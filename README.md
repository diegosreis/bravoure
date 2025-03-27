# Bravoure BE Challenge

This project is an implementation of the Bravoure BE Challenge, which involves fetching data from the YouTube and Wikipedia APIs, merging it, and providing it as a JSON response.

## Technologies Used

* Laravel
* PHP 8.3+
* Docker / Docker Compose
* YouTube API
* Wikipedia API

## Features

* Fetches the most popular videos from the YouTube API for specified countries (UK, NL, DE, FR, ES, IT, GR).
* Fetches initial paragraphs of Wikipedia articles for the same countries.
* Merges the data from YouTube and Wikipedia for each country.
* Returns the results in JSON format.
* Supports pagination, offset, and country filtering.
* Caching is implemented for performance optimization.


**Explanation of Key Directories:**

* **`app/Application/UseCases/`:** Contains use case classes that orchestrate the domain logic to fulfill specific application requirements (e.g., `GetEnrichedCountryDataUseCase`).
* **`app/Domain/Entities/`:** Contains core domain entities representing the main concepts of the application (e.g., `Country`, `Video`).
* **`app/Domain/Repositories/`:** Contains classes responsible for data access and persistence, including caching strategies (e.g., `CountryRepository`, `CountryCacheHandler`).
* **`app/Domain/Services/`:** Contains classes that encapsulate domain logic or interactions with external systems (e.g., `YouTubeService`, `WikipediaService`, `DataEnrichmentService`).
* **`app/Domain/ValueObjects/`:** Contains value objects, which are immutable objects defined by their attributes (e.g., `Thumbnail`).
* **`app/Http/Controllers/`:** Contains controller classes that handle HTTP requests and return responses (e.g., `CountryController`).
* **`app/Http/Resources/`:** Contains resource classes responsible for transforming data for API responses (e.g., `CountryResource`, `VideoResource`).
* **`app/Providers/`:** Contains service providers, which are used to bootstrap the application.

## Getting Started

### Prerequisites

* PHP 8.3+
* Composer
* MySQL
* Docker and Docker Compose (if using Docker)
* YouTube API Key
    * You'll need to obtain a YouTube API key from the Google Cloud Console.

### Installation

1.  Clone the repository:

    ```bash
    git clone https://github.com/diegosreis/bravoure.git
    cd bravoure
    ```

2.  Install PHP dependencies:

    ```bash
    composer install
    ```

3.  Copy the environment file:

    ```bash
    cp .env.example .env
    ```

4.  Generate the application key:

    ```bash
    php artisan key:generate
    ```

5.  Configure your `.env` file:

    ```
    DB_CONNECTION=mysql
    DB_HOST=bravoure-db
    DB_PORT=3306
    DB_DATABASE=bravoure_db  
    DB_USERNAME=       
    DB_PASSWORD=      

    # Add your YouTube API key
    YOUTUBE_API_KEY=<your_youtube_api_key>
    ```

**Note:** The `.env.example` file already contains the database name, username, and password. You only need to copy the file and set the `YOUTUBE_API_KEY`.

### Database Setup

1.  Run database migrations:

    ```bash
    php artisan migrate
    ```

### Running the Application

#### Using PHP Built-in Server

1.  Start the PHP development server:

    ```bash
    php artisan serve
    ```

2.  The application will be accessible at `http://localhost:8000`.

#### Using Docker (Recommended)

1.  **Set up Docker:**

    * Ensure Docker and Docker Compose are installed.

2.  **Build and run the Docker containers:**

    ```bash
    docker compose up -d --build
    ```

3.  The application will be accessible at `http://localhost:8000`.

### API Endpoints

#### Get Country Data

* `GET /api/countries/{code}`
* Fetches enriched data for a specific country.

***Parameters:***

* `code` (string, required): The country code (uk, nl, de, fr, es, it, gr).
* `offset` (integer, optional): Offset for paginating videos.
* `page` (integer, optional): Page number for paginating videos.

***Example:***

http://localhost:8000/api/countries/uk?offset=0&page=1


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
    "total_videos": 10,
    "per_page": 5,
    "total_pages": 2
  }
}
