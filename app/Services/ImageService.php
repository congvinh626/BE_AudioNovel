<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use ImageIntervention;
use stdClass;
class ImageService
{
    public function storeImage($file, $pathToFile, $nameFile, $createName)
    {
        if ($nameFile) {
            $file_path = storage_path() . '/app/' . $pathToFile . '/' . $nameFile;
            if (File::exists($file_path)) {

                unlink($file_path);
            }
        }

        $fileName = $createName . '_' .  time() . '.' . $file->extension();

        $path = $file->storeAs($pathToFile, $fileName);
        return substr($path, strlen($pathToFile . '/'));
    }

    public function fileUpload($files, $pathToFile)
    {
        // return count($files) ;
        // dd($files);
        foreach ($files as $file) {
            $nameFile =  time() .'_'. $file->getClientOriginalName();
            $file->storeAs($pathToFile, $nameFile);

            $temp = new stdClass;
            $temp->name = $nameFile;
            $temp->type = $file->extension();
            $data[] = $temp;
        }
        
        return $data;
    }

    public function removeFileInStorage($file_uploads)
    {
        
        foreach ($file_uploads as $file_upload) {
            $file_path = storage_path() . '/app/' . $file_upload->file_path;
            unlink($file_path);
        }
        
        return 'ok';
    }
}
