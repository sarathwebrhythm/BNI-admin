<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'total_records',
        'imported_records',
        'skipped_records',
        'status',
        'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }
}