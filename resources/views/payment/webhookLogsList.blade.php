<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Stripe Checkout Integration</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
        <script src={{ asset('js/stripe.js') }}></script>
        <style>
            .error {
                color: red;
                font-size: 14px;
            }
            .error-border {
                border: 2px solid red !important;
                border-radius: 5px;
            }
        </style>
        
        
        
        {{-- @vite('resources/css/app.css') <!-- Laravel 11 uses Vite for assets --> --}}
    </head>
    <body class="bg-gray-100">
    
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Stripe Payment</h2>
            @if(Session::has('error'))
                <div class="bg-red-100 text-red-800 p-4 rounded-md mb-4">
                    {{ Session::get('error')  }}
                </div>
            @endif
    
            <form action="{{ route('payment.checkout') }}" id="paymentInitiate" method="POST" class="space-y-4">
                @csrf
                <div class="form-group row">
                      <input class="form-control @error('amount') is-invalid @enderror" type="number" name="amount" placeholder="Enter Amount">  
                      @error('amount')
                      <p class="invalid-feedback">{{$message}}</p>
                      @enderror
                </div>
                <button type="submit" class="w-full py-3 bg-blue-600 text-white 
                font-semibold rounded-md hover:bg-blue-700 transition">
                    Payment
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src={{ asset('js/stripeFromValidation.js') }}></script>
    </body>
    </html>