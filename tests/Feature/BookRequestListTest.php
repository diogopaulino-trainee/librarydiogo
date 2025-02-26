<?php

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Request;
use App\Models\User;
use App\Models\Author;
use function Pest\Laravel\get;

test('user can see only their own requests', function () {
    // Passo 0: Explicação geral do que o teste vai fazer
    info('--- Test starting: This test will ensure that a user can only see their own requests.');
    info('--- It will create two users, each with their own requests. ---');
    info('--- It will simulate a GET request to check if the user can only see their own requests. ---');
    info('--- It will verify if the other user’s requests are not visible to the authenticated user. ---');
    info('--- End of Test Explanation ---');
    info('');

    // Passo 1: Criar dois usuários diferentes
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    info('--- Step 1: Users created with IDs: ' . $user1->id . ' and ' . $user2->id);  // Exibe os IDs dos usuários criados
    info('');

    // Passo 2: Atribuir o papel de "Citizen" aos usuários
    $user1->assignRole('Citizen');
    $user2->assignRole('Citizen');
    info('--- Step 2: Users assigned roles: ' . $user1->roles->pluck('name')->implode(', ') . ' and ' . $user2->roles->pluck('name')->implode(', '));  // Exibe os papéis dos usuários
    info('');

    // Passo 3: Criar um publisher e livros
    $publisher = Publisher::factory()->create();
    info('--- Step 3: Publisher created with ID: ' . $publisher->id);  // Exibe o ID do publisher criado
    info('');

    // Passo 4: Criar um autor para associar ao livro
    $author = Author::factory()->create();
    info('--- Step 4: Author created with ID: ' . $author->id);  // Exibe o ID do autor criado
    info('');

    // Passo 5: Criar livros para os usuários e associar ao autor
    $book1 = Book::factory()->create(['status' => 'available', 'publisher_id' => $publisher->id, 'user_id' => $user1->id]);
    $book2 = Book::factory()->create(['status' => 'available', 'publisher_id' => $publisher->id, 'user_id' => $user2->id]);
    $book1->authors()->attach($author);  // Associar o autor ao livro
    $book2->authors()->attach($author);  // Associar o autor ao livro
    info('--- Step 5: Books created with IDs: ' . $book1->id . ' and ' . $book2->id);  // Exibe os IDs dos livros criados
    info('--- Step 5: Author associated with both Book 1 and Book 2');
    info('');

    // Passo 6: Criar requisições para ambos os usuários
    $request1 = Request::create([
        'book_id' => $book1->id,
        'user_id' => $user1->id,
        'request_date' => now(),
        'expected_return_date' => now()->addDays(5),
        'status' => 'borrowed',
        'user_name_at_request' => $user1->name,
        'user_email_at_request' => $user1->email,
        'request_number' => Request::max('request_number') + 1,
    ]);
    $request2 = Request::create([
        'book_id' => $book2->id,
        'user_id' => $user2->id,
        'request_date' => now(),
        'expected_return_date' => now()->addDays(5),
        'status' => 'borrowed',
        'user_name_at_request' => $user2->name,
        'user_email_at_request' => $user2->email,
        'request_number' => Request::max('request_number') + 1,
    ]);
    info('--- Step 6: Requests created with IDs: ' . $request1->id . ' and ' . $request2->id);  // Exibe os IDs das requisições criadas
    info('');

    // Passo 7: Logar o usuário 1
    $this->actingAs($user1);
    info('--- Step 7: User 1 logged in with ID: ' . $user1->id);  // Confirma que o usuário 1 foi logado
    info('');

    // Passo 8: Verificar se o usuário está autenticado corretamente
    $this->assertAuthenticatedAs($user1);
    info('--- Step 8: User 1 authentication confirmed');  // Confirma que a autenticação foi bem-sucedida
    info('');

    // Passo 9: Simular uma requisição GET para obter as requisições do usuário 1
    info('--- Step 9: Sending GET request to /requests');
    $response = get('/requests');
    info('');

    // Passo 10: Verificar se a requisição do usuário 1 está na resposta
    $response->assertStatus(200);
    info('--- Step 10: Verifying if Request 1 is present in the response');
    $response->assertSee($request1->id);  // Verifica se a requisição do usuário 1 está presente
    $response->assertSee($request1->book->title);  // Verifica se o título do livro do usuário 1 está presente
    $response->assertSee($request1->user_name_at_request);  // Verifica se o nome do usuário 1 está presente
    info('');

    // Passo 11: Verificar se a requisição do usuário 2 NÃO está na resposta
    info('--- Step 11: Verifying if Request 2 is NOT present in the response');
    $response->assertDontSee($request2->book->title);  // Verifica se o título do livro do usuário 2 NÃO está presente
    $response->assertDontSee($request2->user_name_at_request);  // Verifica se o nome do usuário 2 NÃO está presente
    info('');
});
