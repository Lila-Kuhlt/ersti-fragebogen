<?php

namespace Kuhlt\ErstiFragebogen\Classes;

use Kuhlt\ErstiFragebogen\Classes\Exceptions\AccessViolationException;
use Kuhlt\ErstiFragebogen\Classes\Helpers\FormatHelper;

class Ersti
{
    private int $id = 0;
    private string $firstname = '';
    private string $lastname = '';
    private string $email = '';
    private string $phone = '';
    private bool $mailing_list_choice = false;
    private int $course_choice = 0;
    private int $tshirt_choice = 0;
    private int $sleeping_choice = 0;
    private int $cocktail_party_choice = 0;
    private int $scc_tour_choice = 0;
    private int $friday_special_choice = 0;
    private array $error_messages = [];

    public function useFormData( $form_data )
    {
        if (isset($form_data['ersti_id'])) {
            $this->id = (int) $form_data['ersti_id'];
        }

        if (isset($form_data['firstname'])) {
            $this->firstname = ucfirst(trim($form_data['firstname']));
        }

        if (isset($form_data['lastname'])) {
            $this->lastname = ucfirst(trim($form_data['lastname']));
        }

        if (isset($form_data['email'])) {
            $this->email = trim($form_data['email']);
        }

        if (isset($form_data['phone'])) {
            $this->phone = trim($form_data['phone']);
        }

        if (isset($form_data['mailing_list_choice'])
            && ($form_data['mailing_list_choice'] == true)
        ) {
            $this->mailing_list_choice = true;
        }

        if (isset($form_data['course_choice'])) {
            $this->course_choice = (int) $form_data['course_choice'];
        }

        if (isset($form_data['tshirt_choice'])) {
            $this->tshirt_choice = (int) $form_data['tshirt_choice'];
        }

        if (isset($form_data['sleeping_choice'])) {
            $this->sleeping_choice = (int) $form_data['sleeping_choice'];
        }

        if (isset($form_data['cocktail_party_choice'])) {
            $this->cocktail_party_choice = (int) $form_data['cocktail_party_choice'];
        }

        if (isset($form_data['scc_tour_choice'])
            && ($form_data['scc_tour_choice'] == true)
        ) {
            $this->scc_tour_choice = true;
        }

        if (isset($form_data['friday_special_choice'])) {
            $this->friday_special_choice = (int) $form_data['friday_special_choice'];
        }
    }

    public function getRegistrationFormVariables(string $mode = 'default'): array
    {
        return [
            'ersti' => [
                'id'                  => $this->id,
                'firstname'           => $this->firstname,
                'lastname'            => $this->lastname,
                'email'               => $this->email,
                'phone'               => $this->phone,
                'mailingListChoice'   => $this->mailing_list_choice,
                'courseChoice'        => $this->course_choice,
                'sleepingPlaceChoice' => $this->sleeping_choice,
                'tshirtChoice'        => $this->tshirt_choice,
                'cocktailPartyChoice' => $this->cocktail_party_choice,
                'sccTourChoice'       => $this->scc_tour_choice,
                'fridaySpecialChoice' => $this->friday_special_choice,
            ],
            'meta' => [
                'errorMessages' => $this->error_messages,
                'mode'          => $mode,
            ],
        ];
    }

    /**
     * Validates the Ersti data given by the create or update form
     *
     * @return bool
     */
    public function validate()
    {
        if (empty($this->firstname)) {
            $this->error_messages[] = 'Dein Vorname wird benötigt.';
        }

        if (empty($this->lastname)) {
            $this->error_messages[] = 'Dein Nachname wird benötigt.';
        }

        if (strlen($this->firstname) > 32) {
            $this->error_messages[] = 'Die maximale Länge für den Vornamen ist 32 Zeichen.';
        }

        if (strlen($this->lastname) > 32) {
            $this->error_messages[] = 'Die maximale Länge für den Nachnamen ist 32 Zeichen.';
        }

        if (
            !empty($this->email)
            && (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
        ) {
            $this->error_messages[] = 'Die E-Mail-Adresse hat kein gültiges Format.';
        }

        if (strlen($this->email) > 64) {
            $this->error_messages[] = 'Die maximale Länge für E-Mail-Adressen ist 64 Zeichen.';
        }

        if (
            $this->mailing_list_choice
            && empty($this->email)
        ) {
            $this->error_messages[] = 'Deine E-Mail-Adresse wird benötigt, wenn du auf die Mailingliste gesetzt werden möchtest.';
        }

        if (strlen($this->phone) > 32) {
            $this->error_messages[] = 'Die maximale Länge für Telefonnummern ist 32 Zeichen.';
        }

        // If no error messages exist, the Ersti data is valid
        return (count($this->error_messages) === 0);
    }

    /**
     * Applies data from the databae to an Ersti object
     *
     * @param $id the id of an Ersti in the database
     * @return void
     */
    public function loadFromDatabse($id)
    {
        $database = SiteContext::getContext()->databaseConnect();
        $query = "SELECT * FROM erstis WHERE id = $id";
        $result = $database->query($query)->fetch(\PDO::FETCH_ASSOC);

        $this->id = $result['id'];
        $this->firstname = $result['firstname'];
        $this->lastname = $result['lastname'];
        $this->email = $result['email'] ?? '';
        $this->phone = $result['phone'] ?? '';
        $this->mailing_list_choice = $result['mailing_list_choice'];
        $this->course_choice = $result['course_choice'];
        $this->sleeping_choice = $result['sleeping_choice'];
        $this->cocktail_party_choice = $result['cocktail_party_choice'];
        $this->scc_tour_choice = $result['scc_tour_choice'];
        $this->friday_special_choice = $result['friday_special_choice'];
    }

    /**
     * Deletes an Ersti record from the database
     *
     * @param int $id the id of an Ersti in the database
     * @return void
     */
    public static function delete($id)
    {
        $database = SiteContext::getContext()->databaseConnect();
        $query =  "DELETE FROM erstis WHERE id = $id";
        $database->exec($query);
    }

    /**
     * Inserts a new Ersti records into the database
     *
     * @return bool true on success, false on error
     */
    public function insert()
    {
        $database = SiteContext::getContext()->databaseConnect();
        $query =  "INSERT INTO erstis (firstname, lastname, email, phone, mailing_list_choice, course_choice, tshirt_choice, sleeping_choice, cocktail_party_choice, scc_tour_choice, friday_special_choice) ";
        $query .= "VALUES (:firstname, :lastname, :email, :phone, :mailing_list_choice, :course_choice, :tshirt_choice, :sleeping_choice, :cocktail_party_choice, :scc_tour_choice, :friday_special_choice);";
        $statement = $database->prepare($query);
        $this->bindParams($statement);

        try {
            $statement->execute();
            return true;
        }
        catch (\PDOException $exception) {
            if (strpos($exception->getMessage(), 'constraint violation')
                && strpos($exception->getMessage(), 'name')
            ) {
                $this->error_messages[] = 'Es existiert bereits ein Ersti mit dem selben Namen.';
            }
            else if (strpos($exception->getMessage(), 'constraint violation')
                && strpos($exception->getMessage(), 'email')
            ) {
                $this->error_messages[] = 'Die E-Mail-Adresse wird bereits verwendet.';
            }
            else {
                $this->error_messages[] = $exception->getMessage();
            }

            return false;
        }
    }

    /**
     * Update an Ersti record in the database
     *
     * @return bool true on success, false on error
     * @throws AccessViolationException
     */
    public function update()
    {
        if (!SiteContext::getContext()->isAdmin()) {
            throw new AccessViolationException(
                'Access violation: You must be admin.',
                AccessViolationException::ERROR_CODE
            );
        }

        $database = SiteContext::getContext()->databaseConnect();
        $query =  "UPDATE erstis SET firstname=:firstname, lastname=:lastname, email=:email, phone=:phone, mailing_list_choice=:mailing_list_choice, course_choice=:course_choice, tshirt_choice=:tshirt_choice, sleeping_choice=:sleeping_choice, cocktail_party_choice=:cocktail_party_choice, scc_tour_choice=:scc_tour_choice, friday_special_choice=:friday_special_choice WHERE id=:id;";

        $statement = $database->prepare($query);
        $this->bindParams($statement);
        $statement->bindParam(":id", $this->id);

        try {
            $statement->execute();
            return true;
        }
        catch (\PDOException $exception) {
            $this->error_messages[] = $exception->getMessage();
            return false;
        }
    }

    private function bindParams(&$statement)
    {
        $this->bindString($statement, ":firstname", $this->firstname);
        $this->bindString($statement, ":lastname", $this->lastname);
        $this->bindString($statement, ":email", $this->email);
        $this->bindString($statement, ":phone", $this->phone);
        $statement->bindParam(":mailing_list_choice", $this->mailing_list_choice, \PDO::PARAM_BOOL);
        $statement->bindParam(":course_choice", $this->course_choice, \PDO::PARAM_INT);
        $statement->bindParam(":tshirt_choice", $this->tshirt_choice, \PDO::PARAM_INT);
        $statement->bindParam(":sleeping_choice", $this->sleeping_choice, \PDO::PARAM_INT);
        $statement->bindParam(":cocktail_party_choice", $this->cocktail_party_choice, \PDO::PARAM_INT);
        $statement->bindParam(":scc_tour_choice", $this->scc_tour_choice, \PDO::PARAM_BOOL);
        $statement->bindParam(":friday_special_choice", $this->friday_special_choice, \PDO::PARAM_INT);
    }

    private function bindString(&$statement, $id, &$string)
    {
        if (empty($string)) {
            $statement->bindValue($id, null, \PDO::PARAM_NULL);
        }
        else {
            $statement->bindParam($id, $string, \PDO::PARAM_STR);
        }
    }

    public static function getExport()
    {
        $database_connection = SiteContext::getContext()->databaseConnect();
        $query = "SELECT * FROM erstis ORDER BY lastname, firstname ASC;";
        $erstis = $database_connection->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($erstis as $id => $ersti) {
            $ersti['sum_to_pay'] = self::calculateSumToPay($ersti);
            $ersti['course_choice'] = AnswerOption::COURSE[$ersti['course_choice']];
            $ersti['tshirt_choice'] = AnswerOption::T_SHIRT[$ersti['tshirt_choice']];
            $ersti['sleeping_choice'] = AnswerOption::SLEEPING_PLACE[$ersti['sleeping_choice']];
            $ersti['cocktail_party_choice'] = AnswerOption::COCKTAIL_PARTY[$ersti['cocktail_party_choice']];
            $ersti['friday_special_choice'] = AnswerOption::FRIDAY_SPECIAL[$ersti['friday_special_choice']];

            $erstis[$id] = FormatHelper::arrayKeysToCamelcase($ersti);
        }

        $tshirts = self::getTshirtChoicesForExport($erstis);
        $cocktails = self::getCocktailChoicesForExport($erstis);
        $events = self::getEventChoicesForExport($erstis);

        $export_data = [
            'erstis'    => $erstis,
            'tshirts'   => $tshirts,
            'cocktails' => $cocktails,
            'events'    => $events,
        ];

        return $export_data;
    }

    public static function getStatistics()
    {
        $statistics = [];
        $database_connection = SiteContext::getContext()->databaseConnect();

        // amount of erstis
        $query = "SELECT COUNT(*) FROM erstis";
        $statistics['num_erstis'] = $database_connection->query($query)->fetch(\PDO::FETCH_NUM)[0];

        // course choices
        $query = "SELECT course_choice, COUNT(*) as num FROM erstis GROUP BY course_choice";
        $result = $database_connection->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        $statistics['course_choice'] = array_fill(1, count(AnswerOption::COURSE), 0);

        foreach ($result as $row) {
            $statistics['course_choice'][$row['course_choice']] = $row['num'];
        }

        // tshirt choices
        $query = "SELECT tshirt_choice, COUNT(*) as num FROM erstis GROUP BY tshirt_choice";
        $result = $database_connection->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        $statistics['tshirt_choice'] = array_fill(0, count(AnswerOption::T_SHIRT), 0);

        foreach ($result as $row) {
            $statistics['tshirt_choice'][$row['tshirt_choice']] = $row['num'];
        }

        // sleeping choices
        for ($i = 1; $i < 3; $i++) {
            $query = "SELECT id, firstname, lastname FROM erstis WHERE sleeping_choice = $i ORDER BY lastname, firstname ASC";
            $statistics['sleeping_choice'][$i] = $database_connection->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        }

        // all Erstis
        $query = "SELECT id, firstname, lastname FROM erstis ORDER BY lastname, firstname ASC";
        $statistics['erstis'] = $database_connection->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $statistics;
    }

    public static function getMailinglistMails()
    {
        $query = "SELECT email FROM erstis WHERE mailing_list_choice = 1";
        $statement = SiteContext::getContext()->databaseConnect()->query($query);
        $mails = $statement->fetchAll(\PDO::FETCH_COLUMN, 0);

        return $mails;
    }

    /**
     * Sum up all costs of choices made in the Ersti form
     *
     * @param array $erst_data a dataset from the database
     * @return int sum to pay in Cents
     */
    private static function calculateSumToPay(array $ersti_data): int
    {
        $sum_to_pay = 0;

        $sum_to_pay += AnswerOption::T_SHIRT[$ersti_data['tshirt_choice']]['price'];
        $sum_to_pay += AnswerOption::COCKTAIL_PARTY[$ersti_data['cocktail_party_choice']]['price'];
        $sum_to_pay += AnswerOption::FRIDAY_SPECIAL[$ersti_data['friday_special_choice']]['price'];

        return $sum_to_pay;
    }

    /**
     * Get tshirt choices for export
     *
     * @param array $erstis
     * @return array
     */
    private static function getTshirtChoicesForExport(array $erstis): array
    {
        $tshirt_choices = array_fill_keys(
            array_map(
                fn($option) => $option['label'],
                AnswerOption::T_SHIRT
            ),
            0
        );

        foreach ($tshirt_choices as $key => $value) {
            foreach ($erstis as $ersti) {
                if ($ersti['tshirtChoice']['label'] === $key) {
                    $tshirt_choices[$ersti['tshirtChoice']['label']] += 1;
                }
            }
        }

        $tshirts = [];

        foreach ($tshirt_choices as $key => $value) {
            $tshirts[] = [
                'label'  => $key,
                'amount' => $value,
            ];
        }

        array_shift($tshirts);

        return $tshirts;
    }

    /**
     * Get cocktail choices for export
     *
     * @param array $erstis
     * @return array
     */
    private static function getCocktailChoicesForExport(array $erstis): array
    {
        $cocktail_choices = array_fill_keys(
            array_map(
                fn($option) => $option['label'],
                AnswerOption::COCKTAIL_PARTY
            ),
            0
        );

        foreach ($cocktail_choices as $key => $value) {
            foreach ($erstis as $ersti) {
                if ($ersti['cocktailPartyChoice']['label'] === $key) {
                    $cocktail_choices[$ersti['cocktailPartyChoice']['label']] += 1;
                }
            }
        }

        $cocktails = [];

        foreach ($cocktail_choices as $key => $value) {
            $cocktails[] = [
                'label'  => $key,
                'amount' => $value,
            ];
        }

        return $cocktails;
    }

    /**
     * Get event choices for export
     *
     * @param array $erstis
     * @return array
     */
    private static function getEventChoicesForExport(array $erstis): array
    {
        $events = [
            [
                'label' => 'SCC-Tour',
                'amount' => 0,
            ],
            [
                'label' => 'Bowling',
                'amount' => 0,
            ],
            [
                'label' => 'Europabad',
                'amount' => 0,
            ],
            [
                'label' => 'Pauls Pirate Party',
                'amount' => 0,
            ],
        ];

        foreach ($erstis as $ersti) {
            if ($ersti['sccTourChoice']) {
                $events[0]['amount'] += 1;
            }

            if ($ersti['fridaySpecialChoice']['id'] == 1) {
                $events[1]['amount'] += 1;
            }

            if ($ersti['fridaySpecialChoice']['id'] == 2) {
                $events[2]['amount'] += 1;
            }

            if ($ersti['fridaySpecialChoice']['id'] == 3) {
                $events[3]['amount'] += 1;
            }
        }

        return $events;
    }
}
