<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ProductTag;
use App\Models\ProductImage;
use App\Components\CategoryRecusive;
use App\Traits\StorageImageTrait;
use App\Traits\EditorUploadImage;
use Datatables;
use Storage;
use Log;
use DB;
use Str;

class ProductController extends Controller
{
    use StorageImageTrait;
    use EditorUploadImage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::get();
        return DataTables::of($products)
            ->editColumn('feature_image_path', function ($product) {
                return '<img src="'.$product->feature_image_path.'" />';
            })
            ->editColumn('status', function ($product) {
                return $product->quantity > 0 ? '<span class="btn btn-success">In Stock</span>' : '<span class="btn btn-danger">Out of Stock</span>';
            })
            ->addColumn('action', function ($product) {
                return '<a href="'.route('admin.product.edit', ['id' => $product->id]).'" class="btn btn-info">Edit</a>
                        <a data-href="'.route('admin.product.delete', ['id' => $product->id]).'" class="btn btn-danger action-delete">Delete</a>';
            })
            ->rawColumns(['feature_image_path', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $recusive = new CategoryRecusive(Category::get());
        $htmloptions = $recusive->get_categories_recusive();
        return view('admin.product.create', compact('htmloptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['bail','required','unique:products','min:10','max:255'],
            'category_id' => 'required',
            'price' => ['bail','required','regex:/^\d*(\.\d{0,2})?$/'],
            'quantity' => ['bail','required','integer'],
            'details' => 'required',
            'feature_image_path' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400',
            'image_path' => 'bail|array|max:5',
            'image_path.*' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400'
        ]);

        try {
            DB::beginTransaction();
            $details = $this->SaveUploadEditorImage($request, 'product');

            $dataImageUpload = $this->storageUploadImageTrait($request, 'feature_image_path', "product");
            $dataproduct = [
                'name' =>  $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'slug' => Str::slug($request->name),
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'details' => $details,
                'feature_image_path' => $dataImageUpload['file_path'],
                'feature_image_name' => $dataImageUpload['file_name']
            ];

            $product = Product::create($dataproduct);
            if ($request->image_path !== null) {
                foreach ($request->image_path as $imgitem) {
                    $dataImageDetails = $this->storageUploadMultiImageTrait($imgitem, 'product');
                    $product->images()->create([
                        'image_path' => $dataImageDetails['file_path'],
                        'image_name' => $dataImageDetails['file_name']
                    ]);
                }
            }
    
            foreach ($request->tags as $tag) {
                if(trim($tag) !== '') {
                    $tagInstance = Tag::firstOrCreate(['name' => $tag]);
                    $tagids[] = $tagInstance->id;
                }
            }
            $product->tags()->attach($tagids);
            DB::commit();
            return redirect()->route('admin.product.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $recusive = new CategoryRecusive(Category::get());
        $htmloptions = $recusive->get_categories_recusive([$product->category_id]);
        return view('admin.product.edit', compact('htmloptions', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $request->validate([
            'name' => ['bail','required','unique:products,name,'.$product->slug.',slug','min:10','max:255'],
            'category_id' => 'required',
            'price' => ['bail','required','regex:/^\d*(\.\d{0,2})?$/'],
            'quantity' => ['bail','required','integer'],
            'details' => 'required',
            'feature_image_path' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400',
            'image_path' => 'bail|array|max:5',
            'image_path.*' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400'
        ]);

        try {
            DB::beginTransaction();

            $details = $this->SaveUploadEditorImage($request, 'product');

            $dataImageUploadUpdate = $this->storageUploadImageTrait($request, 'feature_image_path', "product");
            $dataproductUpdate = [
                'name' =>  $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'quantity' => $request->quantity,
                'slug' => Str::slug($request->name),
                'user_id' => auth()->id(),
                'details' => $details
            ];

            if (!empty($dataImageUploadUpdate)) {
                if(file_exists(public_path($product->feature_image_path))) {
                    unlink(public_path($product->feature_image_path));
                }
                $dataproductUpdate['feature_image_path'] = $dataImageUploadUpdate['file_path'];
                $dataproductUpdate['feature_image_name'] = $dataImageUploadUpdate['file_name'];
            }

            $product->update($dataproductUpdate);
            if ($request->hasFile('image_path') && $request->image_path !== null) {
                if ($product->images()->get()) {
                    foreach ($product->images()->get() as $image) {
                        if(file_exists(public_path($image->image_path))) {
                            unlink(public_path($image->image_path));
                        }
                    }
                }
                ProductImage::where('product_id', $id)->delete();
                foreach ($request->image_path as $imgitem) {
                    $dataImageDetails = $this->storageUploadMultiImageTrait($imgitem, 'product');
                    $product->images()->create([
                        'image_path' => $dataImageDetails['file_path'],
                        'image_name' => $dataImageDetails['file_name']
                    ]);
                }
            }
            
            foreach ($request->tags as $tag) {
                $tagInstance = Tag::firstOrCreate(['name' => $tag]);
                $tagids[] = $tagInstance->id;
            }
            $product->tags()->sync($tagids);
            DB::commit();
            return redirect()->route('admin.product.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        $product->tags()->detach();

        $uploadDir = 'public/upload/product/'.Str::slug($product->name);
        
        Storage::deleteDirectory($uploadDir);

        if(file_exists(public_path($product->feature_image_path))) {
            unlink(public_path($product->feature_image_path));
        }

        if ($product->images()->get()) {
            foreach ($product->images()->get() as $image) {
                if(file_exists(public_path($image->image_path))) {
                    unlink(public_path($image->image_path));
                }
            }
        }
        $product->images()->delete();
        $product->delete();

        return response()->json($product);
    }
}
