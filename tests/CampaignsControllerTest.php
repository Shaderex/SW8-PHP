<?php

use DataCollection\Campaign;
use DataCollection\Participant;
use DataCollection\Question;
use DataCollection\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\URL;

class CampaignsControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    private $expectedFields = [
        'name',
        'description',
        'is_public',
        'measurements_per_sample',
        'samples_per_snapshot',
        'sample_delay',
        'measurement_frequency',
        'campaign_length',
        'questionnaire_placement'
    ];
    protected $participant;

    public function setUp()
    {
        parent::setUp();
        $this->app = $this->createApplication();
        $this->runDatabaseMigrations();
        $this->artisan('db:seed');
        $this->participant = Participant::create(['device_id' => 'someRandomString']);

        $this->user = User::first();
        $this->actingAs($this->user);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testCreationFormIsShown()
    {
        $this->visit('/campaigns/create')->see('form action="' . URL::to('/campaigns') . '" method="post"');
    }

    public function testIfAllInputFieldsArePresent()
    {
        $page = $this->visit('/campaigns/create');

        foreach ($this->expectedFields as $expectedField) {
            $page->see("name=\"{$expectedField}\"");
        }
    }

    public function testStoreAction()
    {
        $input = [
            'name' => 'Jesper',
            'description' => 'dyld',
            'is_private' => true,
            'snapshot_length' => 10,
            'sample_duration' => 10,
            'sample_frequency' => 10,
            'measurement_frequency' => 10,
        ];

        $campaign = new Campaign();
        $campaign->fill($input);

        $response = $this->call('POST', '/campaigns', $input);

        $this->assertRedirectedTo('/');
    }

    public function testStoreActionNoPrivateBool()
    {
        $input = [
            'name' => 'Jesper',
            'description' => 'dyld',
            'snapshot_length' => 10,
            'sample_duration' => 10,
            'sample_frequency' => 10,
            'measurement_frequency' => 10,
        ];

        $campaign = new Campaign();
        $campaign->fill($input);

        $this->call('POST', '/campaigns', $input);

        $this->assertRedirectedTo('/');
    }

    public function testStoreActionWithSensors()
    {
        $input = [
            'name' => 'Jesper',
            'description' => 'dyld',
            'is_private' => true,
            'snapshot_length' => 10,
            'sample_duration' => 10,
            'sample_frequency' => 10,
            'measurement_frequency' => 10,
            'sensors' => [
                0 => 'Gyroscope'
            ]
        ];

        $campaign = new Campaign();
        $campaign->fill($input);

        $this->call('POST', '/campaigns', $input);

        $this->assertRedirectedTo('/');

        $campaign = Campaign::first();

        $this->assertNotNull($campaign->sensors);
    }

    public function testFormValidationManyWrong()
    {
        $input = [
            'name' => '',
            'description' => 'sadasdasd',
            'measurements_per_sample' => 0,
            'samples_per_snapshot' => 0,
            'sample_delay' => 0,
            'measurement_frequency' => 0,
        ];

        $campaign = new Campaign();
        $campaign->fill($input);

        $response = $this->call('POST', '/campaigns', $input);

        $this->assertSessionHasErrors([
            'name',
            'measurements_per_sample',
            'samples_per_snapshot',
            'sample_delay',
            'campaign_length'
        ]);
    }

    public function testShowAction()
    {
        $campaign = factory(Campaign::class)->create();
        $this->visit("/campaigns/{$campaign->id}")->assertResponseOk();
    }

    public function testGetAllCampaignsRequest()
    {
        $campaign = factory(Campaign::class)->create();

        $this->json('GET', '/api/campaigns')
            ->seeJsonContains([
                'id' => $campaign->id,
                'name' => $campaign->name,
                'user' => $this->user->name,
            ]);
    }

    public function testGetCampaignGetSpecification()
    {
        $createCampaignData = [
            'name' => 'FourtyTwo',
            'description' => 'I intend to find the answer to the universe and everything',
            'is_private' => true,
            'campaign_length' => 1,
            'measurements_per_sample' => 1,
            'samples_per_snapshot' => 1,
            'sample_delay' => 1,
            'measurement_frequency' => 1,
            'sensors' => [
                1 => "on"
            ]
        ];

        $this->call('POST', '/campaigns', $createCampaignData);

        $campaign = Campaign::whereName('FourtyTwo')->first();

        $questions = [
            new Question(['question' => 'What is the answer to the universe?', 'order' => 0]),
            new Question(['question' => 'What is the answer to everything?', 'order' => 1])
        ];

        foreach ($questions as $question) {
            $campaign->questions()->save($question);
        }

        $expected = [
            'name' => 'FourtyTwo',
            'user' => [
                'name' => $this->user->name
            ],
            'description' => 'I intend to find the answer to the universe and everything',
            'is_private' => true,
            'campaign_length' => 1,
            'snapshot_length' => 2,
            'sample_duration' => 1,
            'sample_frequency' => 2,
            'measurement_frequency' => 1,
            'sensors' => [
                ['name' => 'Accelerometer', 'type' => 0]
            ],
            'questions' => [
                ['question' => 'What is the answer to the universe?', 'order' => 0, 'id' => $questions[0]->id],
                ['question' => 'What is the answer to everything?', 'order' => 1, 'id' => $questions[1]->id]
            ],
            'questionnaire_placement' => 0
        ];

        $this->json('GET', 'api/campaigns/' . $campaign->id, [], ['X-Requested-With' => 'XMLHttpRequest']);
        $this->seeJson($expected);
    }

    // Test adding snapshots to campaigns
    public function testAddSnapshotsValidRequest()
    {

        $expectedSize = 3;
        $campaign = factory(Campaign::class)->create();

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

        $this->call('POST', 'api/campaigns/' . $campaign->id . '/snapshots', $request);

        $campaign = Campaign::find($campaign->id);

        $actualSize = 0;
        foreach ($campaign->snapshots as $snapshot) {
            $this->assertEquals($this->participant->id, $snapshot->participant_id, "The participant ids do not match");
            $actualSize++;
        }

        $this->assertEquals($expectedSize, $actualSize, "The amount of snapshots do not correspond");
        $this->assertResponseOk();
    }

    public function testAddSnapshotsInvalidJsonRequest()
    {
        $campaign = factory(Campaign::class)->create();

        $badRequest = ['snapshots' => 'this is not a json string', 'device_id' => $this->participant->device_id];

        $this->call('POST', 'api/campaigns/' . $campaign->id . '/snapshots', $badRequest);

        $this->assertResponseStatus(400);
    }

    public function testAddSnapshotsNoJsonRequest()
    {
        $campaign = factory(Campaign::class)->create();

        $request = ['device_id' => $this->participant->device_id];

        $this->call('POST', 'api/campaigns/' . $campaign->id . '/snapshots/', $request);
        $this->assertResponseStatus(400);
    }

    public function testAddSnapshotsNotExistingCampaign()
    {
        $this->call('POST', 'api/campaigns/42/snapshots/');
        $this->assertResponseStatus(404);
    }

    public function testAddSnapshotsRequestWithJsonNoSnapshots()
    {
        $expectedSize = 0;
        $campaign = factory(Campaign::class)->create();

        $input = '{"snapshots":[]}';

        $request = ['snapshots' => $input, 'device_id' => $this->participant->device_id];

        $this->call('POST', 'api/campaigns/' . $campaign->id . '/snapshots', $request);

        $campaign = Campaign::find($campaign->id);

        $actualSize = count($campaign->snapshots);

        $this->assertEquals($expectedSize, $actualSize, "The amount of snapshots do not correspond");

        $this->assertResponseOk();
    }

    public function testAddSnapshotsNoDeviceIDRequest()
    {
        $campaign = factory(Campaign::class)->create();

        $snapshot_sensor_data_json = '{"accelerometerSamples": [{"measurements": ["2884548964675320317", "2884345555209779987"]}, {"measurements": ["2884258693897091839", "2884647920598088372"]}]}';
        $input = '{"snapshots":[';
        for ($i = 0; $i < 4; $i++) {
            if ($i != 0) {
                $input .= ',';
            }
            $input .= $snapshot_sensor_data_json;
        }
        $input .= ']}';


        $request = ['snapshots' => $input];

        $this->call('POST', 'api/campaigns/' . $campaign->id . '/snapshots/', $request);
        $this->assertResponseStatus(404);
    }

    public function testUserIsAttachedOnCreation()
    {
        $createCampaignData = [
            'name' => 'FourtyTwo',
            'description' => 'I intend to find the answer to the universe and everything',
            'is_private' => true,
            'measurements_per_sample' => 5,
            'samples_per_snapshot' => 5,
            'sample_delay' => 100,
            'measurement_frequency' => 100,
            'campaign_length' => 10,
            'questionnaire_placement' => 0,
            'sensors' => [
                5 => "on",
                2 => "on"
            ]
        ];

        $this->call('POST', '/campaigns', $createCampaignData);

        $this->assertRedirectedTo('/campaigns');

        $campaign = Campaign::whereName('FourtyTwo')->first();

        $this->assertNotNull($campaign->user, "The attached user is null");
        $this->assertEquals($campaign->user->attributes, $this->user->attributes, "The user attached to the campaign is not the expected one");
    }

    public function testAuthUserCampaignsView()
    {
        $expectedNames = $this->user->campaigns()->get(['name']);

        foreach ($expectedNames as $name) {
            $this->visit('/campaigns')->see($name->name);
        }
    }
}
