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
            'sender' => ['required', 'integer'],
            'receiver' => ['required', 'integer'],
            'message' => ['required', 'string'],
        ]);

        if ($fields->fails()) {
            return [
                'error' => 'Bad credentials',
                'status' => 401
            ];
        }

        $message = new Message;

        $message->sender = $request->sender;
        $message->receiver = $request->receiver;
        $message->message = $request->message;

        if ($request->receiver > $request->sender) {
            $message->conversation_id = $request->receiver . $request->sender;
        } else {
            $message->conversation_id = $request->sender . $request->receiver;
        }

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
        if ($request->receiver > $request->sender) {
            $conversation_id = $request->receiver . $request->sender;
        } else {
            $conversation_id = $request->sender . $request->receiver;
        }

        $messages = Message::where('conversation_id', $conversation_id)->get();
        $user = User::where('id', $request->owner)->get();

        $structMessage = array();
        foreach ($messages as $key => $message) {
            array_push($structMessage, [
                'message' => $messages[$key]->message,
                'time' => Carbon::createFromTimeStamp(strtotime($messages[$key]->created_at))->diffForHumans(),
                'userid' => $messages[$key]->sender,
            ]);
        }

        return ['status' => 'success', 'data' => $structMessage, 'user' => $user];
    }

    public function get_chats(Request $request)
    {
        $messageFrom = Message::where('sender', $request->sender)->orwhere('receiver', $request->sender)->orderBy('created_at', 'desc')->get()->unique('conversation_id');

        $messageInbox = array();
        foreach ($messageFrom as $key => $message) {
            if ($request->sender != $message->sender) {
                $user = User::find($message->sender);
                $messageFrom[$key]['user'] = $user;

                array_push($messageInbox, $message);
            } else if ($request->sender != $message->receiver) {
                $user = User::find($message->receiver);
                $messageFrom[$key]['user'] = $user;

                array_push($messageInbox, $message);
            }
        }

        return ['status' => 'success', 'data' => $messageInbox];
    }
}
