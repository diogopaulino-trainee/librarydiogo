<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Reminder</title>
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
        <h2>Hello, {{ $name }}!</h2>

        <p>We noticed that you have an order that has not been completed yet.</p>

        <p><strong>Order ID:</strong> {{ $orderId }}</p>
        <p><strong>Total:</strong> €{{ number_format($orderTotal, 2, ',', '.') }}</p>

        <p>Some items in your order may no longer be available. Complete your order soon to secure your selection.</p>

        <p>Would you like to complete your order now?</p>

        <p>
            <a href="{{ $checkoutUrl }}" class="btn">Complete Your Order</a>
        </p>

        <p>If you need any assistance, feel free to contact our support team.</p>

        <p class="footer">Best regards,<br><strong>Library Paulino</strong></p>
    </div>
</body>
</html>
