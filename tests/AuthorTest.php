<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    /**
    * @backupGlobals disabled
    * $backupStaticAttribute disabled
    */

    require_once "src/Author.php";

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
        //   Book::deleteAll();
            Author::deleteAll();
        }

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
            $id = 1;
            $test_author = new Author($name, $id);

            $test_author->setName('Jane Smith');
            $result = $test_author->getName();

            $this->assertEquals('Jane Smith', $result);
        }

        function test_getId()
        {
            $name = 'John Smith';
            $id = 1;
            $test_author = new Author($name, $id);

            $result = $test_author->getId();

            $this->assertEquals(1, $result);
        }

        function test_setId()
        {
            $name = 'John Smith';
            $id = 1;
            $test_author = new Author($name, $id);

            $test_author->setId(3);
            $result = $test_author->getId();

            $this->assertEquals(3, $result);
        }

        function test_save()
        {
            $name = 'John Smith';
            $id = 1;
            $test_author = new Author($name, $id);

            $test_author->save();
            $result = Author::getAll();

            $this->assertEquals([$test_author], $result);
        }

        function test_getAll()
        {
            $name = 'John Smith';
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();
            $name2 = 'Jane Smith';
            $id2 = 2;
            $test_author2 = new Author($name2, $id2);
            $test_author2->save();

            $result = Author::getAll();

            $this->assertEquals([$test_author, $test_author2], $result);
        }

        function test_update()
        {

        }

    }

?>
