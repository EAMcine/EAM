<?php

set_exception_handler(function ($e) {
  $message = $e->getMessage();
  $code = $e->getCode();
  echo $message . ' Erreur n°' . $code;
});

require_once 'app/config.php';
if (!CONFIG_LOADED) {
    throw new Exception("La configuration du site n'a pas été trouvé ou est indisponible.", 503);
}

require_once __DIR__.'/router.php';

get('/api', 'api/redirect.php');

// Static API GET
get('/api/test', 'api/test.php');