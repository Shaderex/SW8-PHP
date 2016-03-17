<?php

use DataCollection\Campaign;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuestionsControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Campaign
     */
    protected $campaign;


    public function setUp()
    {
        parent::setUp();
        $this->app = $this->createApplication();
        $this->runDatabaseMigrations();

        $this->campaign = Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => true,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);
    }

    public function testCreateAction()
    {
        $this->visit("/campaigns/{$this->campaign->id}/add-question")
            ->assertResponseOk();
    }

    public function testStoreAction()
    {
        $this->call(
            'POST',
            "/campaigns/{$this->campaign->id}/add-question",
            [
                'question' => 'How are you?',
            ]
        );

        $this->assertRedirectedTo("/campaigns/{$this->campaign->id}");
    }

    public function testStoreActionActuallyStores()
    {
        $this->call(
            'POST',
            "/campaigns/{$this->campaign->id}/add-question",
            [
                'question' => 'How are you?',
            ]
        );

        $this->assertRedirectedTo("/campaigns/{$this->campaign->id}");


        $this->assertNotNull($this->campaign->questions);
    }
}
