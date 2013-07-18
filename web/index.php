<?php
  require_once __DIR__.'/../vendor/autoload.php';
  require_once __DIR__.'/../schema/generate.php';

  use RedBean_Facade as R;
  R::setup('mysql:host=localhost;dbname=sungroup','root','root');

  $app = new Silex\Application();
  $app['debug'] = true;

  $app->get('/web/',function( ) {
    return 'HELLO Silex';
  });

  $app->run();
  R::close();
?>
