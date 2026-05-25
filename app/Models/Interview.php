<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_id',
        'candidate_name',
        'candidate_email',
        'candidate_phone',
        'interview_date',
        'status',
        'notes',
        'feedback',
        'rating',
        'interviewer_id',
        'resume_path',
    ];

    protected $casts = [
        'interview_date' => 'datetime',
    ];

    // Relationships
    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}