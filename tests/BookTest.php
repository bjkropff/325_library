<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    /**
    * @backupGlobals disabled
    * $backupStaticAttribute disabled
    */

    require_once "src/Book.php";

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
           Book::deleteAll();
        //    Author::deleteAll();
        }

        function test_getTitle()
        {
            //Arrange

            $title = 'John Smith';
            $genre = 'Humor';
            $id = 1;
            $test_book = new Book($title, $genre, $id);

            //Act
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals($title, $result);
        }

        function test_setTitle()
        {
            $title = 'John Smith';
            $genre = 'Humor';
            $id = 1;
            $test_book = new Book($title, $genre, $id);

            $test_book->setTitle('Jane Smith');
            $result = $test_book->getTitle();

            $this->assertEquals('Jane Smith', $result);
        }

        function test_getGenre()
        {
            $title = 'John Smith';
            $genre = 'Humor';
            $id = 1;
            $test_book = new Book($title, $genre, $id);

            $result = $test_book->getGenre();

            $this->assertEquals($genre, $result);
        }

        function test_setGenre()
        {
            $title = 'John Smith';
            $genre = 'Humor';
            $id = 1;
            $test_book = new Book ($title, $genre, $id);

            $test_book->setGenre('Fantasy');
            $result = $test_book->getGenre();

            $this->assertEquals('Fantasy', $result);
        }

        function test_getId()
        {
            $title = 'John Smith';
            $genre = 'Humor';
            $id = 1;
            $test_book = new Book($title, $genre, $id);

            $result = $test_book->getId();

            $this->assertEquals(1, $result);
        }

        function test_setId()
        {
            $title = 'John Smith';
            $genre = 'Humor';
            $id = 1;
            $test_book = new Book($title, $genre, $id);

            $test_book->setId(3);
            $result = $test_book->getId();

            $this->assertEquals(3, $result);
        }

        function test_save()
        {
            $title = 'BFG';
            $genre = 'Humor';
            $id = 1;
            $test_book = new Book($title, $genre, $id);

            $test_book->save();
            $result = Book::getAll();

            $this->assertEquals([$test_book], $result);
        }

        function test_getAll()
        {
            $title = 'BFG';
            $genre = 'Humor';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();
            $title2 = 'WWATCF';
            $genre2 = 'Humor2';
            $id2 = 2;
            $test_book2 = new Book($title2, $genre, $id2);
            $test_book2->save();

            $result = Book::getAll();

            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function test_update()
        {
            $title = 'BFG';
            $genre = 'Humor';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();
            $new_title = 'FMF';
            $new_genre = 'Action';

            $test_book->update($new_title, $new_genre);

            $this->assertEquals(['FMF', 'Action'], [$test_book->getTitle(), $test_book->getGenre()]);
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

            $test_book->addAuthor($test_author);
            $test_book->delete();

            $this->assertEquals([], $test_author->getBooks());
        }

    }

?>
