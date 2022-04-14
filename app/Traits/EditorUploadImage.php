<?php
namespace App\Traits;
use Str;
use Storage;

trait EditorUploadImage {
    public function SaveUploadEditorImage ($request, $foldername)
    {
        $details = $request->details;
        $detailsArray = explode('src="data:', $details);
        $results = [];
        if (count($detailsArray) > 1) {
            foreach ($detailsArray as $k => $d) {
                $results[$k] = $d;

                if (strpos($d, ';base64,')) {
                    $dA = explode('" data-filename="', $d);
                    if (count($dA) > 1) {
                        list($typefiletmp, $dataSrcTmp) = explode(';', $dA[0]);
                        list($typefile, $dataSrc) = explode(',', $dA[0]);
                        list($type, $extension) = explode('/', $typefiletmp);
                        $dataSrc = base64_decode($dataSrc);
                        $image_name = time() . $k . '.' . $extension;
                        $patheditor = 'public/upload/' . $foldername . '/' . Str::slug($request->name) . '/' . $image_name;
                        Storage::put($patheditor, $dataSrc);
                        $path = 'storage/upload/' . $foldername . '/' . Str::slug($request->name) . '/' . $image_name;
                        $result = implode('" data-filename="', [asset($path), $dA[1]]);
                        $results[$k] = $result;
                    }
                }
            }
        }
        if (count($results) > 0) {
            return implode('src="', $results);
        } else {
            return $details;
        }
    }
}
