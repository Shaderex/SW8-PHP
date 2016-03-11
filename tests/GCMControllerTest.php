<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GCMControllerTest extends TestCase
{
    public function testNotifyAll()
    {
        $this->call('GET', 'gcm/notifyAll');
        $this->assertResponseOk();
    }

    public function testRegisterDevice()
    {
        $deviceID = "asduhAUHSusdhHU687687576DGHSGysgdayg";
        $this->call('POST', 'gcm/registerDevice', ['deviceID' => $deviceID]);
        $this->assertResponseOk();

    }
}
