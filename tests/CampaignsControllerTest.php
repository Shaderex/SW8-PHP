<?php

use DataCollection\Campaign;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\URL;

class CampaignsControllerTest extends TestCase
{
    use DatabaseMigrations;
    
    private $expectedFields = [
        'name',
        'description',
        'sensors[]',
        'is_private',
        'snapshot_length',
        'sample_duration',
        'sample_frequency',
        'measurement_frequency',
    ];

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
            'sample_frequency'
        ]);
    }

    public function testFormValidationNumbersGreaterThan()
    {
        $input = [
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => true,
            'snapshot_length' => 100,
            'sample_duration' => 101,
            'sample_frequency' => 102,
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
        $campaign = Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => false,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);

        $this->get('/campaigns')
            ->seeJson([
                'id' => 1,
                'name' => 'asdasd'
            ]);
    }

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
        for($i = 0; $i < $expectedSize; $i ++) {
            if($i != 0) {
                $input .= ',';
            }
            $input .= $snapshot_sensor_data_json;
        }
        $input .= ']}';


        $request = ['snapshots' => $input];

        $this->call('POST', '/campaigns/'.  $campaign->id .'/snapshots', $request);

        $campaign = Campaign::find($campaign->id);

        $actualSize = count($campaign->snapshots);

        $this->assertEquals($expectedSize,$actualSize, "The amount of snapshots do not correspond");
        $this->assertResponseOk();
    }

    public function testAddSnapshotsInvalidJsonRequest()
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

        $badRequest = ['snapshots' => 'this is not a json string'];

        $this->call('POST', '/campaigns/'.  $campaign->id .'/snapshots', $badRequest);

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

        $this->call('POST', '/campaigns/'. $campaign->id .'/snapshots/');
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

        $request = ['snapshots' => $input];

        $this->call('POST', '/campaigns/'.  $campaign->id .'/snapshots', $request);

        $campaign = Campaign::find($campaign->id);

        $actualSize = count($campaign->snapshots);

        $this->assertEquals($expectedSize,$actualSize, "The amount of snapshots do not correspond");

        $this->assertResponseOk();
    }


}
