<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function test_guest()
    {
        $this->get('repositories')->assertRedirect('login'); //index
        $this->get('repositories/1')->assertRedirect('login'); //show
        $this->get('repositories/1/edit')->assertRedirect('login'); //edit
        $this->put('repositories/1')->assertRedirect('login'); //update
        $this->delete('repositories/1')->assertRedirect('login'); //destroy
        $this->get('repositories/create')->assertRedirect('login'); //create
        $this->post('repositories', [])->assertRedirect('login'); //store

    }
    public function test_store()
    {
        $repository = Repository::factory()->make();
        
        $data = [
            'url' => $repository->url,
            'description' => $repository->description
        ];
        
        $user = User::factory()->create();
        
        $this->actingAs($user) //emplea el usuario para la autenticacion.
            ->post('repositories', $data)
            ->assertRedirect('repositories');

        $this->assertDatabaseHas('repositories', $data);
    }
}
