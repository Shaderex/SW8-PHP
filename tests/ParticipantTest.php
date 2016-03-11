<?php

use DataCollection\Participant;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipantTest extends TestCase
{
    public function testParticipant()
    {
        $participant = new Participant;
        $participant->id = 1;
        $participant->deviceID = "SOME_STRING";
    }
}
