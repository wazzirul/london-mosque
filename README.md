# London Mosque API

## Installation
1. Composer Install // install composer vendor
2. cp .env.example .env // copy paster .env.example to .env
3. php artisan key:generate // generate key for the project
4. configure .env // setting database, etc
5. php artisan migrate // get tables
6. php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider" // to add Swagger Vendor
7. add code in the below to the .env 
L5_SWAGGER_GENERATE_ALWAYS = true
SWAGGER_VERSION = 2.0
8. php artisan l5-swagger:generate // generate api docs swagger for first time 
9. php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config"
10. php artisan serve // run the server

# Notes
- dont do composer update
- if theres new vendor on composer just run "composer install"
