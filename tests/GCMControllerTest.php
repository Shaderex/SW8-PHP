<?php

use DataCollection\Participant;

class GCMControllerTest extends TestCase
{
    public function testNotifyAll()
    {
        $this->call('GET', 'gcm/notifyAll');
        $this->assertResponseOk();

        $this->call('GET', 'gcm/notifyAll/Testnotification');
        $this->assertResponseOk();
    }

    public function testRegisterDevice()
    {
        $deviceID = "deviceTestID";

        $this->call('POST', 'gcm/registerDevice', ['deviceID' => $deviceID]);
        $this->assertResponseOk();

        $count = Participant::where('deviceID', '=', $deviceID)->count();

        $expectedCount = 1;

        $this->assertEquals($expectedCount, $count);

        $this->call('POST', 'gcm/registerDevice', ['deviceID' => $deviceID]);

        $count = Participant::where('deviceID', '=', $deviceID)->count();

        $this->assertEquals($expectedCount, $count);

        Participant::where('deviceID', '=', $deviceID)->delete();
    }
}
