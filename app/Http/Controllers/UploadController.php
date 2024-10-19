<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Handler\ResumableJSUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploadController
{
    public function index()
    {
        return view('upload-big-file');
    }

    /**
     * @throws UploadFailedException
     */
    public function store(Request $request)
    {
        $receiver = new FileReceiver($request->file, $request, ResumableJSUploadHandler::class);

        $save = $receiver->receive();

        if($save->isFinished())
        {
            $file = $save->getFile();
            $newFileName = $file->hashName();

            $file->move(storage_path('app/private/files'), $newFileName);
        }

        $handler = $save->handler();

        return response()->json([
            'progress' => $handler->getPercentageDone(),
        ]);
    }
}
