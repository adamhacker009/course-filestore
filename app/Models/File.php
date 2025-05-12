<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            "name" => $uploadedFile->getClientFilename(),
            "path" => $path,
            "is_public" => $options["is_public"] ?? false,
        ];

        return self::create($data);
    }

    public function url()
    {
        if(!$this->is_public){
            return null;
        }

        return Storage::disk('public')->url($this->path);
    }
}
