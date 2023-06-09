<?php

use Framework\Core\ClassLoader;

include_once '../../src/FrameworkBundle/core/classLoader.php';
$loader = new ClassLoader();
$loader->loadFolder(__DIR__ . '/../../src/FrameworkBundle');
include_once '../config.php';
include_once '../../src/StandardBundle/traits/bddTrait.php';
include_once '../../src/StandardBundle/traits/securityTrait.php';

use StandardBundle\Traits\BddTrait as BddTrait;

BddTrait::bddInit();
