{{-- resources/views/moyasar_payment.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moyasar Payment Form</title>

    <!-- Moyasar Styles -->
    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.7.3/moyasar.css" />

    <!-- Moyasar Scripts -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
    <script src="https://cdn.moyasar.com/mpf/1.7.3/moyasar.js"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
</head>

<body>
    <section class="flex h-screen justify-center items-center">
        <div class="mysr-form w-80 m-10"></div>
    </section>

    <script>
        Moyasar.init({
            element: '.mysr-form',
            amount: 1000, // Amount in the smallest currency unit (10 SAR = 1000 Halalas)
            currency: 'SAR',
            description: 'Coffee Order #1',
            publishable_api_key: '{{ env("MOYASAR_PUBLISHABLE_KEY") }}', // Use your publishable key from .env
            callback_url: '{{ url('api/payment/callback?reservationId=27') }}',
            methods: ['creditcard'],
            //fixed_width: false, // Optional
           /* on_initiating: function () {
                return new Promise(function (_, reject) {
                    setTimeout(function () {
                        reject('This is just a sample form, it won\'t work ;)');
                    }, 2000);
                });
            }*/
        });
    </script>
</body>

</html>
