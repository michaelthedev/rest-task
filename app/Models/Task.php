<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'label',
        'description',
        'due_date',
    ];

    protected $hidden = [
        'id',
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'datetime'
    ];
}
