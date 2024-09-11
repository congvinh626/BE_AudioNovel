<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lesson = new FileUpload();
        $lesson->fill($request->all());
        $lesson->save();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fileUpload = FileUpload::findOrFail($id);

        $file_path = storage_path() . '/app/' . $fileUpload->file_path;
        if (File::exists($file_path)) {
            unlink($file_path);
        }

        FileUpload::destroy($fileUpload->id);

        return response()->json([
            'statusCode' => 200,
            'message' => 'Xóa file thành công!'
        ], 200);
    }
}
