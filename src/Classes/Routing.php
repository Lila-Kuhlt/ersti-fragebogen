<?php

namespace Kuhlt\ErstiFragebogen\Classes;

use Kuhlt\ErstiFragebogen\Classes\Controllers\AdminController;
use Kuhlt\ErstiFragebogen\Classes\Controllers\ErrorController;
use Kuhlt\ErstiFragebogen\Classes\Controllers\PrivacyPolicyController;
use Kuhlt\ErstiFragebogen\Classes\Controllers\RegistrationController;

class Routing
{
    public function callActionByRoute()
    {
        $context = self::getSiteContext();
        $page_request_uri = self::getPageRequestUri();

        match ($page_request_uri) {
            '/'                          => RegistrationController::registrationFormAction($context),
            '/registration'              => RegistrationController::registrationFormAction($context),
            '/privacy'                   => PrivacyPolicyController::privacyPolicyAction($context),
            '/admin'                     => AdminController::adminOverviewAction($context),
            '/admin/export'              => AdminController::adminExportAction($context),
            '/admin/mail_export'         => AdminController::adminMailExportAction($context),
            '/admin/edit'                => AdminController::adminEditAction($context),
            '/admin/toggle_registration' => AdminController::adminToggleRegistrationAction($context),
            default                      => ErrorController::error404Action($context),
        };
    }

    /**
     * Gets the current REQUEST_URI and removes the domain part
     *
     * @return string
     */
    private static function getPageRequestUri(): string
    {
        $page_request_uri = '/';

        if (isset($_SERVER['REQUEST_URI'])) {
            $path_bits = explode('/', $_SERVER['REQUEST_URI']);

            if (count($path_bits) > 0 && $path_bits[count($path_bits) - 1] == '') {
                unset($path_bits[count($path_bits) - 1]);
            }

            if (isset($path_bits[1]) && !empty($path_bits[1])) {
                array_shift($path_bits);
            }

            $page_request_uri = '/' . implode('/', $path_bits);
        }

        return $page_request_uri;
    }

    private static function getSiteContext(): SiteContext
    {
        return SiteContext::getContext();
    }
}
