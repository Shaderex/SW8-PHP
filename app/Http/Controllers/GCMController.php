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

        // Get the deviceID from the request
        $deviceID = $request->input('deviceID');

        // If the participant with that deviceID does not exist
        if (!Participant::where('deviceID', '=', $deviceID)->exists()) {
            $participant = new Participant;

            $participant->deviceID = $deviceID;

            $participant->save();
        }

        return;

    }
}
