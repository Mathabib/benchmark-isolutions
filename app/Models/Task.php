<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['project_id', 'nama_task', 'status', 'start_date', 'end_date', 'estimate', 'assign_to', 'priority', 'comment', 'description'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignToUser()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments()
{
    return $this->hasMany(Attachment::class);
}

}