<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "title",
        "description",
        "due_date",
        "status",
        'user_id'
    ];

    protected $casts = [
        "status" => "boolean"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
