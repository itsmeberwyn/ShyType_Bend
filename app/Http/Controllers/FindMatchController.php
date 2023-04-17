<?php

namespace App\Http\Controllers;

use App\Models\FindMatch;
use App\Models\Personality;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FindMatchController extends Controller
{
    public function find_match(Request $request)
    {
        $user = User::find($request->userId)->Personality;
        // $user = Personality::WHERE('user_id', $request->userId)->first();

        // dd($user);

        $matches = DB::table('personalities')
            ->select(
                DB::raw("personalities.user_id, 
                    CASE WHEN personalities.question1 = $user->question1 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question2 = $user->question2 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question3 = $user->question3 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question4 = $user->question4 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question5 = $user->question5 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question6 = $user->question6 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question7 = $user->question7 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question8 = $user->question8 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question9 = $user->question9 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question10 = $user->question10 THEN 1 ELSE 0 END AS score,
                    users.firstname
                    JOIN users ON users.id = personalities.user_id
                    WHERE users.gender = '$user->gender'
                    "),
            )
            ->get();

        dd($matches);
    }
}
