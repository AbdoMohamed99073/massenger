<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessangerController extends Controller
{
    public function index()
    {
        $user =  Auth::user();
        $friend = User::where('id' , '<>' , $user->id)
            ->orderBy('name')
            ->paginate();

        $chats = $user->conversations()->with(['lastMessage' , 'participants'])->get();

        dd($chats);
/*
        return view('Messanger',[
            'friends' => $friend ,
            'chats' => $chats
        ]);*/
    }
}
