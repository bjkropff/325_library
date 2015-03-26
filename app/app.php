<?php

    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/Author.php';
    require_once __DIR__.'/../src/Book.php';
    require_once __DIR__.'/../src/Copy.php';
    require_once __DIR__.'/../src/Patron.php';


    $DB = new PDO('pgsql:host=localhost;dbname=library');

    $app = new Silex\Application();

    $app['debug'] = true;

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path'=> __DIR__.'/../views'
    ));
    //creates route to homepage
    $app->get('/', function() use ($app) {
        return $app['twig']->render('index.twig', array('authors' => Author::getAll(), 'books' => Book::getAll()));
    });
    //creates route to books
    $app->get('/books', function() use ($app) {
        return $app['twig']->render('books.twig', array('books' => Book::getAll()));
    });
    //creates a route checkouts
    $app->get('/authors', function() use ($app) {
        return $app['twig']->render('authors.twig', array('authors' => Author::getAll()));
    });

    $app->get('/books/{id}', function($id) use ($app) {
      $books = Book::find($id);
      $current_author = $books->getAuthors();
      return $app['twig']->render('view_books.twig', array('books' => $books, 'authors' => $current_author[0]));
    });

    //posts info to books
    $app->post('/books', function() use ($app) {
        $title = $_POST['title'];
        $genre = $_POST['genre'];
        $new_title = new Book($title, $genre);
        $new_title->save();
        $author = $_POST['author'];
        $new_author = new Author($author);
        $new_author->save();
        $new_title->addAuthor($new_author);

        return $app['twig']->render('books.twig', array('books' => Book::getAll(), 'authors' => Author::getAll()));
    });

    $app->post("/delete_all_books", function() use ($app) {
        Book::deleteAll();
        return $app['twig']->render('books.twig', array('books' => Book::getAll()));
    });



    return $app;

?>
