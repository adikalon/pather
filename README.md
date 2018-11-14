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

// Исключить дублирование слеша
$string = '/path//to///folder';
echo Pather::sone($string) . PHP_EOL;

// Развертывание пути
$string = 'C:\path\to\..\folder';
$params = [
    'upath'  => true,  // Разделители в unix стиле (default)
    'sone'   => true,  // Исключить дублирование слеша (default)
    'rstrim' => false, // Без разделителя в конце
    'trim'   => true,  // Применить к строке trim() (default)
];
echo Pather::expath($string, $params) . PHP_EOL;
```