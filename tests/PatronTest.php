<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    /**
    * @backupGlobals disabled
    * $backupStaticAttribute disabled
    */

    require_once "src/Patron.php";

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Patron::deleteAll();
        }

        function test_getFirstLast()
        {
            //Arrange

            $first_last = 'Jimmy Bob';
            $phone = 'Bob';
            $id = 1;
            $test_patron = new Patron($first_last, $phone, $id);

            //Act
            $result = $test_patron->getFirstLast();

            //Assert
            $this->assertEquals($first_last, $result);
        }

        function test_setFirstLast()
        {
            $first_last = 'Jimmy Bob';
            $phone = '555-555-1234';
            $id = 1;
            $test_patron = new Patron($first_last, $phone, $id);

            $test_patron->setFirstLast('Janny Bob');
            $result = $test_patron->getFirstLast();

            $this->assertEquals('Janny Bob', $result);
        }

        function test_getPhone()
        {
            $first_last = 'Jimmy Bob';
            $phone = '555-555-5544';
            $id = 1;
            $test_patron = new Patron($first_last, $phone, $id);

            $result = $test_patron->getPhone();

            $this->assertEquals($phone, $result);
        }

        function test_setPhone()
        {
            $first_last = 'Jimmy Bob';
            $phone = '555-555-5544';
            $id = 1;
            $test_patron = new Patron($first_last, $phone, $id);

            $test_patron->setPhone('Jimmy');
            $result = $test_patron->getPhone();

            $this->assertEquals('Jimmy', $result);
        }

        function test_getId()
        {
            $first_last = 'Jimmy Bob';
            $phone = '555-555-5544';
            $id = 3;
            $test_patron = new Patron($first_last, $phone, $id);

            $result = $test_patron->getId();

            $this->assertEquals($id, $result);
        }

        function test_setId()
        {
            $first_last = 'Jimmy Bob';
            $phone = '555-555-5544';
            $id = 3;
            $test_patron = new Patron($first_last, $phone, $id);

            $test_patron->setId(4);
            $result = $test_patron->getId();

            $this->assertEquals(4, $result);
        }

        function test_save()
        {
            $first_last = 'Jimmy Bob';
            $phone = '555-555-5544';
            $id = 1;
            $test_patron = new Patron($first_last, $phone, $id);

            $test_patron->save();
            $result = Patron::getAll();

            $this->assertEquals([$test_patron], $result);
        }

        function test_update()
        {
            $first_last = 'Jimmy Bob';
            $phone = '555-555-5544';
            $id = 1;
            $test_patron = new Patron($first_last, $phone, $id);
            $test_patron->save();

            $new_first_last = 'Janny Bob';
            $new_phone = '555-555-5554';

            $test_patron->update($new_first_last, $new_phone);

            $this->assertEquals(['Janny Bob', '555-555-5554'], [$test_patron->getFirstLast(), $test_patron->getPhone()]);
        }

        function test_find()
        {
            $first_last = 'Jimmy Bob';
            $phone = '555-555-5544';
            $id = 1;
            $test_patron = new Patron($first_last, $phone, $id);
            $test_patron->save();

            $first_last2 = 'Jimmy John';
            $phone2 = '555-555-5553';
            $id2 = 2;
            $test_patron2 = new Patron($first_last2, $phone2, $id2);
            $test_patron2->save();

            $result = Patron::find($test_patron->getId());

            $this->assertEquals($test_patron, $result);
        }
    }
?>
