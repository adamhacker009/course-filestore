<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\PassChangeRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->only('name', 'email', 'password');

        $token = User::registerUser($data);

        return response()->json([
            'message' => 'User registered successfully',
            "data" => ['token' => $token]
        ], 201);
    }

    public function login(AuthRequest $request)
    {
        $data = $request->only('email', 'password');

        ['token' => $token] = User::Auth($data);

        return response()->json([
            'message' => 'User login successfully',
            "data" => ['token' => $token]
        ], 200);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(["message" => "Successfully logged out"], 200);
    }

    public function changePassword(PassChangeRequest $request)
    {
        $data = $request->only('current_password', 'new_password');

        $request->user()->changePassword($data);

        return response()->json([
            'message' => 'Password changed successfully. Re login with new password',
        ], 200);
    }

    public function deleteAccount(Request $request)
    {
        User::deleteUser($request->delete);

        $request->user()->deleteUser();

        return response()->json(["message" => "User deleted successfully"], 200);
    }

    public function promote(Request $request,int $id)
    {
        $target = User::findOrFail($id);

        try {
            $target->makeAdmin($request->user());
            return response()->json(['message' => 'User promoted successfully'], 200);
        } catch (Throwable $th) {
            return response()->json(['message' => $th->getCode()]);
        }
    }
}
