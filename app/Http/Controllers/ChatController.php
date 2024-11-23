<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    //
    function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return view('dashboard', compact('users'));
    }

    function fetchMessages(Request $request)
    {
        $contact = User::findOrFail($request->get('contact_id'));
        $messages =Message::where('from_id',Auth::user()->id)->where('to_id',$contact->id)->orWhere('from_id',$contact->id)->where('to_id',Auth::user()->id)->get();
        return response()->json([
            'contact' => $contact,
            'messages' => $messages
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
        return response($message);

    }
}
