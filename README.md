Console Runner
==============

An extension for running console commands on background in Yii framework.

Installation
------------

Add the following to `require` section of your `composer.json`:

```
"vova07/yii2-console-runner-extension": "*"
```

Then do `composer install`.

Usage
-----

Imported class:

```php
use vova07\console\ConsoleRunner;
$cr = new ConsoleRunner(['file' => '@my/path/to/yii', 'php' => 'c:\path\to\php.exe']);
$cr->run('controller/action param1 param2 ...');
```
The 'php' attribute is only needed when running under Windows. It can be omitted under Linux.

Application component:

```php
// config.php
...
components [
    'consoleRunner' => [
        'class' => 'vova07\console\ConsoleRunner',
        'file' => '@my/path/to/yii' // or an absolute path to console file
		'php' => 'c:\path\to\php.exe' // only needed when running under Windows
    ]
]
...

// some-file.php
Yii::$app->consoleRunner->run('controller/action param1 param2 ...');
```