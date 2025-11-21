<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'location_text',
        'urgency',
        'is_resolved',
        'reporter_id',
        'latitude',
        'longitude',
        'image_path',
        'admin_note',
    ];

    // Complaint belongs to a reporter
    public function reporter()
    {
        return $this->belongsTo(Reporter::class);
    }
}
