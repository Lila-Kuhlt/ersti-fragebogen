<?php

namespace Kuhlt\ErstiFragebogen\Classes;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Template
{
    const TEMPLATES_ROOT_PATH = __DIR__ . '/../Templates';

    public static function renderTwigTemplate(string $templateName, array $templateVariables = []): void
    {
        $loader = new FilesystemLoader(self::TEMPLATES_ROOT_PATH);
        $twig = new Environment($loader, [
            'debug' => true,
        ]);
        $twig->addExtension(new \Twig\Extension\DebugExtension());

        echo $twig->render(
            $templateName,
            $templateVariables
        );
    }
}
