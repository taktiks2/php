<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    /**
     * マスアサインメント可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'completed',
    ];

    /**
     * キャストする属性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed' => 'boolean',
    ];
}
