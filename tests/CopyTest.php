<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    /**
    * @backupGlobals disabled
    * $backupStaticAttribute disabled
    */

    require_once "src/Copy.php";
    require_once "src/Patron.php";

    class CopyTest extends PHPUnit_Framework_TestCase
    {
        private $book_id;
        private $id;

        protected function tearDown()
        {
            Patron::deleteAll();
            Copy::deleteAll();
        }

        function test_getBookId()
        {
            $title = 'Faust';
            $genre = 'Horror';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();

            $book_id = $test_book->getId();
            $id = 2;
            $test_copy = new Copy($book_id, $id);

            $result = $test_copy->getBookId();

            $this->assertEquals(true, is_numeric($result));
        }

        function test_setBookId()
        {
            $title = 'Faust';
            $genre = 'Horror';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();

            $book_id = $test_book->getId();
            $id = 2;
            $test_copy = new Copy($book_id, $id);

            $test_copy->setBookId(3);
            $result = $test_copy->getBookId();

            $this->assertEquals(3, $result);
        }

        function test_getId()
        {
            $title = 'Faust';
            $genre = 'Horror';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();

            $book_id = $test_book->getId();
            $id = 2;
            $test_copy = new Copy($book_id, $id);

            $result = $test_copy->getId();

            $this->assertEquals(true, is_numeric($result));
        }

        function test_setId()
        {
            $title = 'Faust';
            $genre = 'Horror';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();

            $book_id = $test_book->getId();
            $id = 2;
            $test_copy = new Copy($book_id, $id);

            $test_copy->setId(3);
            $result = $test_copy->getId();

            $this->assertEquals(3, $result);
        }

        function test_save()
        {
            $title = 'Faust';
            $genre = 'Horror';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();

            $book_id = $test_book->getId();
            $id = 1;
            $test_copy = new Copy($book_id, $id);

            $test_copy->save();
            $result = Copy::getAll();

            $this->assertEquals([$test_copy], $result);
        }

        function test_update()
        {
            $title = 'Faust';
            $genre = 'Horror';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();

            $book_id = $test_book->getId();
            $id = 1;
            $test_copy = new Copy($book_id, $id);
            $test_copy->save();

            $new_book_id = 2;

            $test_copy->update($new_book_id);

            $this->assertEquals([2], [$test_copy->getBookId()]);
        }

        function test_delete()
        {
            $title = 'Faust';
            $genre = 'Horror';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();

            $book_id = $test_book->getId();
            $id = 1;
            $test_copy = new Copy($book_id, $id);
            $test_copy->save();

            $first_last = 'Otto';
            $phone = '555-555-5543';
            $id2 = 2;
            $test_patron = new Patron($first_last, $phone, $id2);
            $test_patron->save();

            $test_copy->addPatron($test_patron);
            $test_copy->delete();

            $this->assertEquals([], $test_copy->getPatrons());
        }

        function test_find()
        {
            $title = 'Faust';
            $genre = 'Horror';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();

            $book_id = $test_book->getId();
            $id = 1;
            $test_copy = new Copy($book_id, $id);
            $test_copy->save();

            $book_id2 = 2;
            $id2 = 1;
            $test_copy2 = new Copy($book_id2, $id2);
            $test_copy2->save();

            $result = Copy::find($test_copy->getId());

            $this->assertEquals($test_copy, $result);
        }

        function test_addPatron()
        {
            $first_last = 'Jimmy Bob';
            $phone = '555-555-5544';
            $id = 2;
            $test_patron = new Patron($first_last, $phone, $id);
            $test_patron->save();

            $title = 'Faust';
            $genre = 'Horror';
            $id = 1;
            $test_book = new Book($title, $genre, $id);
            $test_book->save();

            $book_id = $test_book->getId();
            $id = 3;
            $test_copy = new Copy($book_id, $id);
            $test_copy->save();

            $test_copy->addPatron($test_patron);
            $result = $test_copy->getPatrons();

            $this->assertEquals([$test_patron], $result);
        }
    }

?>
