<?php
namespace App\Traits;
use Str;
use Storage;

trait EditorUploadImage {
    public function SaveUploadEditorImage ($request, $foldername)
    {
        $dataeditor = html_entity_decode($request->details);
        $dom = new \DomDocument();
        $libxml_previous_state = libxml_use_internal_errors(true);
        $dom->loadHtml(utf8_decode($dataeditor), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_use_internal_errors($libxml_previous_state);
        $imageEditors = $dom->getElementsByTagName('img');
        if(count($imageEditors) > 0) {
            foreach ($imageEditors as $imageEditor => $image) {
                $dataImageEditor = $image->getAttribute('src');
                if (count(explode(';', $dataImageEditor)) > 1) {
                    list($typefiletmp, $dataSrcTmp) = explode(';', $dataImageEditor);
                    list($typefile, $dataSrc) = explode(',', $dataSrcTmp);
                    list($type, $extension) = explode('/', $typefiletmp);
                    $dataSrc = base64_decode($dataSrc);
                    $image_name = time().$imageEditor.'.'.$extension;
                    $patheditor = 'images/upload/'.$foldername.'/'.Str::slug($request->name).'/'.$image_name;
                    Storage::disk('public')->put($patheditor, $dataSrc);
                    $image->removeAttribute('src');
                    $image->setAttribute('src', asset($patheditor));
                }
            }
        }
        $details = $dom->saveHTML();
        return $details;
    }
}