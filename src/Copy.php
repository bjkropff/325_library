<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library');

    /**
    * @backupGlobals disabled
    * $backupStaticAttribute disabled
    */

    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Patron.php";
    require_once __DIR__."/../src/Book.php";

    class Copy
    {
        private $book_id;
        private $id;

        function __construct($book_id, $id)
        {
            $this->book_id = (int) $book_id;
            $this->id = (int) $id;
        }

        function getBookId()
        {
            return $this->book_id;
        }

        function setBookId($new_book_id)
        {
            $this->book_id = (int) $new_book_id;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO copies (book_id) VALUES ('{$this->getBookId()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $copies = array();
            foreach($returned_copies as $copy){
                $book_id = $copy['book_id'];
                $id = $copy['id'];
                $new_copy = new Copy($book_id, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies *;");
        }

        function update($new_book_id)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET book_id = '{$new_book_id}' WHERE id = {$this->getId()};");
            $this->setBookId($new_book_id);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE copy_id = {$this->getId()};");
        }

        static function find($search_id)
        {
            $found_copy = null;
            $copies = Copy::getAll();
            foreach($copies as $copy) {
                $book_id = $copy->getId();
                if($book_id == $search_id) {
                    $found_copy = $copy;
                }
            }
            return $found_copy;
        }

        function addPatron($patron)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (patron_id, copy_id) VALUES ({$patron->getId()}, {$this->getId()});");
        }

        function getPatrons()
        {
            $query = $GLOBALS['DB']->query("SELECT patrons.* FROM copies JOIN checkouts ON (copies.id = checkouts.copy_id) JOIN patrons ON (checkouts.patron_id = patrons.id) WHERE copies.id = {$this->getId()};");
            $returned_patrons = $query->fetchAll(PDO::FETCH_ASSOC);

            $patrons = array();
            foreach($returned_patrons as $returned_patron) {
                $first_last = $returned_patron['first_last'];
                $phone = $returned_patron['phone'];
                $id = $returned_patron['id'];
                $new_patron = new Patron($first_last, $phone, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }
    }
?>
