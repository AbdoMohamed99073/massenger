<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PHPUnit\Event\Code\Throwable;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = Auth::user();
        $converstion = $user->conversations()->findOrFail($id);

        return $converstion->messeges()->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => [
                'required',
                'string',
            ],
            'conversation_id' => [
                Rule::requiredIf(function () use ($request) {
                    return !$request->input('user_id');
                }),
                'int',
                'exists:conversations,id',
            ],
            'user_id' => [
                Rule::requiredIf(function () use ($request) {
                    return !$request->input('conversation_id');
                }),
                'int',
                'exists:users,id',
            ],
        ]);
        $user = Auth::user();
        $conversation_id = $request->post('conversation_id');
        $user_id = $request->post('user_id');
        
        DB::beginTransaction();
        try {
            if ($conversation_id) {
                $conversation = $user->conversations()->findOrFail($conversation_id);
            }else{
                $conversation = Conversation::whereHas('participants',function(Builder $builder) use ($user_id , $user){
                    $builder->where('user_id', '=', $user_id);
                });
            }
            $message = $conversation->messages()->create([
                'user_id' => $user->id,
                'bady' => $request->post('message'),
            ]);

            DB::statement('
            INSERT INTO recipients (user_id , message_id)
            SELECT user_id , ? FROM participants
            WHERE conversation_id = ?
        ', [$message->id, $conversation->id]);
            
            DB::commit();

        }catch(Exception $e){
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
