<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Service\PaymentService;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    public $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * this function is for handling webhook details  
     */
    public function __invoke(Request $request)
    {
        $response = $this->paymentService->handleWebhook($request);
        if(isset($response['status'])){
            $status = $response['status'] == true ? 'success' : 'error';
            Log::channel('webhook')->info($status." : ".$response['message']);      
        }else{
            Log::channel('webhook')->info("Error : Something went wrong.");      
        }
    }
}
