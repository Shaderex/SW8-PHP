<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Participant;
use Illuminate\Http\Request;

use DataCollection\Http\Requests;
use PHP_GCM\InvalidRequestException;
use PHP_GCM\Sender;
use PHP_GCM\Message;

class GCMController extends Controller
{
    private static $gcmApiKey = "AIzaSyDpHBqbWCfpouWT7vbJ564vymSjT7zvchM";

    public function notifyAll($msg = "")
    {
        $numberOfRetryAttempts = 5000;

        $sender = new Sender(GCMController::$gcmApiKey);
        $message = new Message(time(), ['message' => $msg]);

        foreach(Participant::all() as $participant) {

            $deviceID = $participant->deviceID;
            try {
                $result = $sender->send($message, $deviceID, $numberOfRetryAttempts);
            } catch (\InvalidArgumentException $e) {
                // $deviceRegistrationId was null
            } catch (InvalidRequestException $e) {
                // server returned HTTP code other than 200 or 503
            } catch (\Exception $e) {
                // message could not be sent
            }
        }

        return;
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
