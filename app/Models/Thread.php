<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'file_id'
    ];

    public static function createThread(User $user,File $file,string $title,string $body = '')
    {
        if(!$file->is_public && $file->user_id !== $user->id){
            throw new Exception("You cant create private thread if file isnt yours", 500);
        }
        return self::create([
            'file_id'=>$file->id,
            'title'=>$title,
            'body'=>$body,
            'user_id'=>$user->id
        ]);
    }

    public static function latestForFile(User $user, File $file, int $limit = 20)
    {

        if(!$file->is_public && $file->user_id !== $user->id){
            throw new Exception('You cant read threads of private file, if file isnt yours', 500);
        }
        return self::where('file_id', $file->id)->
            latest()->limit($limit)->get()->map(fn (self $thread) =>[
            'id'=>$thread->id,
            'title' =>$thread->title,
            'body'=>$thread->body,
            'created_by'=>[
                'id'=>$thread->user_id,
            ],
            'created_at'=>$thread->created_at->toDateTimeString(),
        ]
        );
    }

    public function deleteThread(User $user)
    {
        if($this->created_by !== $user->id && $this->file->user_id !== $user->id){
            throw new Exception("You cannot delete this thread", 500);
        }

        parent::delete();
    }

    public function allComments(){
        return Comment::with(['author','replies'])
            ->where('thread_id', $this->id)->whereNull("comment_parent_id")->orderBy("created_at")->get();
    }

    public function author(){
        return $this->belongsTo(User::class);
    }

    public function file(){
        return $this->belongsTo(File::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
