<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessangerController extends Controller
{
    public function index($id = null)
    {
        $user =  Auth::user();
        $friends = User::where('id' , '<>' , $user->id)
            ->orderBy('name')
            ->paginate();

        $chats = $user->conversations()->with([
            'lastMessage' ,
            'participants' => function($builder) use ($user){
                $builder->where('id' , '<>' , $user->id);
            }])->get();

        $messages = [];
        $activeChat = new Conversation();
        if($id)
        {
            $activeChat = $chats->where('id' , $id)->first();
            $messages = $activeChat->messages()->with('user')->paginate();
        }else{
            $id = $user->conversations[0]->id;
            $activeChat = $chats->where('id' , $id)->first();
            $messages = $activeChat->messages()->with('user')->paginate();
        }


        return view('Messanger',[
            'friends' => $friends ,
            'chats' => $chats,
            'messages' =>$messages,
            'activeChat' => $activeChat

        ]);
    }
}