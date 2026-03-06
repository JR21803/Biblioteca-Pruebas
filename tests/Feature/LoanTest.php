<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_estudiante_can_request_book()
    {
        Role::create(['name' => 'estudiante']);

        $user = User::factory()->create();
        $user->assignRole('estudiante');

        $book = Book::factory()->create([
            'available_copies' => 1,
            'is_available' => true
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/loans', [
                'requester_name' => 'Juan',
                'book_id' => $book->id
            ]);

        $response->assertStatus(201);
    }

    public function test_bibliotecario_can_return_book()
    {
        Role::create(['name' => 'bibliotecario']);

        $user = User::factory()->create();
        $user->assignRole('bibliotecario');

        $book = Book::factory()->create([
            'available_copies' => 0,
            'is_available' => false
        ]);

        $loan = Loan::factory()->create([
            'book_id' => $book->id,
            'requester_name' => 'Pedro',
            'return_at' => null
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/loans/'.$loan->id.'/return');

        $response->assertStatus(200);
    }

    public function test_bibliotecario_can_view_loan_history()
{
    Role::create(['name' => 'bibliotecario']);

    $user = User::factory()->create();
    $user->assignRole('bibliotecario');

    $book = Book::factory()->create([
        'author' => 'Autor prueba'
    ]);

    Loan::factory()->create([
        'book_id' => $book->id,
        'requester_name' => 'Estudiante Test'
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/loans');

    $response->assertStatus(200);
}
}