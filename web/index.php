<?php
  require_once __DIR__.'/../vendor/autoload.php';
  require_once __DIR__.'/../schema/generate.php';

  use RedBean_Facade as R;
  R::setup('mysql:host=localhost;dbname=sungroup','root','root');
  
  use Symfony\Component\HttpFoundation\Response;

  $app = new Silex\Application();
  $app['debug'] = true;

  $app->get('/',function( ) {
    return 'HELLO Silex';
  });
  
  $app->get('/html',function( ) {
    return file_get_contents("aplicacion.html");
  });
  
  $app->get('/css/img/{fl}',function( $fl ) use ($app){
		return $app->sendFile("img/{$fl}.png");
  });
  
  $app->get('/css/{fl}',function( $fl ) {
		$r = new Response();
		$r->setContent(file_get_contents("styles/{$fl}.css"));
		$r->headers->set('Content-Type', 'text/css');
		return $r;
  });
  
  $app->get('/js/{fl}',function( $fl ) {
		$r = new Response();
		$r->setContent(file_get_contents("js/{$fl}.js"));
		$r->headers->set('Content-Type', 'text/javascript');
		return $r;
  });
  
  $app->get('/json/{fl}',function( $fl ) use ($app) {
		//$r = new Response();
		//$r->setContent(file_get_contents("{$fl}.json"));
		//$r->headers->set('Content-Type', 'text/json');
		return $app->sendFile("{$fl}.json");
		//return $r;
  });
  
  $app->get('/img/{fl}',function( $fl ) use ($app) {
		return $app->sendFile("img/{$fl}.png");
  });

  $app->run();
  R::close();
?>
