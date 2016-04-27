<?php

use DataCollection\Campaign;
use DataCollection\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JoinCampaignTest extends TestCase
{
    protected $campaign;
    protected $participant;
    use DatabaseMigrations;


    public function setUp()
    {
        parent::setUp();
        $this->app = $this->createApplication();
        $this->runDatabaseMigrations();
        $this->artisan('db:seed');

        $this->campaign = Campaign::whereName('60SECOND_TEST_CAMP')->first();
        $this->participant = Participant::create(['device_id' => 'DEVICE_ID_STRING']);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testIfParticipantCanJoinCampaign()
    {
        $this->campaign->participants()->attach($this->participant->id);

        $this->assertEquals($this->participant->device_id, $this->campaign->participants()->first()->device_id);
    }

    public function testIfCampaignCanAttachParticipants()
    {
        $this->participant->campaigns()->attach($this->campaign->id);

        $this->assertEquals($this->campaign->id, $this->participant->campaigns()->first()->id);
    }

    public function testPostJoinRequest()
    {
        $expected = [
            'name' => '60SECOND_TEST_CAMP',
            'user' => [ 'name' => \DataCollection\User::first()->name ],
            'description' => '60SECOND_TEST_CAMP',
            'is_private' => false,
            'snapshot_length' => 1,
            'sample_duration' => 1,
            'sample_frequency' => 1,
            'measurement_frequency' => 1,
            'sensors' => [
                ['name' => 'Accelerometer', 'type' => 0],
                ['name' => 'Ambient Light', 'type' => 1],
                ['name' => 'Barometer', 'type' => 2],
                ['name' => 'Cellular', 'type' => 3],
                ['name' => 'Compass', 'type' => 4],
                ['name' => 'Gyroscope', 'type' => 5],
                ['name' => 'Location', 'type' => 6],
                ['name' => 'Proximity', 'type' => 7],
                ['name' => 'Wifi', 'type' => 8]
            ],
            'questions' => [
                ['question' => 'Er du god?', 'id' => 1, 'order' => null],
                ['question' => 'Er du dårlig?', 'id' => 2, 'order' => null],
            ]
        ];

        $this->post('/campaigns/join', [
            'device_id' => $this->participant->device_id,
            'campaign_id' => $this->campaign->id
        ])->seeJson($expected)->assertResponseOk();

        $this->assertEquals($this->campaign->id, $this->participant->campaigns()->first()->id);
    }


}
