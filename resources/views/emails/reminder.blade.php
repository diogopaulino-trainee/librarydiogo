<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Return Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            text-align: center;
        }
        h2 {
            color: #D97706;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        .cover-image {
            width: 200px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #D97706;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn:hover {
            background: #B45309;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello, {{ $name }}!</h2>

        <p>This is a friendly reminder that your borrowed book is due for return **tomorrow**.</p>

        <p><strong>Book:</strong> {{ $bookTitle }}</p>
        <p><strong>Expected Return Date:</strong> {{ $expectedReturnDate }}</p>

        @if($coverImage)
            <p>
                <img src="{{ $coverImage }}" alt="Book Cover" class="cover-image">
            </p>
            <p><em>If the cover image is not displayed, please check the attached file.</em></p>
        @endif

        <p>Please ensure the book is returned on time to avoid overdue penalties.</p>

        <p class="footer">Best regards,<br><strong>Library Paulino</strong></p>
    </div>
</body>
</html>
