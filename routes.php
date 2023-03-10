<?php

set_exception_handler(function ($e) {
  $message = $e->getMessage();
  $code = $e->getCode();
  include_once 'views/error.php';
});

require_once 'app/config.php';
if (!CONFIG_LOADED) {
    throw new Exception("La configuration du site n'a pas été trouvé ou est indisponible.", 503);
}

require_once __DIR__.'/router.php';

// Static GET
get('/', 'src/controllers/indexController.php');

any('/404','views/404.php');