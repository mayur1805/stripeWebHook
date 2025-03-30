<?php
namespace App\Service;

use Stripe\Stripe;
use Stripe\PaymentIntent;  
use App\Models\Payment; 
use App\Models\StripeWebhookLogs;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    
    public function createPayment($amount){
        try {
            
            //create payment intent for checkout
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'description' => 'Laravel Stripe Payment',
            ]);
            if($paymentIntent){
                //if payment intent created properly add records
                $payment = Payment::create([
                    "payment_intent_id" => $paymentIntent->id,
                    "amount" => $paymentIntent->amount / 100,
                    "currency" => $paymentIntent->currency,
                    "payment_method" => $paymentIntent->payment_method,
                ]);
                if($payment){
                    $intent = $paymentIntent->client_secret;
                    $details = [
                        "intent" => $intent,
                        "amount" => $amount
                    ];
                    return successResponse("Payment initialized successfully.",$details);
                }else{
                    return errorResponse("An error occurred while saving the payment details. Please contact support.");
                }
            }else{
                return errorResponse("Failed to initialize payment. Please try again later.");
            }
            
        } catch (ApiErrorException $e) {
            return errorResponse("Something went wrong.");
        }
    }

    public function handleWebhook($requestParam)
    {
        
        $webhookSecret = config('services.stripe.webhook_secret');
        $payload = $requestParam->getContent();
        $sigHeader = $requestParam->header('Stripe-Signature');
    
        try {
            // Verify the webhook signature
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            //Get the message as per the event occur
            $message = $this->getWebHookEventMessage($event);
            $paymentIntent = $event->data->object ?? null;
          if($paymentIntent){
                $amount = $paymentIntent->amount ? $paymentIntent->amount / 100 : null; // payment in cent so need to convert 
                $type = $event->type;
                $currency = $paymentIntent?->currency ?? null;
                $eventId = $paymentIntent->id;
                $status = $paymentIntent->status ?? null;
                
                // save logs details in databse
                StripeWebhookLogs::create([
                    'payment_intent_id' => $eventId,
                    'type' => $type,
                    'payload' => $payload,
                    'amount'  => $amount,
                    'currency' => $currency,
                    'status' => $status
                ]);
                return successResponse( $message,[]);
          }else{
            return errorResponse("Payment Intent does not created.");   
          }
        } catch (SignatureVerificationException $e) {
            return errorResponse('Invalid signature');
        }
    }

    public function getWebHookEventMessage($event)
    {
        $message = '';
            switch ($event->type) {
                case 'payment_intent.amount_capturable_updated':
                    $message = "Payment intent is now ready to be captured.";
                    break;
                case 'payment_intent.canceled':
                    $message = "Payment intent has been canceled.";
                    break;
                case 'payment_intent.created':
                    $message = "A new payment intent has been created.";
                    break;
                case 'payment_intent.partially_funded':
                    $message = "Payment intent has been partially funded.";
                    break;
                case 'payment_intent.payment_failed':
                    $message = "Payment attempt has failed.";
                    break;
                case 'payment_intent.processing':
                    $message = "Payment is currently being processed.";
                    break;
                case 'payment_intent.requires_action':
                    $message = "Payment requires additional action.";
                    break;
                case 'payment_intent.succeeded':
                    $message = "Payment has been successfully completed.";
                    break;
                default:
                    $message = "Received unknown event type: " . $event->type;
            }

            return $message;
    }
}
