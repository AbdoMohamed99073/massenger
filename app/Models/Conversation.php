<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id' , 'lable','last_massage_id',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class,'participants')
            ->withPivot([
                'read_at' , 'role'
            ]);
    }

    public function masseges()
    {
        return $this->hasMany(Massage::class,'conversation_id')
            ->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function lastMessage()
    {
        return $this->hasMany(Massage::class,'last_massage_id','id')
            ->withDefault();
    }
}
