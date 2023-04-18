<?php

namespace Kuhlt\ErstiFragebogen\Classes\Controllers;

use Kuhlt\ErstiFragebogen\Classes\Template;

class ErrorController
{
    public static function error404Action(&$context)
    {
        header(
            $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found',
            true,
            404
        );

        $template_variables = [
            'title'   => '404 Seite nicht gefunden',
            'message' => 'Seite nicht gefunden.',
        ];

        Template::renderTwigTemplate(
            'message.html.twig',
            $template_variables
        );
    }
}
