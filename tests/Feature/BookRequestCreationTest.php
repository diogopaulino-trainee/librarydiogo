<?php

use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use App\Models\Author;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

// Teste para garantir que o usuário pode criar uma requisição de livro
test('user can create a book request', function () {
    // Explicação geral do que o teste vai fazer
    info('--- Test starting: This test will verify if a user can successfully create a book request. ---');
    info('--- It will create a user in the database. ---');
    info('--- It will create a book and associate it with the user. ---');
    info('--- It will simulate the submission of a book request. ---');
    info('--- It will ensure the request is created successfully and that the data is correct. ---');
    info('--- End of Test Explanation ---');
    info('');

    // Criar um usuário para simular a requisição
    $user = User::factory()->create();
    info('--- Step 1: User created with ID: ' . $user->id . ' ---');  // Exibe no console o ID do usuário criado
    info('');

    // Logar o usuário para simular a autenticação
    $this->actingAs($user);  
    info('--- Step 2: User logged in with ID: ' . $user->id . ' ---');  // Confirma que o usuário foi logado
    info('');

    // Criar um publisher para associar ao livro
    $publisher = Publisher::factory()->create();
    info('--- Step 3: Publisher created with ID: ' . $publisher->id . ' ---');  // Exibe no console o ID do publisher
    info('');

    // Criar um autor para associar ao livro
    $author = Author::factory()->create();
    info('--- Step 4: Author created with ID: ' . $author->id . ' ---');  // Exibe no console o ID do autor criado
    info('');

    // Criar um livro associado ao usuário e ao autor
    $book = Book::factory()->create([
        'status' => 'available',  // O livro está disponível
        'publisher_id' => $publisher->id,
        'user_id' => $user->id,  // Associando o livro ao usuário
    ]);
    info('--- Step 5: Book created with ID: ' . $book->id . ' ---');  // Exibe no console o ID do livro criado
    info('');

    // Associar o autor ao livro
    $book->authors()->attach($author);  // Associa o autor ao livro usando a tabela pivô
    info('--- Step 6: Author associated with the book ---');  // Confirma que o autor foi associado ao livro
    info('');

    // Enviar uma requisição POST para criar a requisição de livro
    info('--- Step 7: Sending a POST request to create a book request ---');
    info('');
    $response = post("/requests/{$book->id}", [
        'book_id' => $book->id,
        'user_id' => $user->id,
        'request_date' => now(),
        'expected_return_date' => now()->addDays(5),
        'status' => 'borrowed',
    ]);
    info('--- Step 8: Response status after request creation: ' . $response->status() . ' ---');  // Exibe o status da resposta
    info('');

    // Verificar se a requisição foi criada com sucesso e houve redirecionamento
    info('--- Step 9: Verifying if the user is redirected after successful request creation ---');
    info('');
    $response->assertRedirect();  // Verifica se houve um redirecionamento após a requisição bem-sucedida
    info('--- Step 10: Redirect confirmed after request creation ---');
    info('');

    // Verificar se a requisição foi realmente salva no banco de dados
    info('--- Step 11: Verifying if the request is saved in the database ---');
    info('');
    assertDatabaseHas('requests', [  // Verifica a tabela 'requests' do banco de dados
        'book_id' => $book->id,
        'user_id' => $user->id,
        'status' => 'borrowed',
    ]);
    info('--- Step 12: Request successfully saved in the database ---');
    info('');
});
