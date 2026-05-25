<?php
// app/Models/Lead.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'source',
        'status',
        'assigned_to',
        'notes',
        'potential_value',
        'expected_close_date',
        'created_by',
    ];

    protected $casts = [
        'potential_value' => 'decimal:2',
        'expected_close_date' => 'date',
    ];

    // Relationships
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}