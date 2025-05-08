<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'status',
        'comment'
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
} 