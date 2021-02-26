<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function getPhones()
    {
        return Phone::where('contact_id', $this->id)->get();
    }

    public function getEmails()
    {
        return Email::where('contact_id', $this->id)->get();
    }
}
