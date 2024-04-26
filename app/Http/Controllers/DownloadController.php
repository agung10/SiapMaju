<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    /**
     * Download mobile apk file
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($filename)
    {
        // Check if file exists in app/storage/file folder
        $file_path = public_path('downloads') .'/'. $filename;

        if (file_exists($file_path))
        {
            // Send Download
            return \Response::download($file_path, $filename, [
                'Content-Length: '. filesize($file_path)
            ]);
        }
        else
        {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }
}
