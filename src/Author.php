<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    class Author
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
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

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO authors (name) VALUES ('{$this->getName()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors");
            $authors = array();

            foreach($returned_authors as $returned_author) {
                $name = $returned_author['name'];
                $id = $returned_author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec('DELETE FROM authors *;');

        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
        $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE author_id = {$this->getId()};");
        }

        static function find($search_id)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author){
                $author_id = $author->getId();
                if($author_id == $search_id){
                    $found_author = $author;
                }
            }
            return $found_author;
        }

        function addBook($book)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, student_id) VALUES ({$book->getId()}, {$this->getId()});");
        }

        function getBooks()
        {
            $query = $GLOBALS['DB']->query("SELECT books.* FROM authors JOIN authors_books ON (authors.id = authors_books.author_id) JOIN books ON (authors_books.book_id = books.id) WHERE books.id = {$this->getId()};");
            $returned_books = $query->fetchAll(PDO::FETCH_ASSOC);

            $books = array();
            foreach($returned_books as $returned_book) {
                $title = $returned_book['title'];
                $genre = $returned_book['genre'];
                $id = $returned_book['id'];
                $new_book = new Book($title, $genre, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

    }

?>
