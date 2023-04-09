<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:admins'],
            'password' => ['required', 'string'],
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $admin = Admin::create([
            "name" => $request->name,
            "email" => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $newAdmin = $admin->save();

        if ($newAdmin) {
            return [
                "message" => "Successfully registered",
                "status" => 201
            ];
        }
        return [
            'error' => 'Bad credentials',
            'status' => 401
        ];
    }

    public function login(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $user = Admin::where('email',  $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => "Bad credentials",
                'status' => 401
            ];
        }

        return [
            "message" => "Successfully logged in",
            "status" => 200
        ];
    }
}
