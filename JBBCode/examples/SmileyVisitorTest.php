<?php

require_once dirname(__DIR__, 1) . "/Parser.php";
require_once dirname(__DIR__, 1) . "/visitors/SmileyVisitor.php";

error_reporting(E_ALL);

$parser = new JBBCode\Parser();
$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

if (count($argv) < 2) {
    die("Usage: " . $argv[0] . " \"bbcode string\"\n");
}

$inputText = $argv[1];

$parser->parse($inputText);

$smileyVisitor = new \JBBCode\visitors\SmileyVisitor();
$parser->accept($smileyVisitor);

echo $parser->getAsHTML() . "\n";
