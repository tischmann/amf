<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Email;
use App\Models\Phone;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('id', 'desc')->get();

        foreach ($contacts as $contact) {
            $contact->phones = Phone::where('contact_id', $contact->id)
                ->pluck('phone');

            $contact->emails = Email::where('contact_id', $contact->id)
                ->pluck('email');
        }

        return view('contacts', ["contacts" => $contacts]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|String',
            'phones' => 'required|Array',
            'emails' => 'required|Array'
        ]);

        $response = $this->existCheck($request);

        if ($response) {
            return response()->json($response);
        }

        $contact = Contact::create(['name' => $request->name]);

        foreach ($request->phones as $phone) {
            Phone::create([
                'contact_id' => $contact->id,
                'phone' => $phone
            ]);
        }

        foreach ($request->emails as $email) {
            Email::create([
                'contact_id' => $contact->id,
                'email' => $email
            ]);
        }

        return response()->json([
            'status' => 1
        ]);
    }

    public function delete($id)
    {
        $contact = Contact::find($id);

        if ($contact) {
            Phone::where('contact_id', $contact->id)->delete();
            Email::where('contact_id', $contact->id)->delete();
            $contact->delete();
        }

        return $this->index();
    }

    protected function existCheck(Request $request)
    {
        if ($this->contactExists($request->name)) {
            return (object) [
                'status' => 0,
                'message' => 'Contact exists',
                'value' => $request->name
            ];
        }

        foreach ($request->phones as $phone) {
            if ($this->phoneExists($phone)) {
                return (object) [
                    'status' => 0,
                    'message' => 'Phone exists',
                    'value' => $phone
                ];
            }
        }

        foreach ($request->emails as $email) {
            if ($this->emailExists($email)) {
                return (object) [
                    'status' => 0,
                    'message' => 'Email exists',
                    'value' => $email
                ];
            }
        }

        return false;
    }

    protected function contactExists(string $name)
    {
        return Contact::where('name', $name)->exists();
    }

    protected function phoneExists(string $phone)
    {
        return Phone::where('phone', $phone)->exists();
    }

    protected function emailExists(string $email)
    {
        return Email::where('email', $email)->exists();
    }
}
