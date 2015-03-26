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

        function test_update_database()
        {
            $name = 'BFG';
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();
            $new_name = 'RD';

            $test_author->update($new_name);
            $new_author = Author::find($test_author->getId());

            $this->assertEquals(['RD'], [$new_author->getName()]);
        }

        function test_update()
        {
            $name = 'BFG';
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();
            $new_name = 'RD';

            $test_author->update($new_name);

            $this->assertEquals(['RD'], [$test_author->getName()]);
        }

        function test_delete()
        {
            $name = 'John Smith';
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            $title = 'Otto';
            $genre = 'Fantasy';
            $id2 = 2;
            $test_book = new Book($title, $genre, $id2);
            $test_book->save();

            $test_author->addBook($test_book);
            $test_author->delete();

            $this->assertEquals([], $test_book->getAuthors());
        }

        function test_find()
        {
            $name = 'John Smith';
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            $name2 = 'Jane Smith';
            $id2 = 2;
            $test_author2 = new Author($name2, $id2);
            $test_author2->save();

            $result = Author::find($test_author->getId());

            $this->assertEquals($test_author, $result);
        }


    }

?>
