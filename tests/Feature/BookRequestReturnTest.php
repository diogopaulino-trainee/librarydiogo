<?php

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Request;
use App\Models\User;
use App\Models\Author;
use Database\Seeders\RoleSeeder;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

// Rodar o seeder de papéis antes de cada teste
beforeEach(function () {
    // Assegura que os papéis "Admin" e "Citizen" estão na base de dados
    $this->seed(RoleSeeder::class);
});

test('admin can confirm return of a book for a citizen', function () {
    // Passo 0: Explicação geral do que o teste vai fazer
    info('--- Test starting: This test will ensure that an admin can confirm the return of a book for a citizen. ---');
    info('--- The admin will confirm the return of a book that is currently borrowed by a citizen. ---');
    info('--- It will verify if the request status is updated to "returned" and the book status to "available". ---');
    info('--- End of Test Explanation ---');
    info('');

    // Passo 1: Criar um usuário como "Citizen"
    $user = User::factory()->create();
    $user->assignRole('Citizen');
    info('--- Step 1: User created with ID: ' . $user->id . ' ---');  // Exibe no console o ID do usuário criado
    info('');

    // Passo 2: Criar um publisher para associar ao livro
    $publisher = Publisher::factory()->create();
    info('--- Step 2: Publisher created with ID: ' . $publisher->id . ' ---');  // Exibe no console o ID do publisher
    info('');

    // Passo 3: Criar um autor para associar ao livro
    $author = Author::factory()->create();
    info('--- Step 3: Author created with ID: ' . $author->id . ' ---');  // Exibe no console o ID do autor criado
    info('');

    // Passo 4: Criar um livro associado ao usuário "Citizen" e ao autor
    $book = Book::factory()->create([
        'status' => 'unavailable', // O livro já está emprestado
        'publisher_id' => $publisher->id,
        'user_id' => $user->id,  // Associando o livro ao usuário "Citizen"
    ]);
    $book->authors()->attach($author);  // Associar o autor ao livro
    info('--- Step 4: Book created with ID: ' . $book->id . ' ---');  // Exibe no console o ID do livro criado
    info('');

    // Passo 5: Criar uma requisição para o livro
    $request_number = Request::max('request_number') + 1;  // Calculando o próximo request_number
    $request = Request::create([
        'book_id' => $book->id,
        'user_id' => $user->id,
        'request_date' => now(),
        'expected_return_date' => now()->addDays(5),
        'status' => 'borrowed',
        'user_name_at_request' => $user->name,
        'user_email_at_request' => $user->email,
        'request_number' => $request_number,
    ]);
    info('--- Step 5: Request created with ID: ' . $request->id . ' ---');  // Exibe no console o ID da requisição criada
    info('');

    // Passo 6: Criar um admin para confirmar a devolução
    $admin = User::factory()->create();
    $admin->assignRole('Admin');
    info('--- Step 6: Admin user created with ID: ' . $admin->id . ' ---');  // Exibe no console o ID do admin
    info('');

    // Passo 7: Logar o admin para simular a confirmação da devolução
    $this->actingAs($admin);  // Simula o login do admin
    info('--- Step 7: Admin logged in with ID: ' . $admin->id . ' ---');  // Confirma que o admin foi logado
    info('');

    // Passo 8: Simular a confirmação da devolução do livro
    info('--- Step 8: Attempting to confirm the return of the book.');
    info('');
    $response = post("/requests/{$request->id}/confirm-return");

    info('--- Step 9: Response status: ' . $response->status() . ' ---');  // Exibe o status da resposta
    info('');

    // Passo 9: Verificar se o estado da requisição foi atualizado para 'returned'
    info('--- Step 10: Verifying if the request status is updated to "returned"');
    info('');
    $response->assertStatus(302);  // Espera-se um redirecionamento após a devolução
    info('--- Step 11: Redirect confirmed after return');
    info('');

    // Passo 10: Verificar se o estado da requisição foi atualizado para 'returned'
    assertDatabaseHas('requests', [
        'id' => $request->id,
        'status' => 'returned',
    ]);
    info('--- Step 12: Request status updated to "returned"');
    info('');

    // Passo 11: Verificar se o status do livro foi atualizado para 'available'
    $book->refresh();  // Atualiza o modelo do livro
    assertDatabaseHas('books', [
        'id' => $book->id,
        'status' => 'available',
    ]);
    info('--- Step 13: Book status updated to "available"');
    info('');
});
