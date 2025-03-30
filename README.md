# Laravel Payment Integration with Webhook Logging

This project implements payment creation using Payment Intent and a webhook for logging payment details into the database.

## Prerequisites
- Laravel 11+
- Composer
- Stripe API keys
- Ngrok installed
- Database configured in `.env`

## Project Setup
1. **Clone the Repository**
   ```sh
   git clone <repository-url>
   cd <project-folder>
   checkout the master branch
   ```

2. **Install Dependencies**
   ```sh
   composer install
   ```

3. **Set Up Environment**
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` file with your Stripe API keys and database credentials:
   ```env
   STRIPE_SECRET=<your-stripe-secret-key>
   STRIPE_WEBHOOK_SECRET=<your-stripe-webhook-secret>
   ```

4. **Run Migrations**
   ```sh
   php artisan migrate
   ```

5. **Start the Laravel Server**
   ```sh
   php artisan serve
   ```

6. **Set Up Ngrok for Webhook Testing**
   - Start Ngrok on port 8000 (default Laravel port):
     ```sh
     ngrok http 8000
     ```
   - Copy the generated `https` forwarding URL from Ngrok (e.g., `https://random-id.ngrok.io`)
   - Update Stripe webhook endpoint with the Ngrok URL:
     ```sh
     stripe listen --forward-to https://random-id.ngrok.io/api/webhook
     ```

7. **Test the Application**
   - Test the payment intent endpoint:
     ```sh
     curl -X POST http://127.0.0.1:8000/api/create-payment-intent
     ```
   - Test the webhook endpoint:
     ```sh
     stripe trigger payment_intent.succeeded
     ```

## Conclusion
This setup allows payments to be created using Stripe Payment Intent and logs webhook events in Laravel. Ensure that the webhook is properly configured in the Stripe dashboard for production use. Using Ngrok helps in local testing by exposing your local server to the internet securely.

