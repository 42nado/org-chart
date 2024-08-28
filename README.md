# Organization Chart API

## Installation

Clone the repository

   git clone https://github.com/42nado/organizational-chart.git
   cd organizational chart

# Install dependencies

composer install
# Configure the environment

Copy the .env.example to .env and adjust your environment settings.

# Run migrations
php artisan migrate

# Start the server

php artisan serve or use Laravel Herd


# API Endpoints
Create Position: POST /api/positions
View All Positions: GET /api/positions
View Position Details: GET /api/positions/{id}
Update Position: PUT /api/positions/{id}
Delete Position: DELETE /api/positions/{id}
