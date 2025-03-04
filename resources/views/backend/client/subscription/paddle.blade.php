<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div style="text-align: center">
    <div>
        <a href="{{ url()->previous() }}" onclick="return confirm('{{ __('are_you_sure') }}?')">{{ __('cancel_payment') }}</a>
    </div>
    <div class="paddle-container"></div>
</div>
<script src="{{ static_asset('admin/js/jquery.min.js') }}"></script>
<script src="{{ static_asset('frontend/js/paddle.js') }}"></script>
<script>
    @if(setting('is_paddle_sandbox_mode_activated'))
        Paddle.Environment.set("sandbox");
    @endif
    Paddle.Setup({
        token: '{{ setting('paddle_client_token') }}',

        eventCallback : function(data) {
            if (data.name == 'checkout.completed') {
                data._token = '{{ csrf_token() }}';
                data.trx_id = '{{ $trx_id }}';
                data.plan_id = '{{ $plan->id }}';

                $.ajax({
                    url : '{{ route('client.paddle.payment.success') }}',
                    type : 'POST',
                    data : data,
                    success : function(response) {
                        if (response.success) {
                            window.location.href = response.route;
                        }
                        else {
                            window.location.href = response.route;
                        }
                    }
                });
            }
        }
    });
    let config = {
        settings: {
            displayMode: "inline",
            theme: "light",
            locale: "en",
            frameTarget: "paddle-container",
            frameInitialHeight: "1200",
            frameStyle: "width: 100%; min-width: 312px; background-color: transparent; border: none;",
        },
        items: [
            {
                priceId: '{{ $price_id }}',
                quantity: 1
            },
        ],

    };
    @if($client->paddle_customer_id)
        config.customer = {
            id : "{{ $client->paddle_customer_id }}"
        };
    @endif
    Paddle.Checkout.open(config);
</script>
</body>
</html>