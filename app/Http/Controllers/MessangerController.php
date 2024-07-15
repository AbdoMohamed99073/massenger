<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessangerController extends Controller
{
    public function index($id = null)
    {
        $user =  Auth::user();
        $friend = User::where('id' , '<>' , $user->id)
            ->orderBy('name')
            ->paginate();

        $chats = $user->conversations()->with([
            'lastMessage' ,
            'participants' => function($builder) use ($user){
                $builder->where('id' , '<>' , $user->id);
            }])->get();

        $messages = [];
        if($id)
        {
            $chat = $chats->where('id' , $id)->first();
            $messages = $chat->messages()->with('user')->paginate();
        }


        return view('Messanger',[
            'friends' => $friend ,
            'chats' => $chats,
            'messages' =>$messages,

        ]);
    }
}