<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Email;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        return view('contacts', ["contacts" => (new Contact())->getContacts()]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phones' => 'required',
            'emails' => 'required'
        ]);

        $response = $this->validateContact($request);

        if ($response !== true) {
            return $response;
        }

        $contact = new Contact(['name' => $request->name]);

        $contactId = $contact->addContact();

        foreach ($request->phones as $number) {
            $phone = new Phone(['contact_id' => $contactId, 'phone' => $number]);
            $phone->addPhone();
        }

        foreach ($request->emails as $address) {
            $email = new Email(['contact_id' => $contactId, 'email' => $address]);
            $email->addEmail();
        }

        return response([
            'status' => 1,
            'message' => 'Success'
        ]);
    }

    public function delete($id)
    {
        $contact = new Contact(['id' => $id]);
        $contact->deleteContact();
        return view('contacts');
    }

    protected function validateContact(Request $request)
    {
        foreach ($request->phones as $phone) {
            if (Phone::where('phone', $phone)->exists()) {
                return response([
                    'status' => 0,
                    'message' => "Phone {$phone} is exist"
                ]);
            }
        }

        foreach ($request->emails as $email) {
            if (Email::where('email', $email)->exists()) {
                return response([
                    'status' => 0,
                    'message' => "Email {$email} is exist"
                ]);
            }
        }

        if (Contact::where('name', $request->name)->exists()) {
            return response([
                'status' => 0,
                'message' => "Contact {$request->name} is exist"
            ]);
        }

        return true;
    }
}
