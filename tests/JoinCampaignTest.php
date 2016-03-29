<?php

use DataCollection\Campaign;
use DataCollection\Participant;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JoinCampaignTest extends TestCase
{
    protected $campaign;
    protected $participant;
    use DatabaseMigrations;


    public function setUp()
    {
        parent::setUp();

        $this->campaign = Campaign::create([
            'name' => 'Jesper',
            'description' => 'dyld',
            'is_private' => true,
            'snapshot_length' => 10,
            'sample_duration' => 10,
            'sample_frequency' => 10,
            'measurement_frequency' => 10,
        ]);

        $this->participant = Participant::create([
            'device_id' => "someRandomString"
        ]);
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
        $this->post('/campaigns/join', [
            'device_id' => $this->participant->device_id,
            'campaign_id' => $this->campaign->id
        ])->seeJson()->assertResponseOk();

        $this->assertEquals($this->campaign->id, $this->participant->campaigns()->first()->id);
    }


}
