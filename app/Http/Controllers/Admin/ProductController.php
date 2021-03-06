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
use DataTables;
use Storage;
use Log;
use DB;
use Str;

class ProductController extends Controller
{
    use StorageImageTrait;
    use EditorUploadImage;

    public function index($permission)
    {
        $products = Product::get();
        foreach ($products as $p) {
            $p->auth_permission = $permission;
        }
        if ($permission != 0) {
            return DataTables::of($products)
                ->editColumn('feature_image_path', function ($product) {
                    return '<img src="'.asset($product->feature_image_path).'" />';
                })
                ->editColumn('status', function ($product) {
                    return $product->quantity > 0 ? '<span class="btn btn-success">Còn hàng</span>' : '<span class="btn btn-danger">Hết hàng</span>';
                })
                ->addColumn('action', function ($product) {
                    switch ($product->auth_permission) {
                        case '1':
                            $action = '<a href="'.route('admin.product.edit', ['id' => $product->id]).'" class="btn btn-info">Sửa</a>
                                        <a data-href="'.route('admin.product.delete', ['id' => $product->id]).'" class="btn btn-danger action-delete">Xóa</a>';
                            break;
                        case '2':
                            $action = '<a href="'.route('admin.product.edit', ['id' => $product->id]).'" class="btn btn-info">Sửa</a>';
                            break;
                        case '3':
                            $action = '<a data-href="'.route('admin.product.delete', ['id' => $product->id]).'" class="btn btn-danger action-delete">Xóa</a>';
                            break;
                    }
                    return $action;
                })
                ->rawColumns(['feature_image_path', 'status', 'action'])
                ->make(true);
        } else {
            return DataTables::of($products)
                ->editColumn('feature_image_path', function ($product) {
                    return '<img src="'.asset($product->feature_image_path).'" />';
                })
                ->editColumn('status', function ($product) {
                    return $product->quantity > 0 ? '<span class="btn btn-success">Còn hàng</span>' : '<span class="btn btn-danger">Hết hàng</span>';
                })
                ->rawColumns(['feature_image_path','status'])
                ->make(true);
        }
    }

    public function create()
    {
        $recusive = new CategoryRecusive(Category::get());
        $htmloptions = $recusive->get_categories_recusive();
        return view('admin.product.create', compact('htmloptions'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
            'name.max' => 'Độ dài không hợp lệ.',
            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm.',
            'price.required' => 'Hãy điền giá cho sản phẩm.',
            'price.integer' => 'Giá trị bạn nhập không phải là số.',
            'quantity.required' => 'Nhập số lượng hiện có cho sản phẩm.',
            'quantity.integer' => 'Giá trị bạn nhập không phải là số.',
            'details.required' => 'Bạn hãy nhập mô tả cho sản phẩm.',
            'feature_image_path.image' => 'Ảnh không hợp lệ.',
            'feature_image_path.mimes' => 'Ảnh không hợp lệ.',
            'feature_image_path.max' => 'Kích thước ảnh lớn hơn 100KB.',
            'image_path.max' => 'Số lượng ảnh tối đa là 5.',
            'image_path.*.image' => 'Ảnh không hợp lệ.',
            'image_path.*.mimes' => 'Ảnh không hợp lệ.',
            'image_path.*.max' => 'Kích thước ảnh lớn hơn 100KB.'
        ];

        $request->validate([
            'name' => ['bail','required','unique:products','max:255'],
            'category_id' => 'required',
            'price' => ['bail','required','regex:/^\d*(\.\d{0,2})?$/'],
            'quantity' => ['bail','required','integer'],
            'details' => 'required',
            'feature_image_path' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400',
            'image_path' => 'bail|array|max:5',
            'image_path.*' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400'
        ], $messages);

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

    public function edit($id)
    {
        $product = Product::find($id);
        $recusive = new CategoryRecusive(Category::get());
        $htmloptions = $recusive->get_categories_recusive([$product->category_id]);
        return view('admin.product.edit', compact('htmloptions', 'product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $messages = [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
            'name.max' => 'Độ dài không hợp lệ.',
            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm.',
            'price.required' => 'Hãy điền giá cho sản phẩm.',
            'price.integer' => 'Giá trị bạn nhập không phải là số.',
            'quantity.required' => 'Nhập số lượng hiện có cho sản phẩm.',
            'quantity.integer' => 'Giá trị bạn nhập không phải là số.',
            'details.required' => 'Bạn hãy nhập mô tả cho sản phẩm.',
            'feature_image_path.image' => 'Ảnh không hợp lệ.',
            'feature_image_path.mimes' => 'Ảnh không hợp lệ.',
            'feature_image_path.max' => 'Kích thước ảnh lớn hơn 100KB.',
            'image_path.max' => 'Số lượng ảnh tối đa là 5.',
            'image_path.*.image' => 'Ảnh không hợp lệ.',
            'image_path.*.mimes' => 'Ảnh không hợp lệ.',
            'image_path.*.max' => 'Kích thước ảnh lớn hơn 100KB.'
        ];

        $request->validate([
            'name' => ['bail','required','unique:products,name,'.$product->slug.',slug','max:255'],
            'category_id' => 'required',
            'price' => ['bail','required','regex:/^\d*(\.\d{0,2})?$/'],
            'quantity' => ['bail','required','integer'],
            'details' => 'required',
            'feature_image_path' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400',
            'image_path' => 'bail|array|max:5',
            'image_path.*' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400'
        ], $messages);

        try {
            DB::beginTransaction();

            $uploadDir = 'images/upload/product/'.$product->slug;
            Storage::disk('public')->deleteDirectory($uploadDir);

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

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->tags()->detach();
        $uploadDir = 'storage/upload/product/'.$product->slug;
        Storage::disk('public')->deleteDirectory($uploadDir);
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
