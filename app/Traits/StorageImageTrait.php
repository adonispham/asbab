<?php
namespace App\Traits;
use Storage;
use Str;

trait StorageImageTrait {
    public function storageUploadImageTrait($request, $fielname, $foldername)
    {
        if($request->hasFile($fielname)) {
            $file = $request->$fielname;
            $basename = $file->getClientOriginalName();
            $filename = Str::random(20).'.'.$file->extension();
            $path = $request->file($fielname)->storeAs('public/'.$foldername, $filename);
            return [
                'file_name' => $basename,
                'file_path' => Storage::url($path)
            ];
        } else {
            return null;
        }
    }

    public function storageUploadMultiImageTrait($file, $foldername)
    {
        $basename = $file->getClientOriginalName();
        $filename = Str::random(20).'.'.$file->extension();
        $path = $file->storeAs('public/'.$foldername, $filename);
        return [
            'file_name' => $basename,
            'file_path' => Storage::url($path)
        ];
    }
}