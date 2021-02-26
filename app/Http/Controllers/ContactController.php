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
            $contact->phones = $contact->getPhones();
            $contact->emails = $contact->getEmails();
        }

        return view('contacts', ["contacts" => $contacts]);
    }

    public function select($id)
    {
        $contact = Contact::find($id);
        $contact->phones = $contact->getPhones();
        $contact->emails = $contact->getEmails();
        return response()->json($contact);
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

    public function update($id)
    {
        $request = request();

        $this->validate($request, [
            'name' => 'required|String',
            'phones' => 'required|Array',
            'emails' => 'required|Array'
        ]);

        $response = $this->existCheck($request);

        if ($response) {
            return response()->json($response);
        }

        $contact = Contact::find($id);

        if ($contact) {
            $contact->name = $request->name;

            Phone::where('contact_id', $contact->id)
                ->whereNotIn('phone', $request->phones)
                ->delete();

            Email::where('contact_id', $contact->id)
                ->whereNotIn('email', $request->emails)
                ->delete();

            foreach ($request->phones as $phone) {
                Phone::upsert([
                    'contact_id' => $contact->id,
                    'phone' => $phone
                ], ['phone'], ['phone']);
            }

            foreach ($request->emails as $email) {
                Email::upsert([
                    'contact_id' => $contact->id,
                    'email' => $email
                ], ['email'], ['email']);
            }

            $contact->save();
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Contact not found'
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

        return response()->json([
            'status' => 1
        ]);
    }

    protected function existCheck(Request $request)
    {
        if ($this->contactExists($request->name, $request->id ?? null)) {
            return (object) [
                'status' => 0,
                'message' => 'Contact exists',
                'value' => $request->name
            ];
        }

        foreach ($request->phones as $phone) {
            if ($this->phoneExists($phone, $request->id ?? null)) {
                return (object) [
                    'status' => 0,
                    'message' => 'Phone exists',
                    'value' => $phone
                ];
            }
        }

        foreach ($request->emails as $email) {
            if ($this->emailExists($email, $request->id ?? null)) {
                return (object) [
                    'status' => 0,
                    'message' => 'Email exists',
                    'value' => $email
                ];
            }
        }

        return false;
    }

    protected function contactExists($name, $contact_id = null)
    {
        $contact = Contact::where('name', $name);

        if ($contact_id) {
            $contact->where('id', '<>', $contact_id);
        }

        return $contact->exists();
    }

    protected function phoneExists($phone, $contact_id = null)
    {
        $phone = Phone::where('phone', $phone);

        if ($contact_id) {
            $phone->where('contact_id', '<>', $contact_id);
        }

        return $phone->exists();
    }

    protected function emailExists($email, $contact_id = null)
    {
        $email = Email::where('email', $email);

        if ($contact_id) {
            $email->where('contact_id', '<>', $contact_id);
        }

        return $email->exists();
    }
}
