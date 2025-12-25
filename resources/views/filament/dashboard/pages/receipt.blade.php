<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .item-details {
            flex: 1;
        }
        .item-price {
            text-align: right;
            min-width: 60px;
        }
        .totals {
            margin-top: 10px;
            text-align: right;
        }
        .totals div {
            margin-bottom: 2px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>{{ $tenant->name ?? 'Restaurant' }}</h1>
        <p>Order #{{ $order->id }}</p>
        <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
        @if($order->table)
            <p>Table: {{ $order->table->name }}</p>
        @endif
        @if($order->staff)
            <p>Server: {{ $order->staff->user->name ?? $order->staff->id }}</p>
        @endif
    </div>

    <div class="divider"></div>

    @foreach($order->items as $item)
        <div class="item">
            <div class="item-details">
                {{ $item->quantity }}x {{ $item->menuItem->name }}
                @if($item->variant)
                    <br><small>({{ $item->variant->name }})</small>
                @endif
            </div>
            <div class="item-price">
                {{ number_format($item->subtotal, 2) }}
            </div>
        </div>
    @endforeach

    <div class="divider"></div>

    <div class="totals">
        <div>Subtotal: {{ number_format($order->total_amount, 2) }}</div>
        <div><strong>Total: {{ number_format($order->total_amount, 2) }}</strong></div>
    </div>
    
    <div class="divider"></div>
    
    <div class="totals">
        @foreach($order->payments as $payment)
            <div>Paid ({{ ucfirst($payment->payment_method) }}): {{ number_format($payment->amount, 2) }}</div>
        @endforeach
    </div>

    <div class="footer">
        <p>Thank you for dining with us!</p>
        <p>Powered by Resevit</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Print Again</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Close</button>
    </div>

</body>
</html>
