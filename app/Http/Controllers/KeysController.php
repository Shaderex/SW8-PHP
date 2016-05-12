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
        $device_id = Input::get('device_id');

        $participant = Participant::firstOrCreate(['device_id' => $device_id]);

        return hex2bin($participant->enc_key);
    }
}
