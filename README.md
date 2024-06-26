<table>
<tr style="border: none">
<td style="border: none">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Laravel Logo" style="outline: 1px solid transparent"></a>
</td>
<td style="border: none; padding-right: 65px;">
<a href="https://restfulapi.net" target="_blank"><img src="https://res.cloudinary.com/practicaldev/image/fetch/s--utGlBfsh--/c_imagga_scale,f_auto,fl_progressive,h_500,q_auto,w_1000/https://dev-to-uploads.s3.amazonaws.com/uploads/articles/6yxtne4omqzoo24dvavn.png" width="180" alt="Laravel Logo" style="outline: 1px solid transparent"></a>
</td>
<td style="border: none">
<a href="https://jwt-auth.readthedocs.io" target="_blank" style="outline: 1px solid transparent"><img src="https://seeklogo.com/images/J/jwt-logo-11B708E375-seeklogo.com.png" width="230" alt="Laravel Logo" style="outline: 1px solid transparent"></a>
</td>
</tr>
</table>

# Laravel 10 Template

![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![JWT Auth](https://img.shields.io/badge/JWT-Auth-blue)

This project is an extended version of the Laravel 10 source code designed to provide a template for JWT authentication-based REST API server applications with a full user authentication cycle.

## About the Project

This is a starter template for developing REST API applications using Laravel 10.

**It includes:**

- A clean REST API implementation.
- Client JWT authentication with all necessary options: authentication with getting bearer token, token refreshing, etc.
- User authentication, including: registration (with email verification), authentication (login and logout), password changing, and so on.

## Getting Started

These instructions will help you clone and run the project on your local machine for development and testing purposes.

### Prerequisites

Necessary:
- PHP >= 8.1.
- Composer.
 
On your choice: 
- MySQL or another supported database.
- Or Docker for using [Laravel Sail](https://laravel.com/docs/10.x/sail).

### Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/kublahanov/laravel-10-template.git
    cd laravel-10-template
    ```

2. Install dependencies via Composer:

    ```bash
    composer install
    ```

3. Copy the `.env.example` file to `.env` (if it not completed automatically in step 2):

    ```bash
    cp .env.example .env
    ```

4. Generate the application key (if it not completed automatically in step 2):

    ```bash
    php artisan key:generate
    ```

5. Configure your database settings in the `.env` file.

6. Run the database migrations:

    ```bash
    php artisan migrate
    ```

7. Start the local development server (or use [Laravel Sail](https://laravel.com/docs/10.x/sail) package):

    ```bash
    php artisan serve
    ```

Your application should now be accessible at `http://localhost:8000`.

### Features

- **JWT Authentication**: Implemented using the `tymon/jwt-auth` package.
- **REST API**: All routes and controllers for auth-based API.

### Usage Examples

#### User Registration

`POST /api/auth/register`

```json
{
    "name": "John Doe",
    "email": "john.doe@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

Response:

```json
{
    "message": "User registered successfully, please check your email for verification link",
    "user": {
        "name": "John Doe",
        "email": "john.doe@example.com",
        "updated_at": "2024-06-26T18:03:49.000000Z",
        "created_at": "2024-06-26T18:03:49.000000Z",
        "id": 1
    }
}
```

#### User Login

`POST /api/auth/login`

```json
{
    "email": "john.doe@example.com",
    "password": "password"
}
```

Response:

```json
{
    "access_token": "your-jwt-token",
    "token_type": "bearer",
    "expires_in": 3600
}
```

### Deployment

For deploying on a production server, it is recommended to follow these steps:

1. Install all dependencies and run the migrations.
2. Configure your web server (e.g., Nginx or Apache) to work with Laravel.
3. Ensure you have the appropriate caching and session levels set up.

### Contributing

If you have suggestions for improving this project or want to report a bug, please open an issue or create a pull
request.

### License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
