<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['nama', 'deskripsi'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}