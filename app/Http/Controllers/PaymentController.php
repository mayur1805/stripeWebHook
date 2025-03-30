<?php
namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use Illuminate\Support\Facades\Session;
use App\Service\PaymentService;
    
    class PaymentController extends Controller
    {
        public $paymentService;
        public function __construct(PaymentService $paymentService)
        {
            $this->paymentService = $paymentService;
        }
        /**
         * this is for payment view page
         */
        public function index()
        {
            return view('payment.initiate');
        }
        
        /**
         * this function is for handling payment checkout 
         * @param App\Http\Requests\PaymentRequest $request
         */
        public function checkout(PaymentRequest $request)
        {
            //pyment service call for create payment
            $response = $this->paymentService->createPayment($request->amount);
            if($response['status'] == true){
                //go to the checkout page with payment intent and amount
                return view('payment.checkout',$response['data']);
            }
            Session::flash("error",$response['message']);
            return redirect()->route('payment.form');
        }
    
}