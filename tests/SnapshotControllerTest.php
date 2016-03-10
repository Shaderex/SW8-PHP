<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SnapshotControllerTest extends TestCase
{
    public function testIndex()
    {
        $this->call('GET', 'snapshot');
        $this->assertResponseOk();
    }

    public function testCreate()
    {
        $this->call('GET', 'snapshot/create');
        $this->assertResponseOk();
    }

    public function testStore()
    {
        $this->call('POST', 'snapshot');
        $this->assertResponseOk();
    }

    public function testShow()
    {
        $this->call('GET', 'snapshot/1');
        $this->assertResponseOk();
    }

    public function testEdit()
    {
        $this->call('GET', 'snapshot/1/edit');
        $this->assertResponseOk();
    }

    public function testUpdatePut()
    {
        $this->call('PUT', 'snapshot/1');
        $this->assertResponseOk();
    }

    public function testUpdatePatch()
    {
        $this->call('PATCH', 'snapshot/1');
        $this->assertResponseOk();
    }

    public function testDestroy()
    {
        $this->call('DELETE', 'snapshot/1');
        $this->assertResponseOk();
    }
}
