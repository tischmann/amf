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

    public function getContacts()
    {
        $contacts = Contact::orderBy('id')
            ->get();

        foreach ($contacts as $contact) {
            $contact->phones = Phone::where('contact_id', $contact->id)
                ->pluck('phone');

            $contact->emails = Email::where('contact_id', $contact->id)
                ->pluck('email');
        }

        return $contacts;
    }

    public function addContact()
    {
        return Contact::insertGetId(['name' => $this->name]);
    }
}
