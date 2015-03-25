<?php

    $DB = new PDO('pgsql:host=localhost;dbname=library_test');

    class Book
    {
        private $title;
        private $genre;
        private $id;

        function __construct($title, $genre, $id = null)
        {
            $this->title = (string) $title;
            $this->genre = (string) $genre;
            $this->id = (int) $id;
        }

        function getTitle()
        {
            return $this->title;
        }

        function setTitle($new_title)
        {
            $this->title = (string) $new_title;
        }

        function getGenre()
        {
            return $this->genre;
        }

        function setGenre($new_genre)
        {
            $this->genre = (string) $new_genre;
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
            $statement = $GLOBALS['DB']->query("INSERT INTO books (title, genre) VALUES ('{$this->getTitle()}', '{$this->getGenre()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books");
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

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books *;");
        }

        function update($new_title, $new_genre)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("UPDATE books SET genre = '{$new_genre}' WHERE id = {$this->getId()};");
            $this->setTitle($new_title);
            $this->setGenre($new_genre);
        }

        function delete()
        {
        $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE book_id = {$this->getId()};");
        }

        static function find($search_id)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_id = $book->getId();
                if($book_id == $search_id){
                    $found_book = $book;
                }
            }
            return $found_book;
        }

        function addAuthor($author)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ('{$author->getId()}', {$this->getId()});");
        }

        function getAuthors()
        {
            $query = $GLOBALS['DB']->query("SELECT authors.* FROM books JOIN authors_books ON (books.id = authors_books.book_id) JOIN authors ON (authors_books.author_id = authors.id) WHERE books.id = {$this->getId()};");
            $returned_authors = $query->fetchAll(PDO::FETCH_ASSOC);

            $authors = array();
            foreach($returned_authors as $returned_author){
                $name = $returned_author['name'];
                $id = $returned_author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

    }

?>
