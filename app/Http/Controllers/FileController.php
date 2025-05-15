<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileSendRequest;
use App\Models\File;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(FileSendRequest $request)
    {

        try{
            $file = File::upload(
                $request->user(),
                $request->file('file'),
                [
                    'name' => $request->name ?? null,
                    'is_public' => $request->is_public ?? null,
                ]
            );
        } catch (Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
        return response()->json([
            'id'=>$file->id,
            'name'=>$file->name,
            'url'=>$file->url(),
            'is_public'=>$file->is_public,
            'created_at'=>$file->created_at->toDateTimeString(),
            ],200);
    }

    public function indexPublic():JsonResponse
    {
        $files=File::latestPublicInfo();
        return response()->json([$files],200);
    }

    public function indexUser(Request $request):JsonResponse
    {
        $files=File::latestForUser($request->user());
        return response()->json([$files],200);
    }

    public function download(Request $request, File $file)
    {
        try {
            return $file->download($request->user());
        } catch (Exception $e){
            return response()->json(['error' => $e->getMessage()],$e->getCode());
        }
    }

    public function delete(Request $request,int $id)
    {
        $file=File::findOrFail($id);

        try{
            $file->filedelete($request->user());
        }catch (Exception $e){
            return response()->json(['error' => $e->getMessage()],$e->getCode());
        }

        return response()->json(['message'=>'File deleted'],200);
    }
}
