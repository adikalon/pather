# Pather
**hellpers/pather** - Обработка и форматирование путей, ссылок, имен файлов/папок.

## Установка:
	composer require hellpers/pather

## Пример:
```php
<?php

use Hellpers\Pather;

// Экранировать все символы
$string = 'Hello, World!';
echo Pather::quote($string) . PHP_EOL;

// Вырезаем из имени файла/паки запрещенные символы/слова
$string = 'File <Name>?';
echo Pather::name($string) . PHP_EOL;

// Обрезать слеш в конце
$string = '/path/to/folder/';
echo Pather::rstrim($string) . PHP_EOL;

// Обрезать слеш в начале
$string = '/path/to/folder/';
echo Pather::lstrim($string) . PHP_EOL;

// Обрезать слеш с обеих сторон
$string = '/path/to/folder/';
echo Pather::strim($string) . PHP_EOL;

// Заменить тип разделителя пути с windows на unix
$string = '\path\to\folder';
echo Pather::upath($string) . PHP_EOL;
```