<?php

namespace Kuhlt\ErstiFragebogen\Classes\Controllers;

use Kuhlt\ErstiFragebogen\Classes\AnswerOption;
use Kuhlt\ErstiFragebogen\Classes\Ersti;
use Kuhlt\ErstiFragebogen\Classes\Exceptions\ConfigVariableNotFoundException;
use Kuhlt\ErstiFragebogen\Classes\SiteContext;
use Kuhlt\ErstiFragebogen\Classes\Template;

class RegistrationController
{
    public static function registrationFormAction(&$context)
    {
        try {
            if (SiteContext::getContext()->config('registration_enabled')) {
                $ersti = new Ersti();

                // if form data was sent
                if (isset($_POST['ersti_form'])) {
                    $ersti->useFormData($_POST);

                    if ($ersti->validate() && $ersti->insert()) {
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
                            $ersti->getRegistrationFormVariables(),
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
            else {
                $template_variables = [
                    'title'   => 'Registrierung geschlossen',
                    'message' => 'Die Registrierung ist momentan geschlossen.',
                ];

                Template::renderTwigTemplate(
                    'message.html.twig',
                    $template_variables
                );
            }
        }
        catch (ConfigVariableNotFoundException $exception) {
            $template_variables = [
                'title'   => 'Fehler',
                'message' => 'Die Datenbank für die Registrierung ist noch nicht bereit.',
            ];

            Template::renderTwigTemplate(
                'message.html.twig',
                $template_variables
            );
        }
    }
}
