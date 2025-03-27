# Bravoure BE Challenge

This project is an implementation of the Bravoure BE Challenge, which involves fetching data from the YouTube and Wikipedia APIs, merging it, and providing it as a JSON response.

## Technologies Used

* Laravel
* PHP 8.3+
* Docker / Docker Compose
* YouTube API
* Wikipedia API

## Features

* Fetches the most popular videos from YouTube API for specified countries (UK, NL, DE, FR, ES, IT, GR). 
* Fetches initial paragraphs of Wikipedia articles for the same countries.
* Merges the data from YouTube and Wikipedia for each country.
* Returns the results in JSON format.
* Supports pagination, offset, and country filtering.
* Caching is implemented for performance optimization.

## Getting Started

### Prerequisites

* PHP 8.3+
* Composer
* MySQL
* Docker and Docker Compose (if using Docker)
* YouTube API Key
    * You'll need to obtain a YouTube API key from the Google Cloud Console.
* Ensure you have created a database for the application.

### Installation

1. Clone the repository:

    ```bash
    git clone <repository_url>
    cd bravoure-challenge
    ```
2. Install PHP dependencies:

    ```bash
    composer install
    ```
3. Copy the environment file:

    ```bash
    cp .env.example .env
    ```
4. Generate the application key:

    ```bash
    php artisan key:generate
    ```
5. Configure your `.env` file:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=<your_database_name>
    DB_USERNAME=<your_database_user>
    DB_PASSWORD=<your_database_password>
    
    # Add your YouTube API key
    YOUTUBE_API_KEY=<your_youtube_api_key>
    ```

### Database Setup

1. Run database migrations:

    ```bash
    php artisan migrate
    ```

### Running the Application

#### Using PHP Built-in Server

1. Start the PHP development server:

    ```bash
    php artisan serve
    ```
2. The application will be accessible at `http://localhost:8000`.

#### Using Docker (Recommended)

1. **Set up Docker:**

    * Ensure Docker and Docker Compose are installed.
2. **Build and run the Docker containers:**

    ```bash
    docker compose up -d --build
    ```
3. The application will be accessible at `http://localhost:8000`.

### API Endpoints

#### Get Country Data

*`GET /api/countries/{code}`
*Fetches enriched data for a specific country.
***Parameters:**
    * `code` (string, required): The country code (uk, nl, de, fr, es, it, gr).
    * `offset` (integer, optional): Offset for paginating videos.
    * `page` (integer, optional): Page number for paginating videos.
***Example:**

    ```
    http://localhost:8000/api/countries/uk?offset=0&page=1
    ```

### Response Format

The API returns a JSON response with the following structure:

```json
{
  "code": "uk",
  "name": "United Kingdom",
  "wikipedia_paragraph": "...",
  "videos": [
    // Array of video objects
    {
      "id": "...",
      "title": "...",
      "description": "...",
      "thumbnail": {
        "normal": "...",
        "high": "..."
      }
    }
  ],
  "pagination": {
    "offset": 0,
    "page": 1,
    "total_videos": 100,
    "per_page": 10,
    "total_pages": 10
  }
}
