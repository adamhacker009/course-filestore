<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function registerUser(array $data):array
    {
        $data['password'] = Hash::make($data['password']);

        $user = self::create($data);

        $token = $user->createToken('default')->plainTextToken;

        return ["user" => $user, "token" => $token];
    }

    public function Auth(array $data):array
    {
        $user = self::where('email', $data['email'])->first();

        if (!$user){
            throw new Exception("User not found", 404);
        }

        if(!Hash::check($data['password'], $user->password)){
            throw new Exception("Wrong password", 401);
        }

        $token = $user->createToken('default')->plainTextToken;

        return ['token' => $token];
    }

    public function changePassword(array $data)
    {
        if(!Hash::check($data['current_password'], $this->password)){
            throw new Exception("Wrong password", 401);
        }

        if($data['current_password'] === $data["new_password"]){
            throw new Exception("New password cannot be the same as current password", 401);
        }

        $this->password  = Hash::make($data['new_password']);
        $this->save();
    }

    public function deleteUser(bool $delete)
    {
        switch ($delete){
            case true:
                $this->delete();
            case false:
                throw new Exception("You didnt agree to delete your account", 403);
        }
    }
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
