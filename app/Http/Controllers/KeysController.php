<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Participant;
use Illuminate\Http\Request;

use DataCollection\Http\Requests;
use Illuminate\Support\Facades\Input;

class KeysController extends Controller
{
    public function getKey()
    {
        Participant::firstOrCreate(['device_id' => 'someString']);
        $device_id = Input::get('device_id');

        $participant = Participant::whereDeviceId($device_id)->firstOrFail();

        return $participant->enc_key;
    }
}
