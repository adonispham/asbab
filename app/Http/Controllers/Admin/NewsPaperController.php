<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsPaper;
use App\Traits\StorageImageTrait;
use App\Traits\EditorUploadImage;
use Datatables;
use Storage;
use Log;
use DB;
use Str;

class NewsPaperController extends Controller
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
        $news = NewsPaper::get();
        return DataTables::of($news)
            ->editColumn('image_path', function ($new) {
                return '<img src="'.$new->image_path.'" />';
            })
            ->addColumn('action', function ($new) {
                return '<a href="'.route('admin.news.edit', ['id' => $new->id]).'" class="btn btn-info">Edit</a>
                        <a data-href="'.route('admin.news.delete', ['id' => $new->id]).'" class="btn btn-danger action-delete">Delete</a>';
            })
            ->rawColumns(['image_path', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.news.create');
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
            'name' => ['bail','required','unique:news_papers,title','min:10','max:255'],
            'abstract' => 'required',
            'image_path' => 'bail|required|image|mimes:jpg,jpeg,png,gif|max:102400',
            'details' => 'required',
            'authors' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $details = $this->SaveUploadEditorImage($request, 'newspaper');

            $dataImageUpload = $this->storageUploadImageTrait($request, 'image_path', "newspaper");
            $dataNew = [
                'title' =>  $request->name,
                'abstract' => $request->abstract,
                'details' => $details,
                'slug' => Str::slug($request->name),
                'user_id' => auth()->id(),
                'authors' => $request->authors,
                'image_path' => $dataImageUpload['file_path'],
                'image_name' => $dataImageUpload['file_name']
            ];

            $new = NewsPaper::create($dataNew);
            DB::commit();
            return redirect()->route('admin.news.index');
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
        $new = NewsPaper::find($id);
        return view('admin.news.edit', compact('new'));
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
        $new = NewsPaper::find($id);
        $request->validate([
            'name' => ['bail','required','unique:news_papers,title,'.$new->slug.',slug','min:10','max:255'],
            'abstract' => 'required',
            'image_path' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400',
            'details' => 'required',
            'authors' => 'required'
        ]);
        
        try {
            DB::beginTransaction();

            $details = $this->SaveUploadEditorImage($request, 'newspaper');

            $dataImageUploadUpdate = $this->storageUploadImageTrait($request, 'image_path', "newspaper");
            $dataNewUpdate = [
                'title' =>  $request->name,
                'abstract' => $request->abstract,
                'slug' => Str::slug($request->name),
                'user_id' => auth()->id(),
                'details' => $details,
                'authors' => $request->authors
            ];

            if (!empty($dataImageUploadUpdate)) {
                if(file_exists(public_path($new->image_path))) {
                    unlink(public_path($new->image_path));
                }
                $dataNewUpdate['image_path'] = $dataImageUploadUpdate['file_path'];
                $dataNewUpdate['image_name'] = $dataImageUploadUpdate['file_name'];
            }

            $new->update($dataNewUpdate);
            DB::commit();
            return redirect()->route('admin.news.index');
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
        $new = NewsPaper::find($id);

        $uploadDir = 'public/upload/newspaper/'.$new->slug;
        
        Storage::deleteDirectory($uploadDir);

        if(file_exists(public_path($new->image_path))) {
            unlink(public_path($new->image_path));
        }
        $new->delete();

        return response()->json($new);
    }
}
