<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Fileable;
use Illuminate\Http\Request;

class FileableController extends Controller
{
    public function show(Request $request, Fileable $fileable)
    {
        if (
            !$request->access_key
            || $fileable->access_key != $request->access_key
        ) {
            abort(404);
        }

        // dd(bin2hex($fileable->file));

        return response($fileable->file)
            ->header('Cache-Control', 'no-cache private')
            // ->header('Content-Description', 'File Transfer')
            ->header('Content-type', $fileable->file_type)
            // ->header('Content-length', $fileable->file_size)
            ->header('Content-Disposition', 'filename=' . $fileable->file_name)
            ->header('Content-Transfer-Encoding', 'binary');
    }
}
