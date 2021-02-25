<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_id',
        'phone',
    ];

    public function addPhone()
    {
        return Phone::insertGetId(['contact_id' => $this->contact_id, 'phone' => $this->phone]);
    }
}
