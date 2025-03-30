@extends('layouts.layout')

@section('title', 'Stripe Payment initiate')
@section('scripts')
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
@endsection
        
@section('content')
    <div class="bg-gray-100 flex justify-center items-center min-h-screen">
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
@endsection      

@section('bottom_scripts')
    <script src={{ asset('js/stripeFromValidation.js') }}></script>
@endsection