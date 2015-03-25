<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    /**
    * @backupGlobals disabled
    * $backupStaticAttribute disabled
    */

    require_once "src/Author.php";

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        function test_getName()
        {
            //Arrange

            $name= 'John Smith';
            $id = 1;
            $test_author = new Author($name, $id);

            //Act
            $result = $test_author->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function test_setName()
        {
            $name = 'John Smith';
            
        }

    }

?>
