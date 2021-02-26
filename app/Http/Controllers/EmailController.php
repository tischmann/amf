<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function select($id)
    {
        $email = Email::find($id);

        if (!$email) {
            return response()->json([
                'status' => 0,
                'message' => 'Email not exists'
            ]);
        }

        return response()->json([
            'status' => 1,
            'email' => $email
        ]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'emails' => 'required|Array'
        ]);

        $response = $this->existCheck($request);

        if ($response) {
            return response()->json($response);
        }

        $emails = [];

        foreach ($request->emails as $email) {
            $emails[] = Email::create([
                'contact_id' => $request->contact_id,
                'email' => $email
            ])->id;
        }

        return response()->json([
            'status' => 1,
            'emails' => Email::whereIn('id', $emails)->get()
        ]);
    }

    public function update($id)
    {
        $request = request();

        $this->validate($request, [
            'email' => 'required|String',
        ]);

        $email = Email::find($id);

        if ($email) {
            if ($email->email != $request->email) {
                if ($this->existEmail($request->email)) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Email exists'
                    ]);
                }

                $email->email = $request->email;
                $email->save();
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Email not found'
            ]);
        }

        return response()->json([
            'status' => 1
        ]);
    }

    public function delete($id)
    {
        $email = Email::find($id);

        if (!$email) {
            return response()->json([
                'status' => 0,
                'message' => 'Email not exists'
            ]);
        }

        $email->delete();

        return response()->json([
            'status' => 1
        ]);
    }

    protected function existCheck(Request $request)
    {
        foreach ($request->emails as $email) {
            if ($this->existEmail($email)) {
                return (object) [
                    'status' => 0,
                    'message' => 'Email exists',
                    'value' => $email
                ];
            }
        }

        return false;
    }

    protected function existEmail($email)
    {
        return Email::where('email', $email)->exists();
    }
}
