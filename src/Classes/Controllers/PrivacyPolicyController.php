<?php

namespace Kuhlt\ErstiFragebogen\Classes\Controllers;

use Kuhlt\ErstiFragebogen\Classes\Template;

class PrivacyPolicyController
{
    public static function privacyPolicyAction(&$context)
    {
        Template::renderTwigTemplate('privacyPolicy.html.twig');
    }
}
