
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@lang('Payment with Stripe')</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

@php
    $stripe = $data->StripeJSAcc;
    $publishable_key = $stripe[0]->publishable_key;
    $sessionId = $data->session->id;
@endphp

<script>
    "use strict";
    var stripe = Stripe('{{$publishable_key}}');
    stripe.redirectToCheckout({
        sessionId: '{{$sessionId}}'
    });
</script>
</body>
</html>
