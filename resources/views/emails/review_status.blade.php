<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Review Status Update</title>
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
        .status {
            font-size: 18px;
            font-weight: bold;
            color: {{ $reviewStatus === 'Approved' ? '#28a745' : '#dc3545' }};
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
        <h2>Your Review Status Update</h2>

        <p><strong>Hello {{ $userName }},</strong></p>
        <p>Your review for the book <strong>{{ $bookTitle }}</strong> has been <span class="status">{{ ucfirst($reviewStatus) }}</span>.</p>

        @if($reviewStatus === 'Rejected')
            <p><strong>Reason:</strong> {{ $adminJustification }}</p>
        @endif

        <p>
            <a href="{{ $reviewLink }}" class="btn">View Book Details</a>
        </p>

        <p class="footer">Best regards,<br><strong>Library Paulino</strong></p>
    </div>
</body>
</html>
