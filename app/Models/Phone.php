<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    public function addPhone($contactId, $phone)
    {
        return Phone::insertGetId(['contact_id' => $contactId, 'phone' => $phone]);
    }
}
