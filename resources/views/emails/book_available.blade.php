<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Availability Notification</title>
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
            color: #2D96C6;
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
            background: #2D96C6;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn:hover {
            background: #2378A3;
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
        
        <p>The book you requested to be notified about is now available!</p>
        
        <p><strong>Book:</strong> {{ $bookTitle }}</p>

        <p>
            <img src="{{ $coverImage }}" alt="Book Cover" style="max-width: 150px; height: auto; display: block; margin: 10px auto;">
        </p>

        <p><em>If the cover image is not displayed, please check the attached file.</em></p>

        <p>You can now request this book from the library.</p>

        <p><a href="{{ $requestUrl }}" class="btn">Go to Book Page</a></p>

        <p>If you no longer want to receive notifications about this book, you can cancel your subscription on the book's page.</p>

        <p class="footer">Best regards,<br><strong>Library Paulino</strong></p>
    </div>
</body>
</html>
