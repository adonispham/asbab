<?php

namespace App\Http\Controllers\Admin;

use App\Events\Guest\CommentNews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Traits\StorageImageTrait;
use App\Traits\EditorUploadImage;
use DataTables;
use File;
use Storage;
use Log;
use DB;
use Str;

class NewsController extends Controller
{
    use StorageImageTrait;
    use EditorUploadImage;

    public function index($permission)
    {
        $news = News::get();
        foreach ($news as $n) {
            $n->auth_permission = $permission;
        }
        if ($permission != 0) {
            return DataTables::of($news)
                ->editColumn('image_path', function ($new) {
                    return '<img src="' . asset($new->image_path) . '" />';
                })
                ->addColumn('action', function ($new) {
                    switch ($new->auth_permission) {
                        case '1':
                            $action = '<a href="' . route('admin.news.edit', ['id' => $new->id]) . '" class="btn btn-info">Sửa</a>
                                        <a data-href="' . route('admin.news.delete', ['id' => $new->id]) . '" class="btn btn-danger action-delete">Xóa</a>';
                            break;
                        case '2':
                            $action = '<a href="' . route('admin.news.edit', ['id' => $new->id]) . '" class="btn btn-info">Sửa</a>';
                            break;
                        case '3':
                            $action = '<a data-href="' . route('admin.news.delete', ['id' => $new->id]) . '" class="btn btn-danger action-delete">Xóa</a>';
                            break;
                    }
                    return $action;
                })
                ->rawColumns(['image_path', 'action'])
                ->make(true);
        } else {
            return DataTables::of($news)
                ->editColumn('image_path', function ($new) {
                    return '<img src="' . asset($new->image_path) . '" />';
                })
                ->rawColumns(['image_path'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Tiêu đề bài viết không được để trống.',
            'name.unique' => 'Tiêu đề bài viết đã tồn tại.',
            'name.max' => 'Tiêu đề bài viết quá dài.',
            'abstract.required' => 'Hãy nhập mô tả ngắn cho bài viết.',
            'image_path.required' => 'Vui lòng chọn ảnh đại diện cho bài viết.',
            'image_path.image' => 'Ảnh không đúng định dạng.',
            'image_path.mimes' => 'Ảnh không đúng định dạng.',
            'image_path.max' => 'Kích thước ảnh lớn hơn 100KB.',
            'details.required' => 'Hãy thêm nội dung bài viết.',
            'authors.required' => 'Vui lòng thêm tác giả của bài viết.',
        ];

        $request->validate([
            'name' => ['bail', 'required', 'unique:news,title', 'max:255'],
            'abstract' => 'required',
            'image_path' => 'bail|required|image|mimes:jpg,jpeg,png,gif|max:102400',
            'details' => 'required',
            'authors' => 'required'
        ], $messages);

        try {
            DB::beginTransaction();
            $details = $this->SaveUploadEditorImage($request, 'news');

            $dataImageUpload = $this->storageUploadImageTrait($request, 'image_path', "news");
            $dataNew = [
                'title' => $request->name,
                'abstract' => $request->abstract,
                'details' => $details,
                'slug' => Str::slug($request->name),
                'user_id' => auth()->id(),
                'authors' => $request->authors,
                'image_path' => $dataImageUpload['file_path'],
                'image_name' => $dataImageUpload['file_name']
            ];

            News::create($dataNew);
//            broadcast(new CommentNews($avatar, $chatContent));

            DB::commit();
            return redirect()->route('admin.news.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: ' . $exception->getMessage() . ' line: ' . $exception->getLine());
            return back();
        }
    }

    public function edit($id)
    {
        $new = News::find($id);
        return view('admin.news.edit', compact('new'));
    }

    public function update(Request $request, $id)
    {
        $new = News::find($id);
        $messages = [
            'name.required' => 'Tiêu đề bài viết không được để trống.',
            'name.unique' => 'Tiêu đề bài viết đã tồn tại.',
            'name.max' => 'Tiêu đề bài viết quá dài.',
            'abstract.required' => 'Hãy nhập mô tả ngắn cho bài viết.',
            'image_path.required' => 'Vui lòng chọn ảnh đại diện cho bài viết.',
            'image_path.image' => 'Ảnh không đúng định dạng.',
            'image_path.mimes' => 'Ảnh không đúng định dạng.',
            'image_path.max' => 'Kích thước ảnh lớn hơn 100KB.',
            'details.required' => 'Hãy thêm nội dung bài viết.',
            'authors.required' => 'Vui lòng thêm tác giả của bài viết.',
        ];

        $request->validate([
            'name' => ['bail', 'required', 'unique:news,title,' . $new->slug . ',slug', 'max:255'],
            'abstract' => 'required',
            'image_path' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400',
            'details' => 'required',
            'authors' => 'required'
        ], $messages);
        try {
            DB::beginTransaction();
            $uploadDir = public_path('images/upload/news/' . $new->slug);
            if (File::isDirectory($uploadDir)) {
                File::deleteDirectory($uploadDir);
            }
            $details = $this->SaveUploadEditorImage($request, 'news');
            $dataImageUploadUpdate = $this->storageUploadImageTrait($request, 'image_path', "news");
            $dataNewUpdate = [
                'title' => $request->name,
                'abstract' => $request->abstract,
                'slug' => Str::slug($request->name),
                'user_id' => auth()->id(),
                'details' => $details,
                'authors' => $request->authors
            ];
            if (!empty($dataImageUploadUpdate)) {
                if (file_exists(public_path($new->image_path))) {
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
            Log::error('Message: ' . $exception->getMessage() . ' line: ' . $exception->getLine());
            return back();
        }
    }

    public function destroy($id)
    {
        $new = News::find($id);
        $uploadDir = 'images/upload/news/' . $new->slug;
        Storage::disk('public')->deleteDirectory($uploadDir);
        if (file_exists(public_path($new->image_path))) {
            unlink(public_path($new->image_path));
        }
        $new->delete();
        return response()->json($new);
    }
}
