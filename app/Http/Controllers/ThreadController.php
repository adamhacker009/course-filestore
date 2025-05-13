<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadCreateRequest;
use App\Models\File;
use App\Models\Thread;
use Exception;
use Illuminate\Http\Request;


class ThreadController extends Controller
{
    public function createThread(ThreadCreateRequest $request, int $fileId)
    {
        $file = File::findOrFail($fileId);

        try{
            $thread = Thread::createThread($request->user(), $file, $request->title, $request->body);
            return response()->json([
                'id' => $thread->id,
                'title' => $thread->title,
                'created_at' => $thread->created_at,]);
        } catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function newThreads(Request $request, int $fileId)
    {
        $file = File::findOrFail($fileId);

        try {
            $threads = Thread::latestForFile($request->user(), $file, 20);
            return response()->json(
                $threads
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function deleteThread(Request $request, int $threadId)
    {
        $thread = Thread::findOrFail($threadId);

        try {
            $thread->deleteThread($request->user());
            return response()->json(['message' => 'Thread deleted']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
