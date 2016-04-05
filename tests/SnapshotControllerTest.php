<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SnapshotControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->app = $this->createApplication();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testIndex()
    {
        $this->call('GET', 'snapshots');
        $this->assertResponseOk();
    }

    public function testCreate()
    {
        $this->call('GET', 'snapshots/create');
        $this->assertResponseOk();
    }

    public function testStore()
    {
        $this->call('POST', 'snapshots');
        $this->assertResponseOk();
    }

    public function testShow()
    {
        $this->call('GET', 'snapshots/1');
        $this->assertResponseOk();
    }

    public function testEdit()
    {
        $this->call('GET', 'snapshots/1/edit');
        $this->assertResponseOk();
    }

    public function testUpdatePut()
    {
        $this->call('PUT', 'snapshots/1');
        $this->assertResponseOk();
    }

    public function testUpdatePatch()
    {
        $this->call('PATCH', 'snapshots/1');
        $this->assertResponseOk();
    }

    public function testDestroy()
    {
        $this->call('DELETE', 'snapshots/1');
        $this->assertResponseOk();
    }

    public function testStoreWithJson()
    {
        $input = ['json' => '{"accelerometerSamples":[{"measurements":["2884548964675320317","2884345555209779987"]},{"measurements":["2884258693897091839","2884647920598088372"]},{"measurements":["2884904656335632834","2884806250357422747"]},{"measurements":["2885048143354887059","2886253758129474165"]},{"measurements":["2884841434547059788","2884366445789152487"]},{"measurements":["2884999764439563456","2884458804508982016"]},{"measurements":["2884823842733259377","2883402174265653192"]},{"measurements":["2884894211157621547","2884439013876398741"]},{"measurements":["2884433516112739127","2884583049420438587"]},{"measurements":["2884485192817408426","2884897510015465429"]}]}'];
        $this->call('POST', '/snapshots', $input);
    }
}
