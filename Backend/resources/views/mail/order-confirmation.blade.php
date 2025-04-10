<div>
    <p>
        Order Confirmed <br>
        Your Order ID: {{ $order->O_ID }} <br>
        Order Details: {{ $order->order_date }}
        {{ $order->total_payment }}
        {{ $order->shipping_address }}
    </p>
</div>