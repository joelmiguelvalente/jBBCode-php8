<?php

/**
 * We load the autoloader so that the Parser is executed.
 **/
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use JBBCode\Parser;
use JBBCode\DefaultCodeDefinitionSet;

$parser = new Parser();
$parser->addCodeDefinitionSet(new DefaultCodeDefinitionSet());

$text = "The default codes include: [b]bold[/b], [i]italics[/i], [u]underlining[/u], ";
$text .= "[url=http://jbbcode.com]links[/url], [color=red]color![/color] and more.";

$parser->parse($text);

print $parser->getAsHtml();
