<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    public function index(Request $request)
    {
        $contacts = DB::table('contacts')
            ->select('id', 'name')
            ->orderBy('id')
            ->get();

        foreach ($contacts as $contact) {
            $contact->phones = DB::table('phones')
                ->where('contact_id', $contact->id)
                ->pluck('phone');

            $contact->emails = DB::table('emails')
                ->where('contact_id', $contact->id)
                ->pluck('email');
        }

        return view('contacts', ["contacts" => $contacts]);
    }
}
