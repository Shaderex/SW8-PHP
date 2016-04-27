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

        $this->campaign = Campaign::create([
            'name' => 'asdasd',
            'description' => 'sadasdasd',
            'is_private' => true,
            'snapshot_length' => 100,
            'sample_duration' => 50,
            'sample_frequency' => 10,
            'measurement_frequency' => 5,
        ]);

        $this->user = User::create(['name' => 'børge', 'email' => 'børge@børgespølser.dk', 'password' => bcrypt('børge')]);
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

    public function testChangeOrderAction()
    {
        $questionObjs = [];

        foreach ($this->questions as $question) {
            $questionObj = new Question($question);
            $this->campaign->questions()->save($questionObj);

            $questionObjs[] = $questionObj;
        }

        $input = [
            'order' => [
                0 => $questionObjs[3]->id,
                1 => $questionObjs[2]->id,
                2 => $questionObjs[1]->id,
                3 => $questionObjs[0]->id,
            ]
        ];

        $this->call('POST', "/campaigns/{$this->campaign->id}", $input);

        $this->assertRedirectedTo("/campaigns/{$this->campaign->id}");

        $count = count($questionObjs);

        for ($i = 0; $i < $count; $i++) {
            $question = Question::find($questionObjs[$i]->id);

            $this->assertEquals($count - 1 - $i, $question->order);
        }
    }
}
