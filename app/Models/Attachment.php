<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
  protected $fillable = [
        'task_id',
        'filename', // tambahkan ini
    ];

    public function task()
{
    return $this->belongsTo(Task::class);
}

}
