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
        $user = User::find($request->userId);
        $personality = User::find($request->userId)->Personality;
        // $user = Personality::WHERE('user_id', $request->userId)->first();

        $matches = DB::table('personalities')
            ->select(
                DB::raw("personalities.user_id, users.*,
                    CASE WHEN personalities.question1 = $personality->question1 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question2 = $personality->question2 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question3 = $personality->question3 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question4 = $personality->question4 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question5 = $personality->question5 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question6 = $personality->question6 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question7 = $personality->question7 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question8 = $personality->question8 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question9 = $personality->question9 THEN 1 ELSE 0 END +
                    CASE WHEN personalities.question10 = $personality->question10 THEN 1 ELSE 0 END AS score
                    "),
            )
            ->leftJoin("users", "users.id", "=", "personalities.user_id")
            ->where("personalities.user_id", "<>", $request->userId)
            ->where("users.gender", "=", $user->matchgender)
            ->get();

        return [
            "message" => "Successfully pulled data",
            "data" => $matches,
            "status" => 200
        ];
    }

    public function recent_match(Request $request)
    {
        // check if the two user matches by checking their recent matches
    }
}
