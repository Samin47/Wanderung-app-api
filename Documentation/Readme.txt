Title: API Development with Geolocation and User Authentication

## Requirements ##

- PHP 7.3 or higher
- Laravel 8.75 or higher


## Installation ##

To get started with the project, follow these steps:

1. Clone the repository.
2. Install the dependencies in the project directory: (commands)
	-composer install
	-composer update

3. Configure the environment:
	-Copy the .env.example file to .env and update the necessary configuration values such as database credentials. 
	-Obtain a Foursquare API token by creating an account and registering your application on the Foursquare Developers website (https://developer.foursquare.com/).
	-Find the `FOURSQUARE_API_TOKEN` variable and replace the placeholder value with your actual Foursquare API token in .env file.

4. Generate an app key. (command)
	-php artisan key:generate


5. Create the database: (command or manually)
	-php artisan db:create
This will create the database specified in the .env file.

6. Run database migrations: (command)
	-php artisan migrate

7. Start the development server:(command)
	-php artisan serve



## API Documentation ##

### Signup Endpoint

- Endpoint: /api/signup
- Method: POST
- Description: Allow users to sign up by providing their email and password.
- Request Body:
	- email (string): The user's email.
	- password (string): The user's password. Must meet the following validation rules:
		- Minimum length: 8 characters.
		- At least one uppercase letter.
		- At least one lowercase letter.
- Response:
	- message (string): Success message indicating the user registration status.
	- user (object): Object containing user information.
		- name (string): The user's name.
		- email (string): The user's email.




### Login Endpoint

- Endpoint: /api/login
- Method: POST
- Description: Allow users to log in by providing their email and password.
- Request Body:
	- email (string): The user's email.
	- password (string): The user's password.
- Response:
	- message (string): Success message indicating a successful login.
	- token (string): The authentication token generated for the user. This token can be included in subsequent API requests to authorize and authenticate the user.




### Nearby Places Endpoint

- Endpoint: /api/places
- Method: GET
- Description: Get a list of nearby places based on the provided coordinates.

- Query Parameters:
  	- lat (float): Latitude of the location.
  	- long (float): Longitude of the location.
  	- radius (float, optional): Radius in meters to search for nearby places. Default is 5000. Maximum value is 100,000.
  	- limit (integer, optional): Maximum number of places to return. Default is 10. Maximum value is 50.

- Headers:
  	- Authorization (string): Bearer token for authentication. Include the token in the request header
  	- Accept (string): Set to `application/json` to indicate that the API response should be in JSON format.

To authenticate the request, include a bearer token in the `Authorization` header. The token should be obtained through the login endpoint and appended as follows:

Authorization: Bearer {token}
>Replace `{token}` with the actual bearer token received from the login endpoint.

- Response:
  	- An array of nearby places with their details. The response will be in json format.


## Credits ##

This project utilizes the Foursquare API for retrieving nearby places. The Foursquare API provides comprehensive location data and allows us to fetch relevant information about various places based on coordinates. More information about the Foursquare API can be found at https://developer.foursquare.com


