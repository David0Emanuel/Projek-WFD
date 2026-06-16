<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceTicket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'status',
        'status_message',
        'progress_date',
        'completed_date',
        'admin_notes',
    ];
}
