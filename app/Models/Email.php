<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_id',
        'email',
    ];

    public function addEmail()
    {
        return Email::insertGetId(['contact_id' => $this->contact_id, 'email' => $this->email]);
    }
}
