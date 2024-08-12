# Setup

- Create database and edit .env accordingly
- Create key for WeatherAPI and add in .env as "WEATHERAPI_KEY" (in the email I provide my API key)
- Execute commands in the following order:
```
composer update
php artisan migrate
```

# Run the Application
- To run the background job:
```
php artisan queue:work
```

- To run the web service:

```
php -S localhost:8000 -t public
```

# Examples with curl

## Adding a new location

Request:  
```
curl --request POST 'localhost:8000/location?long={longitude}&lat={latitude}'
```
Response:
```
HTTP 200 OK
```

## Getting data from a specified location and time

Request:
```
curl 'localhost:8000/location?long={longitude}&lat={latitude}&time={Y-m-d H:i:s}'
```

Response:
```

```