<?php

namespace Hellpers;

use Exception;

class Pather
{
    /**
     * @var array Массив запрещенных имен в ОС windows
     */
    private static $antiNames = [
        'CON',
        'NUL',
        'AUX',
        'PRN',
        'COM1',
        'COM2',
        'COM3',
        'COM4',
        'COM5',
        'COM6',
        'COM7',
        'COM8',
        'COM9',
        'LPT1',
        'LPT2',
        'LPT3',
        'LPT4',
        'LPT5',
        'LPT6',
        'LPT7',
        'LPT8',
        'LPT9',
    ];

    /**
     * @var array Массив запрещенных символов в ОС windows
     */
    private static $antiSymbols = [
        '\\', ':', '*', '?', '"', '<', '>', '|'
    ];

    /**
     * Определяем какой метод необходимо вызвать
     * @param string $name Имя вызываемого метода
     * @param array $arguments Параметры переданный в метод
     * @return mixed Результат работы метода
     * @throws Exception
     */
    public static function __callStatic(string $name, array $arguments)
    {
        switch ($name) {
            case $name == 'strim':
                unset($name);
                return self::preplace($arguments[0], ['/^\//ui', '/\/$/ui']);
            case $name == 'lstrim':
                unset($name);
                return self::preplace($arguments[0], ['/^\//ui']);
            case $name == 'rstrim':
                unset($name);
                return self::preplace($arguments[0], ['/\/$/ui']);
            case $name == 'quote':
                unset($name);
                return self::preplace($arguments[0], ['/(.)/ui'], ['\\\$1']);
            case $name == 'upath':
                unset($name);
                if (stristr(strtolower(php_uname('s')), 'win') !== false) {
                    $replacement = ['/([^\\]|^)\\([^\\]|$)/ui'];
                } else {
                    $replacement = ['/([^\\\]|^)\\\([^\\\]|$)/ui'];
                }
                return self::preplace($arguments[0], $replacement, ['$1/$2']);
            case $name == 'sone':
                unset($name);
                return self::preplace($arguments[0], ['/\/{2,}/ui'], ['/']);
            default:
                throw new Exception(
                    "Отсутствует метод " . __CLASS__ . "::$name()"
                );
        }
    }

    /**
     * Обертка над preg_replace
     * @param string $string Строка, которую необходимо отформатировать
     * @param array $replacement Массив строк для замены 
     * @param array $subject Массив строк на которые менять
     * @return string Отформатированная строка
     */
    private static function preplace(
        string $string, array $replacement, array $subject = ['']
    ): string
    {
        $string = preg_replace($replacement, $subject, $string);

        unset($replacement);
        unset($subject);

        return $string;
    }

    /**
     * Вырезаем из имени файла/паки запрещенные символы/слова
     * @param string $string Имя
     * @param string $symbol Символ, которым запрещенные символы будут заменены
     * @return string Откорректированное имя
     */
    public static function name(string $name, string $symbol = '_'): string
    {
        $name = str_replace('/', $symbol, $name);

        if (stristr(strtolower(php_uname('s')), 'win') !== false) {
            $name = str_replace(self::$antiSymbols, $symbol, $name);
            if (in_array(strtoupper($name), self::$antiNames)) {
                $name .= $symbol;
            }
        }

        unset($symbol);

        return trim($name);
    }

    /**
     * Развернуть путь ОС
     * @param string $path Путь к ресурсу
     * @param array $mode Параметры:
     * 
     * bool $mode['upath'] - Разделители в unix стиле (default)
     * 
     * bool $mode['sone'] - Исключить дублирование слеша (default)
     * 
     * bool $mode['rstrim'] - Без разделителя в конце
     * 
     * bool $mode['trim'] - Применить к строке trim() (default)
     * 
     * @return string Развернутый путь к ресурсу
     * @throws Exception
     */
    public static function expath(string $path, array $mode = []): string
    {
        $mode = [
            'upath'   => (bool)($mode['upath']  ?? true),
            'sone'    => (bool)($mode['sone']   ?? true),
            'rstrim'  => (bool)($mode['rstrim'] ?? false),
            'trim'    => (bool)($mode['trim']   ?? true),
        ];

        if ($mode['trim']) {
            $path = trim($path);
        }

        if ($mode['upath']) {
            $path = self::upath($path);
        }

        if ($mode['sone']) {
            $path = self::sone($path);
        }

        if ($mode['rstrim']) {
            $path = self::rstrim($path);
        }

        unset($mode);

        $parts = explode('/', $path);
        $npath = [];

        foreach ($parts as $part) {
            if ($part == '.') {
                unset($part);
                continue;
            } elseif ($part == '..') {
                if (count($npath) < 2) {
                    throw new Exception("Некорректный путь: $path");
                } else {
                    array_pop($npath);
                    unset($part);
                    continue;
                }
            } else {
                $npath[] = $part;
            }
            unset($part);
        }

        unset($path, $parts);

        $npath = implode('/', $npath);

        return $npath;
    }

}
