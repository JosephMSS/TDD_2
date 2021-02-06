<?php

namespace Tests\Unit\Models;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class RepositoryTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_belongs_to()
    {
        $repository=Repository::factory()->create();
        // dd($repository->user());
        $this->assertInstanceOf(User::class,$repository->user);//Debemos importar tambien el modelo usuario

    }
}
