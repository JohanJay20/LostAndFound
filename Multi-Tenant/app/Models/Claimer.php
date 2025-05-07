<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claimer extends Model
{
    protected $fillable = [
        'lost_and_found_id',
        'name',
        'contact_number',
        'email',
    ];

    public function lostAndFound()
    {
        return $this->belongsTo(LostAndFound::class);
    }
}
