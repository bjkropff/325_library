<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    class Patron
    {
        private $first_last;
        private $phone;
        private $id;

        function __construct($first_last, $phone, $id = null)
        {
            $this->first_last = (string) $first_last;
            $this->phone = (string) $phone;
            $this->id = (int) $id;
        }

        function getFirstLast()
        {
            return $this->first_last;
        }

        function setFirstLast($new_first_last)
        {
            $this->first_last = (string) $new_first_last;
        }

        function getPhone()
        {
            return $this->phone;
        }

        function setPhone($new_phone)
        {
            $this->phone = (string) $new_phone;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = $new_id;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO patrons (first_last, phone) VALUES ('{$this->getFirstLast()}', '{$this->getPhone()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $patrons = array();
            foreach($returned_patrons as $patron){
                $first_last = $patron['first_last'];
                $phone = $patron['phone'];
                $id = $patron['id'];
                $new_patron = new Patron($first_last, $phone, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons *;");
        }

    }
?>
