<?php

namespace Routes;

include_once 'routesAPI.php';

set_exception_handler(function ($e) {
  $message = $e->getMessage();
  $code = $e->getCode();
  include_once 'views/error.php';
});

require_once 'app/config.php';
if (!CONFIG_LOADED) {
    throw new \Exception("La configuration du site n'a pas été trouvé ou est indisponible.", 503);
}

require_once __DIR__.'/router.php';
require_once 'src/FrameworkBundle/Traits/viewTrait.php';

function get($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\AppBundle\\'.$path_to_include;
  }
  \Framework\Router::class::get($route, $path_to_include);
}
function post($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\AppBundle\\'.$path_to_include;
  }
  \Framework\Router::class::post($route, $path_to_include);       
}
function put($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\AppBundle\\'.$path_to_include;
  }
  \Framework\Router::class::put($route, $path_to_include);          
}
function patch($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\AppBundle\\'.$path_to_include;
  }
  \Framework\Router::class::patch($route, $path_to_include);              
}
function delete($route, $path_to_include) {
  if (is_string($path_to_include)) {
    $path_to_include = 'src\\AppBundle\\'.$path_to_include;
  }
  \Framework\Router::class::delete($route, $path_to_include);            
}
function any($route, $path_to_include) {
  \Framework\Router::class::any($route, $path_to_include);            
}

require_once 'src/AppBundle/Controllers/indexController.php';

// Static GET
get('/', function() { \AppBundle\controllers\indexController::indexAction(); });

any('/404','views/404.php');