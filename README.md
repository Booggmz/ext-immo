Test Immo extension
===================
some test extension

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add custom repo

```json
"repositories":[
    {
        "type": "git",
        "url": "https://github.com/Booggmz/ext-immo.git"
    }
]
```
to the require section of your `composer.json` file.

Then install extension

```bash
php composer.phar require booggmz/yii2-immo:dev-master
```

Then configure your yii2:
console.php
```php
    'bootstrap'           => ['log', 'immo'],
    ...
    'components' => [
        'queueProcessor'    => ['class' => \booggmz\immo\components\QueueProcessor::class],
        'operatorProcessor' => ['class' => \booggmz\immo\components\OperatorProcessor::class],
    ],
    ...
    'modules' => [
        'immo' => [
            'class' => booggmz\immo\Module::class
        ],
    ],
```

Then run migrations

 ```bash
 $ ./yii migrate --migrationPath=@vendor/booggmz/yii2-immo/src/migrations/
 ```



Usage
-----

Once the extension is installed, you can use it over console:

```
./yii help immo
```