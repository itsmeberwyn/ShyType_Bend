<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'username' => ['required', 'string', 'unique:users'],
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'bio' => ['required', 'string'],
            'profile' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $user = User::create([
            "username" => $request->username,
            "firstname" => $request->firstname,
            "lastname" => $request->lastname,
            "bio" => $request->bio,
            "profile" => $request->profile,
            "date_verified" => Carbon::now()->toDateTimeString(),
            'password' => Hash::make($request->password),
        ]);

        $newUser = $user->save();

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
        $fields = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $user = User::where('username',  $request->username)->first();

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
