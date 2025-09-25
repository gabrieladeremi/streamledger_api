StreamLedger API

StreamLedger API is a secure, scalable, and event-driven wallet & transaction management system built with Laravel 12, PostgreSQL, Kafka, and Sanctum Authentication.

It enables users to:

Register & authenticate securely

Manage their wallet balance

Perform debit/credit transactions (with validations)

Export transaction history asynchronously (Excel)

Stream transaction events into Kafka for real-time processing

🚀 Tech Stack

Laravel 12 – Backend framework

PostgreSQL – Relational database

Laravel Sanctum – Token-based authentication

PEST – Testing framework

Kafka – Event streaming & messaging

Excel Export – Laravel Queue Jobs + maatwebsite/excel

📦 Setup & Installation

Clone Repository
git clone https://github.com/your-username/streamledger_api.git cd streamledger_api

Install Dependencies
composer install cp .env.example .env php artisan key:generate

Configure Environment
Edit .env with your setup:

APP_NAME=StreamLedger APP_ENV=local APP_KEY=base64:GENERATED_KEY APP_DEBUG=true APP_URL=http://localhost

DB_CONNECTION=pgsql DB_HOST=127.0.0.1 DB_PORT=5432 DB_DATABASE=streamledger DB_USERNAME=****** DB_PASSWORD=******

Sanctum
SESSION_DRIVER=cookie SESSION_DOMAIN=localhost

Kafka
KAFKA_BROKERS=localhost:9092 KAFKA_TOPIC=transactions

Database Setup
php artisan migrate

Run Services
Laravel Server php artisan serve
*Queue Worker (for async jobs like exports) php artisan queue:work

Kafka (start locally if installed with Homebrew) brew services start kafka brew services start zookeeper

Kafka Consumer (debugging only) kafka-console-consumer --topic transactions --bootstrap-server localhost:9092 --from-beginning

🔑 Authentication

I used Laravel Sanctum for token-based authentication as requested.

Register: POST /api/v1/register

Login: POST /api/v1/login

Copy the returned token and send in headers: Authorization: Bearer

📖 API Endpoints Wallet

GET /api/v1/wallet → Fetch user wallet & balance

Transactions

POST /api/v1/transactions → Create a new transaction

Body: { "entry": "credit", "amount": 1000 }

GET /api/v1/transactions?page=1&limit=10 → List paginated transactions

POST /api/v1/transactions/export → Trigger export job (Excel generated asynchronously)

** As postman documentation link is attached below

https://documenter.getpostman.com/view/26718931/2sB3QDuCbu

📊 Design Choices & Trade-offs

Laravel Sanctum vs JWT
Chose Sanctum for simplicity & built-in Laravel integration.

Trade-off: JWT is more standard for distributed systems, but Sanctum’s cookie & token-based flows made local development easier.

PostgreSQL vs MySQL
Picked PostgreSQL for stronger data consistency & advanced features (transactions, JSON fields).

Trade-off: Slightly steeper learning curve for team members used to MySQL.

Transactions Logic in Action Class
Used App\Actions\CreateTransaction to encapsulate wallet logic.

Trade-off: More boilerplate, but improves testability & reusability.

Event Streaming with Kafka
Kafka ensures all transactions can be consumed in real-time by other services.

Trade-off: Added setup complexity (ZooKeeper, Kafka brokers). But it prepares the system for horizontal scaling.

Queued Jobs for Export
Heavy operations (Excel generation) run asynchronously via Laravel Jobs.

Trade-off: Requires running a queue worker separately, but prevents blocking user requests.

PEST for Testing
PEST was chosen for readability & developer experience.

Trade-off: PHPUnit is more widely documented, but PEST increases speed of writing tests.

🧪 Running Tests

Run all feature & unit tests:

php artisan test

or with Pest directly:

./vendor/bin/pest

📌 Future Improvements

Add Kafka consumer within Laravel (php artisan kafka:consume)

Add Docker Compose for DB + Kafka setup (for portability)

Implement multi-wallet support per user

Implement robust error system
