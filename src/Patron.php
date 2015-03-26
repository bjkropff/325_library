<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library');

    /**
    * @backupGlobals disabled
    * $backupStaticAttribute disabled
    */

    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";

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

        function update($new_first_last, $new_phone)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET patron = '{$new_first_last}' WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("UPDATE patrons SET phone = '{$new_phone}' WHERE id = {$this->getId()};");
            $this->setFirstLast($new_first_last);
            $this->setPhone($new_phone);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE patron_id = {$this->getId()};");
        }

        static function find($search_id)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_id = $patron->getId();
                if($patron_id == $search_id) {
                    $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        function addCopy($copy)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (copy_id, patron_id) VALUES ({$copy->getId()}, {$this->getId()});");
        }

        function getCopies()
        {
            $query = $GLOBALS['DB']->query("SELECT copies.* FROM patrons JOIN checkouts ON (patrons.id = checkouts.patron_id) JOIN copies ON (checkouts.copy_id = copies.id) WHERE patrons.id = {$this->getId()};");
            $returned_copies = $query->fetchAll(PDO::FETCH_ASSOC);

            $copies = array();
            foreach($returned_copies as $returned_copy) {
                $book_id = $returned_copy['book_id'];
                $id = $returned_copy['id'];
                $new_copies = new Copy($book_id, $id);
                array_push($copies, $new_copies);
            }
            return $copies;
        }
    }
?>
