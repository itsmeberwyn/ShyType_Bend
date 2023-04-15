<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function send_message(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'sender' => ['required', 'string'],
            'receiver' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $message = Message::create([
            "sender" => $request->senrder,
            "receiver" => $request->receiver,
            'message' => $request->message,
        ]);
        $successMessage = $message->save();

        if ($successMessage) {
            // trigger pusher notification

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

    public function get_conversation(Request $request)
    {
        if ($request->to > $request->from) {
            $conversation_id = $request->to . $request->from;
        } else {
            $conversation_id = $request->from . $request->to;
        }

        $messages = Message::where('conversation_id', $conversation_id)->get();

        $structMessage = array();
        foreach ($messages as $key => $message) {
            array_push($structMessage, [
                'message' => $messages[$key]->message,
                'time' => Carbon::createFromTimeStamp(strtotime($messages[$key]->created_at))->diffForHumans(),
                'userid' => $messages[$key]->from,
            ]);
        }

        return ['status' => 'success', 'data' => $structMessage];
    }

    public function get_chats(Request $request, $userId)
    {
        $messageFrom = Message::where('sender', $request->sender)->orwhere('receiver', $request->sender)->orderBy('created_at', 'desc')->get()->unique('conversation_id');

        $messageInbox = array();
        foreach ($messageFrom as $key => $message) {
            if ($request->from !== $message->from) {
                $user = User::find($message->from);
                $messageFrom[$key]['user'] = $user;

                array_push($messageInbox, $message);
            } else if ($request->from !== $message->to) {
                $user = User::find($message->to);
                $messageFrom[$key]['user'] = $user;

                array_push($messageInbox, $message);
            }
        }

        return ['status' => 'success', 'data' => $messageInbox];
    }
}
