<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'comment_parent_id',
        'user_id',
        'thread_id'
    ];

    public static function createComment(User $user,array $data)
    {
        return self::create([
            'body' => $data['body'],
            'comment_parent_id' => $data['comment_parent_id'] ?? null,
            'user_id'=>$user->id,
            'thread_id'=>$data['thread_id']
        ]);
    }

    public function updateComment(User $user, string $body)
    {
        if($user->id !== $this->user_id){
            throw new Exception("You are not the author");
        }
        $this->update([
            'body' => $body
        ]);
    }

    public function deleteComment(User $user){
        if($user->id !== $this->user_id && !$user->isAdmin()){
            throw new Exception("You are not the author");
        }
        $this->delete();
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name');
    }
    public function replies()
    {
        return $this->hasMany(self::class, 'comment_parent_id')
            ->with(["author", "replies"])
            ->orderBy('created_at');
    }
}
