<?php

namespace App\Http\Controllers;

use App\Models\Personality;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $fieldsuser = Validator::make($request->userBio, [
            'username' => ['required', 'string', 'unique:users'],
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'string'],
            'age' => ['required'],
            'gender' => ['required', 'string'],
            'matchgender' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        $fieldspersonality = Validator::make($request->personality, [
            'question1' => ['required'],
            'question2' => ['required'],
            'question3' => ['required'],
            'question4' => ['required'],
            'question5' => ['required'],
            'question6' => ['required'],
            'question7' => ['required'],
            'question8' => ['required'],
            'question9' => ['required'],
            'question10' => ['required'],
        ]);

        if ($fieldsuser->fails() || $fieldspersonality->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $user = User::create([
            "username" => $request->userBio['username'],
            "firstname" => $request->userBio['firstname'],
            "lastname" => $request->userBio['lastname'],
            "email" => $request->userBio['email'],
            "age" => $request->userBio['age'],
            "bio" => '',
            "contact" => "",
            "gender" => $request->userBio['gender'],
            "matchgender" => $request->userBio['matchgender'],
            "profile" => "",
            "ishidden" => 0,
            "date_verified" => Carbon::now()->toDateTimeString(),
            'password' => Hash::make($request->password),
        ]);

        $newUser = $user->save();

        $personality = Personality::create([
            "user_id" => $user->id,
            "question1" => $request->personality['question1'] === 'true' ? 1 : 0,
            "question2" => $request->personality['question2'] === 'true' ? 1 : 0,
            "question3" => $request->personality['question3'] === 'true' ? 1 : 0,
            "question4" => $request->personality['question4'] === 'true' ? 1 : 0,
            "question5" => $request->personality['question5'] === 'true' ? 1 : 0,
            "question6" => $request->personality['question6'] === 'true' ? 1 : 0,
            "question7" => $request->personality['question7'] === 'true' ? 1 : 0,
            "question8" => $request->personality['question8'] === 'true' ? 1 : 0,
            "question9" => $request->personality['question9'] === 'true' ? 1 : 0,
            "question10" => $request->personality['question10'] === 'true' ? 1 : 0,
        ]);

        $personality->save();

        if ($newUser) {
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
        $fields = Validator::make($request->credentials, [
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $user = User::where('email',  $request->credentials['email'])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => "Bad credentials",
                'status' => 401
            ];
        }

        return [
            "message" => "Successfully logged in",
            "data" => $user,
            "status" => 200,
        ];
    }

    public function profile(Request $request)
    {
        $compPic = "";
        if (isset($request->user['profile'])) {
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->user['profile']));
            $compPic =  '_image' . time() . '.' . 'jpg';
            Storage::disk('public')->put($compPic, $image);
        }

        $fields = Validator::make($request->user, [
            'username' => ['required', 'string'],
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        if (isset($request->user['npassword'])) {
            $password = $request->user['npassword'];
        } else {
            $password = $request->user['password'];
        }

        $user = User::find($request->user['id']);
        $user->username = $request->user['username'];
        $user->bio = isset($request->user['bio']) ? $request->user['bio'] : "";
        $user->contact = isset($request->user['contact']) ? $request->user['contact'] : "";
        $user->profile = $compPic !== '' ? $compPic : "";
        $user->password = isset($request->user['npassword']) ? Hash::make($password) : $password;
        $user->save();

        return [
            "message" => "Successfully updated data",
            "data" => $user,
            "status" => 200,
        ];
    }

    public function user_hide_unhide_info(Request $request)
    {
        $fields = Validator::make($request->user, [
            'ishidden' => ['required', 'string'],
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $user = User::find($request->user['id']);
        $user->ishidden = $request->user['ishidden'];
        $user->save();

        return [
            "message" => "Successfully updated data",
            "data" => $user,
            "status" => 200,
        ];
    }
}
