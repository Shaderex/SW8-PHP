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
    public function notifyAll($msg = "")
    {
        $numberOfRetryAttempts = 5000;

        $sender = new Sender(env('GCM_SECRET'));
        $message = new Message(time(), ['message' => $msg]);
        $amountSent = 0;

        foreach(Participant::all() as $participant) {
            try {
                $result = $sender->send($message, $participant->device_id, $numberOfRetryAttempts);
                $amountSent++;
            } catch (\InvalidArgumentException $e) {
                // $deviceRegistrationId was null
            } catch (InvalidRequestException $e) {
                // server returned HTTP code other than 200 or 503
            } catch (\Exception $e) {
                // message could not be sent
            }
        }

        return $amountSent . ' devices notified';
    }

    public function registerDevice(Request $request)
    {
        // Get the deviceID from the request
        $deviceID = $request->input('deviceID');

        Participant::firstOrCreate(['device_id' => $deviceID]);
    }
}
