<?php

namespace RoutesAPI;

set_exception_handler(function ($e) {
  $message = $e->getMessage();
  $code = $e->getCode();
  echo $code.' | '.$message;
});

require_once 'app/config.php';
if (!CONFIG_LOADED) {
    throw new \Exception("La configuration du site n'a pas été trouvé ou est indisponible.", 503);
}

require_once __DIR__.'/router.php';

function get($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\ApiBundle\\'.$path_to_include;
  }
  \Framework\Router::class::get('/api'.$route, $path_to_include);
}
function post($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\ApiBundle\\'.$path_to_include;
  }
  \Framework\Router::class::post('/api'.$route, $path_to_include);       
}
function put($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\ApiBundle\\'.$path_to_include;
  }
  \Framework\Router::class::put('/api'.$route, $path_to_include);          
}
function patch($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\ApiBundle\\'.$path_to_include;
  }
  \Framework\Router::class::patch('/api'.$route, $path_to_include);              
}
function delete($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\ApiBundle\\'.$path_to_include;
  }
  \Framework\Router::class::delete('/api'.$route, $path_to_include);            
}

get('', 'Controllers/redirectController.php');

// Static API GET
get('/test', 'Controllers/testController.php');

\Framework\Router::class::any('/api/$path', function($path) {
  throw new \Exception("La requête demandée n'a pas été trouvée ou n'est pas disponible : $path", 404);
});