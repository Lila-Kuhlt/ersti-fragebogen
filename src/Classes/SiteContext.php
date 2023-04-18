<?php

namespace Kuhlt\ErstiFragebogen\Classes;

use Kuhlt\ErstiFragebogen\Classes\Exceptions\ConfigVariableNotFoundException;
use Symfony\Component\Dotenv\Dotenv;

class SiteContext
{
    private static $context = null;

    /**
     * @var array $config
     */
    private $config;

    /**
     * @var \PDO $database_connection
     */
    private $database_connection;

    /**
     * @var bool $is_admin
     */
    private $is_admin;

    private function __construct()
    {
        $this->config = [];
        $this->is_admin = false;

        if (file_exists(__DIR__ . '/../../.env')) {
            $dotenv = new Dotenv();
            $dotenv->load(__DIR__ . '/../../.env');
        }

        // simple admin authentication ...
        if (isset($_SERVER['PHP_AUTH_USER'])
            && $_SERVER['PHP_AUTH_USER'] == $_SERVER['ADMIN_USER']
            && $_SERVER['PHP_AUTH_PW'] == $_SERVER['ADMIN_PASSWORD']
        ) {
            $this->is_admin = true;
        }

        // establish database connection
        $this->database_connection = new \PDO('sqlite:' . __DIR__ . '/../../data/erstis.sqlite');
        $this->database_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        try {
            $this->prepareDatabase();
        }
        catch (\PDOException $exception) {
            $template_variables = [
                'title'   => 'Fehler',
                'message' => 'Die Datenbank für die Registrierung konnte nicht vorbereitet werden.',
            ];

            Template::renderTwigTemplate(
                'message.html.twig',
                $template_variables
            );
        }
    }

    /**
     * Loads configuration data and creates a new database file
     * using the database schema if the database is uninitialized
     *
     * @return void
     * @throws \PDOException
     */
    private function prepareDatabase()
    {
        $is_database_initialized = false;

        while (!$is_database_initialized) {
            try {
                $result = $this->database_connection->query("SELECT * FROM config;");
                $database_config = $result->fetch(\PDO::FETCH_ASSOC);
                $this->config = $this->config + $database_config;

                $is_database_initialized = true;
            }
            catch (\PDOException $exception) {
                if (strpos($exception->getMessage(), 'no such table: config') !== false) {
                    $this->database_connection->exec(
                        file_get_contents(__DIR__ . '/../../data/schema.sql')
                    );
                }
                else {
                    throw $exception;
                }
            }
        }
    }

    /**
     * Singleton pattern
     *
     * @return SiteContext
     */
    public static function getContext()
    {
        if (!is_object(self::$context)) {
            self::$context = new SiteContext();
        }

        return self::$context;
    }

    /**
     * Gets a config variable by its name and returns its value
     *
     * @param mixed $name
     * @return mixed
     * @throws ConfigVariableNotFoundException
     */
    public function config($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }

        throw new ConfigVariableNotFoundException(
            "Config variable '{$name}' does not exist.",
            ConfigVariableNotFoundException::ERROR_CODE
        );
    }

    /**
     * Checks if you currently are in admin mode
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Lets you login via HTTP basic auth to access the admin mode
     */
    public function requestAdmin()
    {
        header(
            $_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized',
            true,
            401
        );

        header('WWW-Authenticate: Basic realm="Lila Pause Admin"');

        $template_variables = [
            'title'   => 'Zugriff verweigert',
            'message' => 'Zugriff verweigert!',
        ];

        Template::renderTwigTemplate(
            'message.html.twig',
            $template_variables
        );

        exit();
    }

    /**
     * Sets the config for enabled/disabled registration in the database
     * which activates or deactivates the registration form
     *
     * @return void
     */
    public function toggleRegistration()
    {
        $this->config['registration_enabled'] = !$this->config['registration_enabled'];
        $value = (int) $this->config['registration_enabled'];
        $query = "UPDATE config SET registration_enabled = $value;";
        $this->database_connection->exec($query);
    }

    /**
     * Establishes a database connection and returns the PDO
     *
     * @return \PDO
     */
    public function databaseConnect()
    {
        return $this->database_connection;
    }
}
