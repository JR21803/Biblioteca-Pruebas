<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LibroTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_books()
    {
        Role::create(['name' => 'estudiante']);

        $user = User::factory()->create();
        $user->assignRole('estudiante');

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/books');

        $response->assertStatus(200);
    }

    public function test_bibliotecario_can_create_book()
    {
        Role::create(['name' => 'bibliotecario']);

        $user = User::factory()->create();
        $user->assignRole('bibliotecario');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/books', [
                'title' => 'Clean Code',
                'author' => 'Robert Martin',
                'description' => 'Libro sobre buenas prácticas',
                'ISBN' => '9780132350884',
                'total_copies' => 5,
                'available_copies' => 5,
                'is_available' => true
            ]);

        $response->assertStatus(200);
    }

        public function test_ebibliotecario_can_delete_book()
    {
        Role::create(['name' => 'bibliotecario']);

        $user = User::factory()->create();
        $user->assignRole('bibliotecario');

        $book = Book::factory()->create([
            'author' => 'Autor prueba'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/v1/books/' . $book->id);

        $response->assertStatus(200);
    }

    public function test_estudiante_cannot_delete_book()
    {
        Role::create(['name' => 'estudiante']);

        $user = User::factory()->create();
        $user->assignRole('estudiante');

        $book = Book::factory()->create([
            'author' => 'Autor prueba'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/v1/books/' . $book->id);

        $response->assertStatus(403);
    }

    public function test_can_view_book_detail()
    {
        Role::create(['name' => 'estudiante']);

        $user = User::factory()->create();
        $user->assignRole('estudiante');

        $book = Book::factory()->create([
            'author' => 'Autor prueba'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/books/' . $book->id);

        $response->assertStatus(200);
    }

    public function test_bibliotecario_can_update_book()
    {
        Role::create(['name' => 'bibliotecario']);

        $user = User::factory()->create();
        $user->assignRole('bibliotecario');

        $book = Book::factory()->create([
            'author' => 'Autor original'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/api/v1/books/' . $book->id, [
                'title' => 'Clean Code Updated',
                'author' => 'Robert Martin',
                'description' => 'Libro actualizado',
                'ISBN' => '9780132350884',
                'total_copies' => 10,
                'available_copies' => 10,
                'is_available' => true
            ]);

        $response->assertStatus(200);
    }
}