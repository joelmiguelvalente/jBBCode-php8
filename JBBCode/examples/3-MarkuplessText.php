<?php

/**
 * We load the autoloader so that the Parser is executed.
 **/
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use JBBCode\Parser;
use JBBCode\DefaultCodeDefinitionSet;

$parser = new Parser();
$parser->addCodeDefinitionSet(new DefaultCodeDefinitionSet());

$text = "[b][u]There is [i]a lot[/i] of [url=http://en.wikipedia.org/wiki/Markup_language]markup[/url] in this";
$text .= "[color=#333333]text[/color]![/u][/b]";
$parser->parse($text);

$eol = PHP_SAPI === 'cli' ? PHP_EOL : '<br>' . PHP_EOL;

echo 'Only html (original): ' . $parser->getAsHTML() . $eol;
echo 'Only text (clean): ' . $parser->getAsText() . $eol;
