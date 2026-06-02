<?php

require_once dirname(__DIR__, 1) . "/Parser.php";
require_once dirname(__DIR__, 1) . "/visitors/TagCountingVisitor.php";

error_reporting(E_ALL);

$parser = new JBBCode\Parser();
$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

if (count($argv) < 3) {
    die("Usage: " . $argv[0] . " \"bbcode string\" <tag name to check>\n");
}

$inputText = $argv[1];
$tagName = $argv[2];

$parser->parse($inputText);

$tagCountingVisitor = new \JBBCode\visitors\TagCountingVisitor();
$parser->accept($tagCountingVisitor);

echo $tagCountingVisitor->getFrequency($tagName) . "\n";
