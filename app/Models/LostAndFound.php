<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostAndFound extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'lost_and_founds';

    // The attributes that are mass assignable
    protected $fillable = [
        'item_name',
        'description',
        'found_at',
        'status',
        'location',
        'category',
    ];

    // The attributes that should be hidden for arrays (e.g., if you use the model in an API)
    protected $hidden = [];

    // The attributes that should be cast to a native type
    protected $casts = [
        'found_at' => 'datetime', // Ensure found_at is cast to a datetime
    ];
    public function claimer()
{
    return $this->hasOne(Claimer::class);
}

}
