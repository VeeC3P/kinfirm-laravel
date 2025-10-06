# Quiz Application

## Requirements for the task

Write a command which imports products from given JSON file (products.json).

Write a scheduled command which imports products stock from given JSON file
(stocks.json).

Write a simple JSON API endpoint which can list all existing products.

Create a front-end:
- list all products.
- single product page with related products.
- Use cache for single product page information (keep in mind that stock must be
shown in real-time).

### Bonus tasks
- Add unit/feature tests.
- Add authentification for JSON API.
- Use Laravel Queues for products import.
- Show most popular tags list by products count.

## Installation

### Prerequisites
Make sure you have the following installed:
- PHP 8.2
- Laravel 12

### Steps
- Install composer dependancies for Laravel
   ```bash
   composer install

- Set up your .env file or keep the existing .env for quick startup
   ```
   cp .env.example .env

- If you are running Sail, then all PHP commands should start with "sail" instead.
   ```bash
   sail artisan migrate
   sail artisan import:stocks
   sail artisan import:products
   sail artisan queue:work

- Create database
   ```bash
   php artisan migrate

- Generate application key
    ```bash
    php artisan key:generate

- Run the seeder to generate the questions & answers
   ```bash
   php artisan db:seed

- Run the import scripts for stocks & products.
   ```bash
   php artisan import:stocks
   php artisan import:products

   To skip queues (Jobs)
   php artisan import:stocks --test
   php artisan import:products --test

- Run the import jobs (if imports done without --test parameter)
    ```bash
    php artisan queue:work

- Run the project
    ```bash
    php artisan serve

### Usage

Frontend:
- View all products: http://localhost/products
- View individual products: http://localhost/products/16 or click on one of the products in the GUI

Backend (API). Use Postman for easier access
- Use the JSON below for an import

```
{
	"info": {
		"_postman_id": "c36ae1d4-2f2c-4888-8a56-1e4895312ded",
		"name": "Kinfirm Products API",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
		"_exporter_id": "44869262"
	},
	"item": [
		{
			"name": "Get Specific Product",
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "3|1JqtGyzMuZZCusvqU7eH2GXMFEK7eQZFTU3SBk5Y9958ef00",
							"type": "string"
						}
					]
				},
				"method": "POST",
				"header": [],
				"body": {
					"mode": "urlencoded",
					"urlencoded": [
						{
							"key": "email",
							"value": "admin@example.com",
							"type": "text",
							"disabled": true
						},
						{
							"key": "password",
							"value": "password",
							"type": "text",
							"disabled": true
						},
						{
							"key": "token",
							"value": "2|pWyXYd75mkMYgKFMkm8aqS65YcT5OezUnl7QBycK0efc3f4c",
							"type": "text",
							"disabled": true
						}
					]
				},
				"url": {
					"raw": "http://localhost/api/products/16",
					"protocol": "http",
					"host": [
						"localhost"
					],
					"path": [
						"api",
						"products",
						"16"
					]
				}
			},
			"response": []
		},
		{
			"name": "Get All Product",
			"protocolProfileBehavior": {
				"disableBodyPruning": true
			},
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "3|mDdzeymzjiHlQC2sZf2XmAbopEezBlhh2urFX6Av3a503cd2",
							"type": "string"
						}
					]
				},
				"method": "GET",
				"header": [],
				"body": {
					"mode": "urlencoded",
					"urlencoded": [
						{
							"key": "email",
							"value": "admin@example.com",
							"type": "text",
							"disabled": true
						},
						{
							"key": "password",
							"value": "password",
							"type": "text",
							"disabled": true
						},
						{
							"key": "token",
							"value": "2|pWyXYd75mkMYgKFMkm8aqS65YcT5OezUnl7QBycK0efc3f4c",
							"type": "text",
							"disabled": true
						}
					]
				},
				"url": {
					"raw": "http://localhost/api/products",
					"protocol": "http",
					"host": [
						"localhost"
					],
					"path": [
						"api",
						"products"
					]
				}
			},
			"response": []
		},
		{
			"name": "Login Token",
			"request": {
				"method": "POST",
				"header": [],
				"body": {
					"mode": "urlencoded",
					"urlencoded": [
						{
							"key": "email",
							"value": "admin@example.com",
							"type": "text"
						},
						{
							"key": "password",
							"value": "password",
							"type": "text"
						}
					]
				},
				"url": {
					"raw": "http://localhost/api/login",
					"protocol": "http",
					"host": [
						"localhost"
					],
					"path": [
						"api",
						"login"
					]
				}
			},
			"response": []
		},
		{
			"name": "Logout Token",
			"request": {
				"method": "POST",
				"header": [],
				"url": {
					"raw": "http://localhost/api/logout",
					"protocol": "http",
					"host": [
						"localhost"
					],
					"path": [
						"api",
						"logout"
					]
				}
			},
			"response": []
		}
	]
}

