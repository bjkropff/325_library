<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    class Author
    {
        private $name;
        private $id;

        function __construct($name, $id=null)
        {
            $this->name = (string) $name;
            $this->id = (int) $id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

    }

?>
