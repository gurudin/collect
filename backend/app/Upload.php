<?php

namespace App;

use Illuminate\Http\Request;

class Upload
{
    /**
     * Upload file.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        if ($request->file('file')) {
            $file = $request->file('file');
        } else {
            $file = $request->file('upload');
        }
        $extension = $file->getClientOriginalExtension();
        $targetPath = sprintf(
            '/public/images/%s/%s',
            date('Y'),
            date('m')
        );
        $fileName = time() . random_int(1000, 9999) . '.' . $extension;

        $path =  $file->storeAs(
            $targetPath,
            $fileName
        );

        if ($path) {
            return ['status' => true, 'path' => str_replace('public', 'storage', $targetPath) . '/' . $fileName];
        } else {
            return ['status' => false, 'msg' => 'upload error.'];
        }
    }
}
