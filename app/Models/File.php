<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'path',
        'is_public'
    ];

    public static function upload(User $user, UploadedFile $uploadedFile, array $options = []): self
    {
        $directory = 'files';
        $filename = $uploadedFile->hashName();
        $disk = 'public';

        $path = $uploadedFile->storeAs($directory, $filename, $disk);
        if (!$path) {
            throw new Exception('Could not store file',500);
        }

        $data = [
            "user_id" => $user->id,
            "name" => $options['name'] ?? $uploadedFile->getClientOriginalName(),
            "path" => $path,
            "is_public" => $options["is_public"] ?? false,
        ];

        return self::create($data);
    }

    public function url()
    {
        return Storage::disk('public')->url($this->path);
    }

    public static function latestPublicInfo(int $limit = 40)
    {
        return self::where("is_public", true)->latest('created_at')->limit($limit)->get()
            ->map(function (self $file) {
                return [
                    'id'=>$file->id,
                    'name'=>$file->name,
                    'url'=>$file->url(),
                    'uploader_info'=>[
                        'id'=>$file->user->id,
                        'name'=>$file->user->name,
                    ],
                    'created_at'=>$file->created_at->toDateTimeString(),
                ];
            });
    }
    public static function latestForUser(User $user, int $limit = 40)
    {
        return self::where('user_id', $user->id)->latest('created_at')->limit($limit)->get()
            ->map(function (self $file) {
                return [
                    'id'=>$file->id,
                    'name'=>$file->name,
                    'is_public'=>$file->is_public,
                    'created_at'=>$file->created_at->toDateTimeString(),
                ];
            });
    }
    public function filedelete(User $user): void
    {
        if ($user->id !== $this->user_id && $user->isAdmin() === false) {
            throw new Exception("You dont have access to delete this file", 403);
        }
        if (Storage::disk('public')->exists($this->path)){
            Storage::disk('public')->delete($this->path);
        } else {
            throw new Exception("File does not exist", 404);
        }

        parent::delete();
    }

    public function download(User $user)
    {
        if (!$this->is_public && $user->id !== $this->user_id){
            throw new Exception("You dont have access to download this file", 500);
        }
        if (!Storage::disk('public')->exists($this->path)){
            throw new Exception("File does not exist", 404);
        }

        return Storage::disk('public')
            ->download($this->path, $this->name);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
