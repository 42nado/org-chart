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
    Description: Creates a new position in the organizational chart.
    Parameters:
    name (string, required): The name of the position.
    report_to (integer, optional): The ID of the parent position.

View All Positions: GET /api/positions
    Description: Retrieves a list of all positions in the organizational chart.

View Position Details: GET /api/positions/{id}
    Description: Retrieves details for a specific position by ID.
    Parameters:
    {id} (integer, required): The ID of the position.

Update Position: PUT /api/positions/{id}

Description: Updates a specific position's name or parent relationship.
Parameters:
    {id} (integer, required): The ID of the position to update.
    name (string, required): The new name of the position.
    report_to (integer, optional): The ID of the new parent position.

Delete Position: DELETE /api/positions/{id}

    Description: Deletes a specific position from the organizational chart.
    Parameters:
    {id} (integer, required): The ID of the position to delete.
