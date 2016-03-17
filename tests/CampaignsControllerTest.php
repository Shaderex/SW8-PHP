<?php

use DataCollection\Campaign;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\URL;

class CampaignsControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $mock;
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

    /**
     * A basic test example.
     *
     * @return void
     */
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

    public function testStoreActionNoPrivateBool(){
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

    public function testFormValidation()
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
            'measurement_frequency'
        ]);
    }
}
