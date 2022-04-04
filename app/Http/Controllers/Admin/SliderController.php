<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Traits\StorageImageTrait;
use Datatables;
use Storage;
use Log;
use DB;
use Str;

class SliderController extends Controller
{
    use StorageImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::get();
        return DataTables::of($sliders)
            ->editColumn('image_path', function ($slider) {
                return '<img src="'.$slider->image_path.'" />';
            })
            ->addColumn('action', function ($slider) {
                return '<a data-href="'.route('admin.slider.edit', ['id' => $slider->id]).'" class="btn btn-info action-edit" data-toggle="modal" href="#editSlider">Edit</a>
                        <a data-href="'.route('admin.slider.delete', ['id' => $slider->id]).'" class="btn btn-danger action-delete">Delete</a>';
            })
            ->rawColumns(['image_path', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => ['bail','required','min:1','max:255'],
            'image_path' => 'bail|required|image|mimes:jpg,jpeg,png,gif',
            'description' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $dataImageUpload = $this->storageUploadImageTrait($request, 'image_path', "slider");

            $slider = Slider::create([
                'name' => $request->name,
                'image_path' => $dataImageUpload['file_path'],
                'image_name' => $dataImageUpload['file_name'],
                'description' => $request->description,
                'slug' => Str::slug($request->name)
            ]);
    
            DB::commit();
            return response()->json($slider);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return response()->json([
                'message' => 'There are incorrect values in the form !',
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
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
        $slider = Slider::find($id);
        $updateUrl = route('admin.slider.update', ['id' => $id]);
        return response()->json([
            'slider' => $slider,
            'updateUrl' => $updateUrl
        ]);
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
        $slider = Slider::find($id);
        $validator = $request->validate([
            'name' => ['bail','required','min:2','max:255'],
            'description' => 'required',
            'image_path' => 'bail|image|mimes:jpg,jpeg,png,gif|max:102400'
        ]);
        try {
            DB::beginTransaction();

            $dataImageUploadUpdate = $this->storageUploadImageTrait($request, 'image_path', "slider");
            $dataSLiderUpdate = [
                'name' =>  $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description
            ];

            if (!empty($dataImageUploadUpdate)) {
                if(file_exists(public_path($slider->image_path))) {
                    unlink(public_path($slider->image_path));
                }
                $dataSLiderUpdate['image_path'] = $dataImageUploadUpdate['file_path'];
                $dataSLiderUpdate['image_name'] = $dataImageUploadUpdate['file_name'];
            }
            $slider = Slider::find($id);
            $slider->update($dataSLiderUpdate);
            DB::commit();
            return response()->json($slider);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: '.$exception->getMessage().' line: '.$exception->getLine());
            return response()->json([
                'message' => 'There are incorrect values in the form !',
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);;
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
        $slider = Slider::find($id);

        if(file_exists(public_path($slider->image_path))) {
            unlink(public_path($slider->image_path));
        }

        $slider->delete();

        return response()->json($slider);
    }
}
