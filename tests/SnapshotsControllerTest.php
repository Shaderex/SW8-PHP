<?php

use DataCollection\Campaign;
use DataCollection\Participant;
use DataCollection\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SnapshotsControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected $campaign;
    protected $user;
    protected $participant;

    public function setUp()
    {
        parent::setUp();
        $this->app = $this->createApplication();
        $this->runDatabaseMigrations();
        $this->artisan('db:seed');

        $this->campaign = Campaign::whereName('60SECOND_TEST_CAMP')->first();
        $this->participant = Participant::create(['device_id' => 'DEVICE_ID_STRING']);
        $this->user = User::first();
        $this->actingAs($this->user);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    private function sendSnapshots()
    {
        $expectedSize = 3;
        $snapshot_sensor_data_json = '{"accelerometerSamples": [{"measurements": ["2884548964675320317", "2884345555209779987"]}, {"measurements": ["2884258693897091839", "2884647920598088372"]}]}';
        $input = '{"snapshots":[';
        for ($i = 0; $i < $expectedSize; $i++) {
            if ($i != 0) {
                $input .= ',';
            }
            $input .= $snapshot_sensor_data_json;
        }
        $input .= ']}';

        $request = ['snapshots' => $input, 'device_id' => $this->participant->device_id];
        $this->call('POST', 'api/campaigns/' . $this->campaign->id . '/snapshots', $request);
        $this->assertResponseOk();
    }

    public function testSnapshotsViewExists()
    {
        $this->sendSnapshots();
        $this->visit('/campaigns/' . $this->campaign->id . '/snapshots');
        $this->assertResponseOk();
    }
}
