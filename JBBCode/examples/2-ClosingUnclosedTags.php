<?php

/**
 * We load the autoloader so that the Parser is executed.
 **/
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use JBBCode\Parser;
use JBBCode\DefaultCodeDefinitionSet;

$parser = new Parser();
$parser->addCodeDefinitionSet(new DefaultCodeDefinitionSet());

$text = "The bbcode in here [b]is never closed!";
$parser->parse($text);

$eol = PHP_SAPI === 'cli' ? PHP_EOL : '<br>' . PHP_EOL;

echo 'Text (original): ' . $text . $eol;
echo 'Text (parser): ' . $parser->getAsBBCode() . $eol;
