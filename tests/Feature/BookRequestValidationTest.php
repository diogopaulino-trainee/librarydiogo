<?php

use App\Models\User;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\post;

test('request cannot be created without a valid book', function () {
    // Passo 0: Explicação geral do que o teste vai fazer
    info('--- Test starting: This test ensures that a request cannot be created without a valid book. ---');
    info('--- It will attempt to create a request using an invalid book ID. ---');
    info('--- It will verify if Laravel returns a proper validation error when the book is not found. ---');
    info('--- It will ensure that the invalid request does not get saved in the database. ---');
    info('--- End of Test Explanation ---');
    info('');

    // Passo 1: Criar um usuário para simular a requisição
    $user = User::factory()->create();
    info('--- Step 1: User created with ID: ' . $user->id . ' ---');  // Exibe no console o ID do usuário criado
    info('');

    // Passo 2: Logando o usuário para simular a autenticação
    $this->actingAs($user);
    info('--- Step 2: User logged in with ID: ' . $user->id . ' ---');  // Confirma que o usuário foi logado
    info('');

    // Passo 3: Tentando criar uma requisição com um ID de livro inválido (ID 999)
    info('--- Step 3: Attempting to create a request with an invalid book_id (999) ---');
    info('');
    $response = post("/requests/999", [
        'book_id' => 999,  // ID inválido do livro
        'user_id' => $user->id,
        'request_date' => now(),
        'expected_return_date' => now()->addDays(5),
        'status' => 'borrowed',
    ]);
    info('--- Step 4: Response status: ' . $response->status() . ' ---');  // Exibe o status da resposta
    info('');

    // Passo 4: Verificando se o erro de validação foi retornado (status 404)
    info('--- Step 5: Verifying if error 404 is returned, indicating that the book_id is invalid ---');
    info('');
    $response->assertStatus(404);  // Verifica se o status é 404 (erro de livro não encontrado)
    info('--- Step 6: Validation error received for invalid book_id ---');
    info('');

    // Passo 5: Verificando se a requisição não foi salva no banco de dados, como esperado
    info('--- Step 7: Verifying if the request was not saved in the database due to invalid book_id ---');
    info('');
    assertDatabaseMissing('requests', [
        'book_id' => 999,
        'user_id' => $user->id,
        'status' => 'borrowed',
    ]);
    info('--- Step 8: Request not saved in database due to invalid book_id ---');
    info('');
});
