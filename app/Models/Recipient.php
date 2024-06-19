<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Recipient extends Pivot
{
    use HasFactory;
    public $timestamps = false;

    protected $cast = [
        'read_at' , 'datetime'
    ];


    public function massege()
    {
        return $this->belongsTo(Massage::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
