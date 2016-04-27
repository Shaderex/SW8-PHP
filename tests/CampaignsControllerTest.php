<?php

use DataCollection\Campaign;
use DataCollection\Participant;
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
        'sensors[]',
        'is_private',
        'snapshot_length',
        'sample_duration',
        'sample_frequency',
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

        $this->user = User::create(['name' => 'børge', 'email' => 'børge@børgespølser.dk', 'password' => bcrypt('børge')]);
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
            'is_private' => true,
            'snapshot_length' => -2,
            'sample_duration' => 0,
            'sample_frequency' => 0,
            'measurement_frequency' => PHP_INT_MAX,
        ];

        $campaign = new Campaign();
        $campaign->fill($input);

        $response = $this->call('POST', '/campaigns', $input);

        $this->assertSessionHasErrors([
            'name',
            'snapshot_length',
            'sample_duration',
            'sample_frequency',
            'campaign_length'
        ]);
    }

    public function testFormValidationNumbersGreaterThan()
    {
        $input = [
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => true,
            'snapshot_length' => 100,
            'sample_frequency' => 101,
            'sample_duration' => 102,
            'measurement_frequency' => PHP_INT_MAX,
        ];

        $campaign = new Campaign();
        $campaign->fill($input);

        $this->call('POST', '/campaigns', $input);

        $this->assertSessionHasErrors([
            'sample_duration',
            'sample_frequency',
            'measurement_frequency'
        ]);
    }

    public function testShowAction()
    {
        $campaign = Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => true,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);

        $this->visit("/campaigns/{$campaign->id}")->assertResponseOk();
    }

    public function testGetAllCampaignsRequest()
    {
        Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => false,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);

        $this->get('/campaigns')
            ->seeJsonContains([
                'id' => 1,
                'name' => 'asdasd'
            ]);
    }

    public function testGetCampaignGetSpecification()
    {
        $createCampaignData = [
            'name' => 'FourtyTwo',
            'description' => 'I intend to find the answer to the universe and everything',
            'is_private' => true,
            'campaign_length' => 1,
            'snapshot_length' => 10,
            'sample_duration' => 10,
            'sample_frequency' => 10,
            'measurement_frequency' => 10,
            'sensors' => [
                'Gyroscope',
                'Accelerometer'
            ]
        ];

        $this->call('POST', '/campaigns', $createCampaignData);

        $campaign = Campaign::whereName('FourtyTwo')->first();

        $questions = [
            ['question' => 'What is the answer to the universe?', 'order' => 0],
            ['question' => 'What is the answer to everything?', 'order' => 1]
        ];

        foreach ($questions as $question) {
            $this->call('POST', '/campaigns/' . $campaign->id . '/questions', $question);
        }

        $expected = [
            'name' => 'FourtyTwo',
            'user' => [
                'name' => 'børge'
            ],
            'description' => 'I intend to find the answer to the universe and everything',
            'is_private' => true,
            'campaign_length' => 1,
            'snapshot_length' => 10,
            'sample_duration' => 10,
            'sample_frequency' => 10,
            'measurement_frequency' => 10,
            'sensors' => [
                ['name' => 'Gyroscope', 'type' => 5],
                ['name' => 'Accelerometer', 'type' => 0]
            ],
            'questions' => [
                ['question' => 'What is the answer to the universe?', 'order' => 0, 'id' => 3],
                ['question' => 'What is the answer to everything?', 'order' => 1, 'id' => 4]
            ],
            'questionnaire_placement' => 0
        ];

        $this->json('GET', '/campaigns/' . $campaign->id, [], ['X-Requested-With' => 'XMLHttpRequest']);
        $this->seeJson($expected);
    }

    // Test adding snapshots to campaigns
    public function testAddSnapshotsValidRequest()
    {

        $expectedSize = 3;
        $campaign = Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => false,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);

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

        $this->call('POST', '/campaigns/' . $campaign->id . '/snapshots', $request);

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
        $campaign = Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => false,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);

        $badRequest = ['snapshots' => 'this is not a json string', 'device_id' => $this->participant->device_id];

        $this->call('POST', '/campaigns/' . $campaign->id . '/snapshots', $badRequest);

        $this->assertResponseStatus(400);
    }

    public function testAddSnapshotsNoJsonRequest()
    {
        $campaign = Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => false,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);

        $request = ['device_id' => $this->participant->device_id];

        $this->call('POST', '/campaigns/' . $campaign->id . '/snapshots/', $request);
        $this->assertResponseStatus(400);
    }

    public function testAddSnapshotsNotExistingCampaign()
    {
        $this->call('POST', '/campaigns/42/snapshots/');
        $this->assertResponseStatus(404);
    }

    public function testAddSnapshotsRequestWithJsonNoSnapshots()
    {
        $expectedSize = 0;
        $campaign = Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => false,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);

        $input = '{"snapshots":[]}';

        $request = ['snapshots' => $input, 'device_id' => $this->participant->device_id];

        $this->call('POST', '/campaigns/' . $campaign->id . '/snapshots', $request);

        $campaign = Campaign::find($campaign->id);

        $actualSize = count($campaign->snapshots);

        $this->assertEquals($expectedSize, $actualSize, "The amount of snapshots do not correspond");

        $this->assertResponseOk();
    }

    public function testAddSnapshotsNoDeviceIDRequest()
    {
        $campaign = Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => false,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);

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

        $this->call('POST', '/campaigns/' . $campaign->id . '/snapshots/', $request);
        $this->assertResponseStatus(404);
    }

    public function testUserIsAttachedOnCreation()
    {
        $createCampaignData = [
            'name' => 'FourtyTwo',
            'description' => 'I intend to find the answer to the universe and everything',
            'is_private' => true,
            'campaign_length' => 1,
            'snapshot_length' => 10,
            'sample_duration' => 10,
            'sample_frequency' => 10,
            'measurement_frequency' => 10,
            'sensors' => [
                'Gyroscope',
                'Accelerometer'
            ]
        ];

        $this->call('POST', '/campaigns', $createCampaignData);

        $this->assertRedirectedTo('/');

        $campaign = Campaign::whereName('FourtyTwo')->first();

        $this->assertNotNull($campaign->user, "The attached user is null");
        $this->assertEquals($campaign->user->attributes, $this->user->attributes, "The user attached to the campaign is not the expected one");
    }
}
