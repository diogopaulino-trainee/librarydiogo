<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">List of Books</h2>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>ISBN</th>
                <th>Authors</th>
                <th>Publisher</th>
                <th>Price (€)</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->isbn }}</td>
                    <td>
                        @foreach ($book->authors as $author)
                            {{ $author->name }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </td>
                    <td>{{ $book->publisher->name ?? 'N/A' }}</td>
                    <td>{{ number_format($book->price, 2, ',', '.') }} €</td>
                    <td>{{ $book->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $book->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
