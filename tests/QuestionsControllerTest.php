<?php

use DataCollection\Campaign;
use DataCollection\Question;
use DataCollection\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class QuestionsControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Campaign
     */
    protected $campaign;

    /**
     * @var User
     */
    protected $user;

    private $questions = [
        'How are you?',
        'How are you doing?',
        'What are you doing?',
        'What did you do?'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->app = $this->createApplication();
        $this->runDatabaseMigrations();
        $this->artisan('db:seed');

        $this->campaign = factory(Campaign::class)->create();

        $this->user = User::first();
        $this->actingAs($this->user);
    }

    public function testCreateAction()
    {
        $this->visit("/campaigns/{$this->campaign->id}/questions/create")
            ->assertResponseOk();
    }

    public function testStoreAction()
    {
        $this->call(
            'POST',
            "/campaigns/{$this->campaign->id}/questions",
            [
                'question' => 'How are you?',
            ]
        );

        $this->assertRedirectedTo("/campaigns/{$this->campaign->id}");
    }

    public function testStoreActionValidation()
    {
        $this->call(
            'POST',
            "/campaigns/{$this->campaign->id}/questions",
            [
                'question' => '',
            ]
        );

        $this->assertSessionHasErrors('question');
    }

    public function testStoreActionActuallyStores()
    {
        $this->call(
            'POST',
            "/campaigns/{$this->campaign->id}/questions",
            [
                'question' => 'How are you?',
            ]
        );

        $this->assertRedirectedTo("/campaigns/{$this->campaign->id}");


        $this->assertNotNull($this->campaign->questions);
    }
}
