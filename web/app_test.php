<?php

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

Debug::enable();
ErrorHandler::register();
ExceptionHandler::register();

$kernel = new AppKernel('test', true);
$kernel->loadClassCache();
$kernel->handle(Request::createFromGlobals())->send();