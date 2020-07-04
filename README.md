# ouroom-project
Google Classroom Wannabee

## Requirements
* PHP 7+
* NodeJS
* Composer
* Laravel

## Installation
* Edit .env file according to your needs
* Run :
```bash
$ composer update
$ php artisan classroom:setup
```

## Usage
* Creator Level
  Username | Password
  -------- | ---------
  creator  | creatoratm
  * Can access all feature available
* Administrator Level
  * Can access all feature except update role, assessment, and notifying Creator
* Guru Level
  * Class feature
  * Assessment
* Siswa Level
  * Index class and Index feed
  * Assessment