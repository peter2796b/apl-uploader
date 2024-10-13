
# APL File Uploader

Laravel/React file uploader to Azure blob storage



## Overview / Requirments
Make sure you have the follwing installed

- PHP >8.1
- Node > 18.20
- php-gd extension installed

This is a mono repo with Laravel project in the API directory and react project in the SPA
## Getting started
- Clone the repo
- `cd API && composer install`
- `cp .env.example .env`
- `php aritsan key:generate`
- `php artisan migrate`
- Copy over the Azure credentials in the .env
- `php artisan storage:link`
- `php artisan serve` this will start the api server at `http://localhost:8000
- `cd SPA`
- `npm install`
- `npm run dev` this will start the web app at `http://localhost:5173

To see files being stored to Azure container update `APP_ENV=production`

## Implementation
I have kept the api public and no user authentication is required, Ideally I would setup authentication to keep track of which users uploaded the files.

Storing the `user_id` on `files` table.

see `API/api.php` for avaible api routes.


#### Extendibility

`api/files/upload` - Is extendible and can upload all types of files.

Dynamic validation for different types of files is handled in `FileUploadRequest`

In the `FileService` for the upload method Im using a factory pattern to give us a pre-processor for different file types
```
private function makePreprocessor(FileType $type, $file)
    {
        return match ($type) {
            FileType::IMAGE => new ImagePreProcessor($file),
        };
    }
```

Pre-Processing is done just before we upload any file to handle any sorts of requirments 

#### Testability
Ideally I would have tests written here, hence I have made the `FileController`
testable by using `FileService` and DI into the controller.


`FileService` can be unit tested by mocking the `Storage` Facade and test the `upload` method

#### Pre-processors
We can implement the `PreProcessorInterface` to implement pre-processors for other types of file.

The `process` method drives the processing that that needs to be called.

By default we call all the functions, and individual function decide based on the configuration if we need to perform the action.