<?php

namespace Kuhlt\ErstiFragebogen\Classes\Controllers;

use Kuhlt\ErstiFragebogen\Classes\AnswerOption;
use Kuhlt\ErstiFragebogen\Classes\Ersti;
use Kuhlt\ErstiFragebogen\Classes\SiteContext;
use Kuhlt\ErstiFragebogen\Classes\Template;

class AdminController
{
    public static function adminOverviewAction(&$context)
    {
        if (!$context->isAdmin()) {
            $context->requestAdmin();
        }

        if (isset($_POST['ersti_form'])) {
            $ersti = new Ersti();

            // if form data was sent
            if (isset($_POST['ersti_form'])) {
                $ersti->useFormData($_POST);

                if ($ersti->validate() && $ersti->update()) {
                    $template_variables = [
                        'title'   => 'Registrierung erfolgreich',
                        'message' => 'Du hast dich erfolgreich registriert. Viel Spaß während der O-Phase!',
                    ];

                    Template::renderTwigTemplate(
                        'message.html.twig',
                        $template_variables
                    );
                }
                else {
                    $answerOptions = [
                        'courses'       => AnswerOption::COURSE,
                        'tshirt'        => AnswerOption::T_SHIRT,
                        'sleepingPlace' => AnswerOption::SLEEPING_PLACE,
                        'cocktailParty' => AnswerOption::COCKTAIL_PARTY,
                        'fridaySpecial' => AnswerOption::FRIDAY_SPECIAL,
                    ];

                    $template_variables = array_merge(
                        $ersti->getRegistrationFormVariables('edit'),
                        [ 'answerOptions' => $answerOptions ]
                    );

                    Template::renderTwigTemplate(
                        'registrationForm.html.twig',
                        $template_variables
                    );
                }
            }
            else {
                $answerOptions = [
                    'courses'       => AnswerOption::COURSE,
                    'tshirt'        => AnswerOption::T_SHIRT,
                    'sleepingPlace' => AnswerOption::SLEEPING_PLACE,
                    'cocktailParty' => AnswerOption::COCKTAIL_PARTY,
                    'fridaySpecial' => AnswerOption::FRIDAY_SPECIAL,
                ];

                $template_variables = array_merge(
                    $ersti->getRegistrationFormVariables(),
                    [ 'answerOptions' => $answerOptions ]
                );

                Template::renderTwigTemplate(
                    'registrationForm.html.twig',
                    $template_variables
                );
            }
        }
        else if (isset($_POST['ersti_form_delete'])) {
            Ersti::delete($_POST['ersti_id']);
        }

        $statistics = Ersti::getStatistics();
        $courses = AnswerOption::COURSE;

        $courseChoices = [];

        foreach ($statistics['course_choice'] as $key => $value) {
            $courseChoices[] = [
                'course' => $courses[$key]['label'],
                'amount' => $value,
            ];
        }

        $isRegistrationEnabled = SiteContext::getContext()->config('registration_enabled');

        $template_variables = [
            'courseChoices'         => $courseChoices,
            'erstis'                => $statistics['erstis'],
            'numberOfErstis'        => $statistics['num_erstis'],
            'isRegistrationEnabled' => $isRegistrationEnabled,
        ];

        Template::renderTwigTemplate(
            'adminOverview.html.twig',
            $template_variables
        );
    }

    public static function adminExportAction(&$context)
    {
        if (!$context->isAdmin()) {
            $context->requestAdmin();
        }

        $export_data = Ersti::getExport();

        $template_variables = [
            'erstis'    => $export_data['erstis'],
            'tshirts'   => $export_data['tshirts'],
            'cocktails' => $export_data['cocktails'],
            'events'    => $export_data['events'],
        ];

        Template::renderTwigTemplate(
            'export.html.twig',
            $template_variables
        );
    }

    public static function adminMailExportAction(&$context)
    {
        if (!$context->isAdmin()) {
            $context->requestAdmin();
        }

        header('Content-Type: text/plain');
        header('Content-Disposition:attachment; filename="mailingliste.txt";');

        $mails = Ersti::getMailinglistMails();

        echo(implode("\n", $mails));
    }

    public static function adminEditAction(&$context)
    {
        if (!$context->isAdmin()) {
            $context->requestAdmin();
        }

        $ersti_id = $_POST['ersti_id'];

        $ersti = new Ersti();
        $ersti->loadFromDatabse($ersti_id);
        $ersti->getRegistrationFormVariables('edit');

        $answerOptions = [
            'courses'       => AnswerOption::COURSE,
            'tshirt'        => AnswerOption::T_SHIRT,
            'sleepingPlace' => AnswerOption::SLEEPING_PLACE,
            'cocktailParty' => AnswerOption::COCKTAIL_PARTY,
            'fridaySpecial' => AnswerOption::FRIDAY_SPECIAL,
        ];

        $template_variables = array_merge(
            $ersti->getRegistrationFormVariables('edit'),
            [ 'answerOptions' => $answerOptions ]
        );

        Template::renderTwigTemplate(
            'registrationForm.html.twig',
            $template_variables
        );
    }

    public static function adminToggleRegistrationAction(&$context)
    {
        if (!$context->isAdmin()) {
            $context->requestAdmin();
        }

        $context->toggleRegistration();

        $isRegistrationEnabled = SiteContext::getContext()->config('registration_enabled');

        $template_variables = [
            'title'   => 'Registrierung ' . ($isRegistrationEnabled ? 'aktiviert' : 'deaktiviert'),
            'message' => 'Die Registrierung wurde ' . ($isRegistrationEnabled ? 'aktiviert.' : 'deaktiviert.'),
        ];

        Template::renderTwigTemplate(
            'message.html.twig',
            $template_variables
        );
    }
}
