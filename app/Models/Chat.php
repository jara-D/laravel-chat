<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     *
     * @var array<int, int>
     */
    protected $fillable = [
        'user_id',
        'contact_id',
        'approved'
    ];

}
