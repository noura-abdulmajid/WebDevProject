<div>
    <p>
        Your Order has been shipped <br>
        Order ID: {{ $message->O_ID }} <br>
        Expected Delivery Date: {{ (new DateTime())->modify('+3 days') }}
    </p>
</div>