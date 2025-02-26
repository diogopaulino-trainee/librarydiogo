<?php

use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use App\Models\Author;
use function Pest\Laravel\post;
use function Pest\Laravel\assertDatabaseMissing;

test('user cannot request a book with no stock available', function () {
    // Passo 0: Explicação geral do que o teste vai fazer
    info('--- Test starting: This test will ensure that a user cannot create a book request if the book is unavailable.');
    info('--- It will simulate the creation of a book request for a book with no stock (status "unavailable").');
    info('--- It will verify that the application prevents the request from being created and returns the correct error message.');
    info('--- End of Test Explanation ---');
    info('');

    // Passo 1: Criar um usuário para simular a requisição
    $user = User::factory()->create();
    info('--- Step 1: User created with ID: ' . $user->id);  // Exibe no console o ID do usuário criado
    info('');

    // Passo 2: Logar o usuário para simular a autenticação
    $this->actingAs($user);  
    info('--- Step 2: User logged in with ID: ' . $user->id);  // Confirma que o usuário foi logado
    info('');

    // Passo 3: Criar um publisher para associar ao livro
    $publisher = Publisher::factory()->create();
    info('--- Step 3: Publisher created with ID: ' . $publisher->id);  // Exibe no console o ID do publisher
    info('');

    // Passo 4: Criar um autor para associar ao livro
    $author = Author::factory()->create();
    info('--- Step 4: Author created with ID: ' . $author->id);  // Exibe no console o ID do autor criado
    info('');

    // Passo 5: Criar um livro associado ao usuário e ao autor, mas com status 'unavailable' (sem estoque disponível)
    $book = Book::factory()->create([
        'status' => 'unavailable',  // O livro está sem estoque disponível
        'publisher_id' => $publisher->id,
        'user_id' => $user->id,
    ]);
    info('--- Step 5: Book created with ID: ' . $book->id);  // Exibe no console o ID do livro criado
    info('');

    // Passo 6: Associar o autor ao livro
    $book->authors()->attach($author);  // Associa o autor ao livro usando a tabela pivô
    info('--- Step 6: Author associated with the book');  // Confirma que o autor foi associado ao livro
    info('');

    // Passo 7: Enviar uma requisição POST para tentar criar a requisição de livro
    info('--- Step 7: Sending a POST request to create a book request');
    info('');
    $response = post("/requests/{$book->id}", [
        'book_id' => $book->id,
        'user_id' => $user->id,
        'request_date' => now(),
        'expected_return_date' => now()->addDays(5),
        'status' => 'borrowed',
    ]);
    info('--- Step 8: Response status after attempt to create request: ' . $response->status());  // Exibe o status da resposta
    info('');

    // Passo 8: Verificar se a aplicação impediu a criação e retornou o erro adequado
    info('--- Step 9: Verifying that the book cannot be requested');
    info('');
    $response->assertStatus(422);  // Espera-se um erro de validação (Unprocessable Entity)
    info('--- Step 10: 422 Error confirmed due to unavailable book');
    info('');

    // Passo 9: Verificar se a requisição não foi criada no banco de dados
    info('--- Step 11: Verifying that no request was created in the database');
    info('');
    assertDatabaseMissing('requests', [  // Verifica se a tabela 'requests' não contém a requisição
        'book_id' => $book->id,
        'user_id' => $user->id,
    ]);
    info('--- Step 12: No request found in the database for unavailable book');
    info('');
});
