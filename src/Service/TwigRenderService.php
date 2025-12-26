<?php

namespace App\Service;

use Inilim\Tool\Str;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigRenderService
{
    protected Environment $twig;

    function __construct()
    {
        $config = \DITag('config');
        /** @var \Inilim\Env\Env $config */
        $root = $config->getStr('root');

        $this->twig = new Environment(
            new FilesystemLoader($root . '/files/templates'),
            [
                'cache'            => $root . '/files/cache/twig',
                'debug'            => true,
                'auto_reload'      => true, // Если true, при каждом рендеринге шаблона Symfony сначала проверяет, изменился ли его исходный код с момента его компиляции. Если он изменился, шаблон автоматически компилируется заново.
                'strict_variables' => true, // Если установлено значение false, Twig будет молча игнорировать недопустимые переменные (переменные и/или атрибуты/методы, которые не существуют) и заменять их нулевым значением. Если установлено значение true, Twig вместо этого генерирует исключение (по умолчанию — false).
            ]
        );
    }

    function render(string $name, array $context = []): string
    {
        if (\str_ends_with($name, '.twig')) {
            $name = Str::beforeLast($name, '.twig');
        }
        $name = \strtr($name, [
            '.'  => '/',
            '\\' => '/',
        ]);
        $name = Str::finish($name, '.twig');
        return $this->twig->render($name, $context);
    }

    function show(string $name, array $context = []): void
    {
        echo $this->render($name, $context);
    }
}
