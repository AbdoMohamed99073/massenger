<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipient extends Pivot
{
    use HasFactory , SoftDeletes;
    public $timestamps = false;

    protected $cast = [
        'read_at' => 'datetime'
    ];


    public function massege()
    {
        return $this->belongsTo(Message::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
