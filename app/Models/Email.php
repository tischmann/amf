<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    public function addEmail($contactId, $email)
    {
        return Email::insertGetId(['contact_id' => $contactId, 'email' => $email]);
    }
}
