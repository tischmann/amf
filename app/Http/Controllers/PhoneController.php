<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    public function select($id)
    {
        $phone = Phone::find($id);

        if (!$phone) {
            return response()->json([
                'status' => 0,
                'message' => 'Phone not exists'
            ]);
        }

        return response()->json([
            'status' => 1,
            'phone' => $phone
        ]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'phones' => 'required|Array'
        ]);

        $response = $this->existCheck($request);

        if ($response) {
            return response()->json($response);
        }

        $phones = [];

        foreach ($request->phones as $phone) {
            $phones[] = Phone::create([
                'contact_id' => $request->contact_id,
                'phone' => $phone
            ])->id;
        }

        return response()->json([
            'status' => 1,
            'phones' => Phone::whereIn('id', $phones)->get()
        ]);
    }

    public function update($id)
    {
        $request = request();

        $this->validate($request, [
            'phone' => 'required|String',
        ]);

        $phone = Phone::find($id);

        if ($phone) {
            if ($phone->phone != $request->phone) {
                if ($this->existPhone($request->phone)) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Phone exists'
                    ]);
                }

                $phone->phone = $request->phone;
                $phone->save();
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Phone not found'
            ]);
        }

        return response()->json([
            'status' => 1
        ]);
    }

    public function delete($id)
    {
        $phone = Phone::find($id);

        if (!$phone) {
            return response()->json([
                'status' => 0,
                'message' => 'Phone not exists'
            ]);
        }

        $phone->delete();

        return response()->json([
            'status' => 1
        ]);
    }

    protected function existCheck(Request $request)
    {
        foreach ($request->phones as $phone) {
            if ($this->existPhone($phone)) {
                return (object) [
                    'status' => 0,
                    'message' => 'Phone exists',
                    'value' => $phone
                ];
            }
        }

        return false;
    }

    protected function existPhone($phone)
    {
        return Phone::where('phone', $phone)->exists();
    }
}
