<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\Request;
use Throwable;

class CommentController extends Controller
{
    public function publish(CommentCreateRequest $request)
    {
        try {
            $comment = Comment::createComment($request->user(),$request->all());
            return response()->json($comment, 201);
        } catch (Throwable $e) {
            return response()->json([$e->getMessage()]);
        }
    }
    public function update(Request $request, int $id){
        $comment = Comment::findOrFail($id);
        try {
            $comment->updateComment($request->user(), $request->body);
            return response()->json($comment, 200);
        } catch (Throwable $e) {
            return response()->json([$e->getMessage()]);
        }
    }
    public function delete(Request $request,int $id){
        $comment = Comment::findOrFail($id);

        try{
            $comment->deleteComment($request->user());
            return response()->json('Comment deleted', 200);
        } catch (Throwable $e) {
            return response()->json([$e->getMessage()]);
        }
    }
    public function getComments(Request $request, int $id)
    {
        $thread = Thread::findOrFail($id);
        $comments = $thread->allComments();
        return response()->json($comments, 200);
    }
}
