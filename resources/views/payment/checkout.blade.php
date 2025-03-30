@extends('layouts.layout')

@section('title', 'Stripe Payment initiate')
@section('linkes')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
@endsection
@section('scripts')
    <script src="https://cdn.tailwindcss.com"></script>
@endsection

@section('content')
<div class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-2xl">
        <div id="payment-container">
            <div class="text-lg font-semibold text-white mb-4 text-center">
                <p>Your Total Amount is {{ $amount }} USD</p>
            </div>
            <!-- Payment form container -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('payment.checkout') }}" method="post" id="payment-form">
                    @csrf
                    <!-- Card information input section -->
                    <div class="mb-4">
                        <div class="text-lg font-medium mb-2">
                            <label for="card-element">Enter your credit card information</label>
                        </div>
                        <div class="border rounded p-4" id="card-element">
                        </div>
                        <!-- Display card input errors -->
                        <div class="text-red-500 text-sm mt-2" id="card-errors" role="alert"></div>
                        <input type="hidden" name="plan" value="" />
                    </div>
                    <div class="text-right">
                        <button
                            id="card-button"
                            class="bg-gray-800 text-white px-6 py-3 rounded hover:bg-gray-900"
                            type="submit"
                            data-secret="{{ $intent }}"
                        >
                            Pay
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Success message container -->
        <div id="success-container" class="hidden bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            <h2 class="text-xl font-semibold">Payment Successful!</h2>
            <p class="mt-2">Thank you for your payment. Your transaction has been processed successfully.</p>
            <a href="{{ route('payment.form') }}"> Redirect to payment page</a>
        </div>
    </div>
</div>
@endsection
@section('bottom_scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Define style for Stripe elements
        var style = {
            base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        // Initialize Stripe and create card element
        const stripe = Stripe('{{ env('STRIPE_KEY') }}', { locale: 'en' });
        const elements = stripe.elements();
        const cardElement = elements.create('card', { style: style });
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        cardElement.mount('#card-element');
        // Handle real-time validation errors from the card Element
        cardElement.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
        // Handle form submission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            // Confirm the card payment with Stripe
            stripe.handleCardPayment(clientSecret, cardElement, {
                payment_method_data: {}
            })
            .then(function(result) {
                if (result.error) {
                    // Display error if payment fails
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Hide the payment form and show the success message
                    document.getElementById('payment-container').classList.add('hidden');
                    document.getElementById('success-container').classList.remove('hidden');
                }
            });
        });
    </script>
@endsection