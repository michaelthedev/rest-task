<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'label',
        'description',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime'
    ];
}
