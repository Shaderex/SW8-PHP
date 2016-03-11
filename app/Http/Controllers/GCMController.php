<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Participant;
use Illuminate\Http\Request;

use DataCollection\Http\Requests;

class GCMController extends Controller
{
    public function notifyAll()
    {

    }

    public function registerDevice(Request $request)
    {

        $deviceID = $request->input('deviceID');

        $participant = new Participant;

        $participant->deviceID = $deviceID;

        $participant->save();

        return response($deviceID, 200);

    }
}
