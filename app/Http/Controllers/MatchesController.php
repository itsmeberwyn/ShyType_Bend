<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use Illuminate\Http\Request;

class MatchesController extends Controller
{
    public function save_match_response(Request $request)
    {

        $checkMatch = Matches::where("userId", "=", $request->userId)->where("matchUser", "=", $request->matchUser)->update(['response' => $request->response]);

        if ($checkMatch) {

            return [
                "message" => "already matches",
                "status" => 201
            ];
        }

        $match = Matches::create([
            "userId" => $request->userId,
            "matchUser" => $request->matchUser,
            'response' => $request->response,
        ]);

        $matchSave = $match->save();

        if ($matchSave) {
            $res = $this->check_both_user_matches($request);

            return [
                "message" => "Successfully created match",
                "data" => $res,
                "status" => 201
            ];
        }
        return [
            'error' => 'Bad credentials',
            'status' => 401
        ];
    }

    // NOTE: next tash is to check whether the both user matches

    public function check_both_user_matches(Request $request)
    {
        $match1 = Matches::where("userId", "=", $request->matchUser)->where("matchUser", "=", $request->userId)->where("response", "=", "smash")->get();
        $match2 = Matches::where("userId", "=", $request->matchUser)->where("matchUser", "=", $request->userId)->where("response", "=", "like")->get();
        return ([...$match1, ...$match2]);
    }
}
