# Adminer Admin

Adminer Admin is ultimative administration for any database. It is based on top of [Adminer Editor](https://www.adminer.org/en/editor/) from [Jakub Vrána](https://www.vrana.cz/). Adminer Admin is available in many languages (same as Adminer Editor).

Adminer Admin is intended for your customers (when you makes some simple website for them) to edit content on their websites.

You:
- create blade templates
- write some CSS, JS,...
- prepare database tables with some comments (explained below)
- prepare apropriate Models

Your customer:
- just use Adminer Admin

## Installation
- `composer require kluvi/adminer-admin`
- register provider `\kluvi\AdminerAdmin\Base\AdminerAdminServiceProvider::class,` in `config/app.php`
- edit `app/Http/Middleware/VerifyCsrfToken.php` middleware and add `'/adminer-admin*'` to `$except` array (or your custom route from `config/adminer-admin.php`)
- run `php artisan vendor:publish --provider="kluvi\AdminerAdmin\Base\ServiceProvider" --tag=migrations`, which publishes migrations
- optionaly run `php artisan vendor:publish --provider="kluvi\AdminerAdmin\Base\ServiceProvider" --tag=config`, which publishes config files (it is needed when you want to change default image uploads location)
- run `php artisan migrate`

## Usage

Before first use, you must define comments in database (to tables and columns). Comments must be valid JSONs.
If table or column should be displayed, it must have at least `{"name": "Column name"}`. Other tables and columns are not displayed.

### Columns

Most of columns are displayed as `<input type="text" />`. Some columns are different type, depending of default behaviour of Adminer Editor. Adminer Admin has some other types:

- `{"type": "image"}` - allows upload image file and displays this image. It currently does not makes thumbnails of uploaded images. You must pass `baseDir` and `baseUrl` which are used for storing and displaying images. Look into `config/adminer-admin.php` for default values. Images are stored in directory `{$baseDir}/{$tableName}/{$columnName}/{$primaryKeyValue}/{$uploadedFileName}` - so table must have some primary key. Images can be uploded after saving the record (it needs to have primaryKeyValue)
- `{"type": "multi-input"}` - allows storing multiple values as JSON array. Values are sortable and deletable. It's purpose is for storing for example product tags,...
- `{"type": "password"}` - password column. It stores passed password as password_hash()
- `{"type": "readonly"}` - this column is not editable. Just shows its value.

Adminer Admin also overrides some default behaviour of Adminer Editor in this cases:

- enum columns - it renders them as `<select>`
- foreign keys - it renders them as `<select>` with names as `{key column} - {column next to key column}` - it is more readable for users

### Tables

- `{"allowNew": false}` - disables creating new rows in this table
- `{"allowDelete": false}` - disables deleting rows in this table

### Other features

There is also one special table `adminer_help`. It has columns `table` and `text`. You can write some help text for your users there. This table is not directly viewable by users, but the content is rendered just after displaying table name. 

## Example

Example database is placed in `examples/dump.sql`. There is also `index.php` which demonstrates usage without Laravel.

## How to use Adminer Admin without Laravel?

- look at Downloader->download() - this part is responsible for downloading and editing of Adminer Editor (there should be done some replacements) - you should somehow run it. The parameters are taken from `config/adminer-admin.php`
- use `AdminerFactory::require()` in your controllers to run Adminer Admin.
- now Adminer Admin should work - if not, please make an issue

```php
<?php
require "./vendor/autoload.php";

$downloadUrl = 'https://github.com/vrana/adminer/releases/download/v4.3.1/editor-4.3.1-mysql.php';
$targetDir = './adminer-download';


// Uncomment this to download a copy of Adminer Editor from URL above an apply some patches to downloaded file
//$downloader = new \kluvi\AdminerAdmin\Base\Downloader;
//$downloader->download($downloadUrl, $targetDir);


$pluginsConfig = [
    'field_image' => [
        'baseDir' => './images/',
        'baseUrl' => 'http://localhost/images/',
    ],
];
\kluvi\AdminerAdmin\Base\AdminerFactory::require($targetDir.'/adminer.php', $database = 'test', $host = 'localhost', $username = 'root', $password = '', $pluginsConfig);
```

## Contribution

If you miss some feature or needs some help, please create an Issue.