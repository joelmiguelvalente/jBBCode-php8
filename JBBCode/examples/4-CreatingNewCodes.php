<?php

/**
 * We load the autoloader so that the Parser is executed.
 **/
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use JBBCode\Parser;

$parser = new Parser();

$parser->addBBCode("quote", '<div class="quote">{param}</div>');
$parser->addBBCode("code", '<pre class="code">{param}</pre>', false, false, 1);
