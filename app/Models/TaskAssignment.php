<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['subtask_id', 'user_id', 'rating', 'comment', 'addDate'];

    public function subtask()
    {
        return $this->belongsTo(SubTask::class, 'subtask_id')->with('task');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
