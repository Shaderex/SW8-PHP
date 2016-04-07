<?php

use DataCollection\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GCMControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->app = $this->createApplication();
        $this->runDatabaseMigrations();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testNotifyAll()
    {
        $this->call('GET', 'gcm/notifyAll');
        $this->assertResponseOk();

        $this->call('GET', 'gcm/notifyAll/Testnotification');
        $this->assertResponseOk();
    }

    public function testRegisterDevice()
    {
        $deviceID = "A_TESTING_DEVICE_ID";

        $this->call('POST', 'gcm/registerDevice', ['device_id' => $deviceID]);
        $this->assertResponseOk();

        $count = Participant::whereDeviceId($deviceID)->count();

        $expectedCount = 1;

        $this->assertEquals($expectedCount, $count);

        $this->call('POST', 'gcm/registerDevice', ['device_id' => $deviceID]);

        $count = Participant::whereDeviceId($deviceID)->count();

        $this->assertEquals($expectedCount, $count);
    }
}
