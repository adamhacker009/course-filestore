<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileSendRequest;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(FileSendRequest $request)
    {
        $data = $request->only("user", "file", "isPublic");

        dd($data);
    }
}
