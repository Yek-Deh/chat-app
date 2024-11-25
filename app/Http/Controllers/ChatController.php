<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    //
    function getLastMessages($userId)
    {
        return Message::where('from_id', Auth::id())
            ->where('to_id', $userId)->orWhere('from_id', $userId)->where('to_id',Auth::id())
            ->latest()  // Orders by 'created_at' in descending order (most recent first)
            ->first(); // Gets only the most recent (first) record
    }
    function index()
    {

        $users = User::where('id', '!=', Auth::id())->get();
        $userMessages = [];
        foreach ($users as $user) {
            $message = $this->getLastMessages($user->id);
            $userMessages[$user->id] = $message ? $message->message:'no message';
        }

        return view('dashboard', compact('users', 'userMessages'));
    }

    function fetchMessages(Request $request)
    {
        $contact = User::findOrFail($request->get('contact_id'));
        $messages =Message::where('from_id',Auth::user()->id)->where('to_id',$contact->id)->orWhere('from_id',$contact->id)->where('to_id',Auth::user()->id)->get();
//        $latestMessage = $messages->last();
        return response()->json([
            'contact' => $contact,
            'messages' => $messages,
//            'latestMessage' => $latestMessage ? $latestMessage->message:'no message',
        ]);
    }

    function sendMessage(Request $request)
    {
        $request->validate([
            'contact_id' => ['required'],
            'message' => ['required', 'string'],
        ]);

        $message = new Message();
        $message->from_id = Auth::user()->id;
        $message->to_id = $request->contact_id;
        $message->message = $request->message;
        $message->save();
        $time=$message->created_at;

        event(new SendMessageEvent($message->message,Auth::user()->id,$request->contact_id,$time));
        return response($message);

    }
}
