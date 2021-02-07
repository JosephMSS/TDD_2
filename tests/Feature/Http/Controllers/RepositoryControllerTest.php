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
    /**listado personal */
    public function test_index_with_data()
    {
        $user = User::factory()->create(); // id = 1
        $repository = Repository::factory()->create(['user_id' => $user->id]); // user_id = 1

        $this
            ->actingAs($user)
            ->get('repositories')
            ->assertStatus(200)
            ->assertSee($repository->id)
            ->assertSee($repository->url);
    }
    public function test_index_empty()
    {
        Repository::factory()->create();
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get('repositories')
            ->assertStatus(200)
            ->assertSee('No hay repositorios creados');
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
    public function test_update()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);
        $newData = Repository::factory()->make();

        $data = [
            'url' => $newData->url,
            'description' => $newData->description
        ];
        $this->actingAs($user) //emplea el usuario para la autenticacion.
            ->put("repositories/$repository->id", $data)
            ->assertRedirect("repositories/$repository->id/edit");

        $this->assertDatabaseHas('repositories', $data);
    }
    public function test_validate_store()
    {
        $user = User::factory()->create();

        $this->actingAs($user) //emplea el usuario para la autenticacion.
            ->post('repositories', [])
            ->assertStatus(302) //valido una redireccion a la misma pagina
            ->assertSessionHasErrors(['url', 'description']); //que existan errores en los valores requeridos 

    }
    public function test_validate_update()
    {
        $repository = Repository::factory()->create();

        $user = User::factory()->create();

        $this->actingAs($user) //emplea el usuario para la autenticacion.
            ->put("repositories/$repository->id", [])
            ->assertStatus(302) //valido una redireccion a la misma pagina
            ->assertSessionHasErrors(['url', 'description']); //que existan errores en los valores requeridos 
    }
    public function test_destroy()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this
            ->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertRedirect('repositories');

        $this->assertDatabaseMissing('repositories', [
            'id' => $repository->id,
            'url' => $repository->url,
            'description' => $repository->description,
        ]);
    }
    public function test_destroy_policy()
    {
        $user = User::factory()->create(); // id = 1 
        $repository = Repository::factory()->create(); // user_id = 2

        $this
            ->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertStatus(403);
    }

    /**
     * Politicas de acceso
     */

    /**
     * Actualizamos un repositorio que no pertenece al usuario
     */
    public function test_update_policy()
    {

        $user = User::factory()->create(); //user id 1

        $repository = Repository::factory()->create(); //repository 1 user id:2
        $newData = Repository::factory()->make();

        $data = [
            'url' => $newData->url,
            'description' => $newData->description
        ];


        $this->actingAs($user) //emplea el usuario para la autenticacion.
            ->put("repositories/$repository->id", $data)
            ->assertStatus(403); //Nosostros no podemos realizar la accion por que el servidor lo detiene


    }
    public function test_show()
    {
    $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this
            ->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(200);

    }
    public function test_show_policy()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();
      


        $this->actingAs($user) //emplea el usuario para la autenticacion.
            ->get("repositories/$repository->id")
            ->assertStatus(403);

    }
}
