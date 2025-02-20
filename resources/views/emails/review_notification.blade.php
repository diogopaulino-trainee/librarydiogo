<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Book Review Submitted</title>
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
        <h2>New Review Submitted</h2>

        <p><strong>User:</strong> {{ $userName }}</p>
        <p><strong>Book:</strong> {{ $bookTitle }}</p>
        <p><strong>Review:</strong> "{{ $reviewContent }}"</p>

        <p>
            <a href="{{ $reviewLink }}" class="btn">View Review</a>
        </p>
        <p>
            <a href="{{ $manageReviewsLink }}" class="btn" style="background: #777;">Manage Reviews</a>
        </p>

        <p class="footer">Best regards,<br><strong>Library Paulino</strong></p>
    </div>
</body>
</html>
