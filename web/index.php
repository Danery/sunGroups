<?php
<<<<<<< HEAD
  require_once __DIR__.'/../vendor/autoload.php';
=======
require_once __DIR__.'/../vendor/autoload.php';
require_once 'retrieval.php';
>>>>>>> 7b2635803f7bffd4aa37555be75ff049134e79f8

use RedBean_Facade as R;
R::setup('mysql:host=localhost;dbname=sunburst','root','root');
  
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
    $r = new Response();
    return $app->sendFile("{$fl}.json");
});
  
$app->get('/img/{fl}',function( $fl ) use ($app) {
    return $app->sendFile("img/{$fl}.png");
});

$app->get('/data',function (Request $request) use ($app) {
    $level = $request->get('level');
    $id = $request->get('id');
    if($level>=0) {
        return $app->json(getLayer($id, $level));
    }
                          
                          
    /* $groups = R::find('grupo',' parent = ? ', array( $request->get('id'))); */
    /* return $app->json($groups); */
    return $request->get('id');
});

$app->run();
R::close();


function getGroup($id) {
    $group = R::load('grupo',$id);
    return $group->export()["children"];
}

function getLayer($id, $level) {
    if (!$id) {
        $l = R::find('grupo',' grupo_id is NULL ');
        $data = [];
        foreach($l as $id) {
            $d = $id->export();
            if (($level-1)) {
                foreach($id->ownGrupo as $group) {
                    error_log("Grup");
                    $data_group = getLayer($group["id"], $level-2);
                    $d["children"][] = $data_group;
                }
            }
            $data[] = $d;
        }
        return $data;
    }
    $group = R::load('grupo',$id);
    $data = $group->export();
    $data["type"] = "group";
    $data["children"] = [];
    if ($level) {
        foreach ($group->ownGrupo as $child) {
            error_log("child:".$child);
            $data["children"][] = getLayer($child->id, $level - 1);
        }
        if (!$group->ownGroup) {
            foreach($group->ownDocument as $doc) {
                $d = $doc->export();
                $d["type"] = "doc";
                $data["children"][] = $d;
            }
        }
    }
    return $data;
}
?>
