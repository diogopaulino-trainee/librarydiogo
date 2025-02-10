<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Updated</title>
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
        }
        h1 {
            color: #2D96C6;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        .role {
            font-weight: bold;
            color: #2378A3;
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
        <h1>Hello, {{ $userName }}</h1>
        <p>Your role has been updated to <span class="role">{{ $newRole }}</span>.</p>
        <p>If this was a mistake, please contact an administrator.</p>
        <p class="footer">Best regards,<br><strong>Library Paulino</strong></p>
    </div>
</body>
</html>
