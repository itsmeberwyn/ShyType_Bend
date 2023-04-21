<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use Illuminate\Http\Request;

class MatchesController extends Controller
{
    public function save_match_response(Request $request)
    {
        $match = Matches::create([
            "userId" => $request->userId,
            "matchUser" => $request->matchUser,
            'response' => $request->response,
        ]);

        $matchSave = $match->save();

        if ($matchSave) {
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
}
