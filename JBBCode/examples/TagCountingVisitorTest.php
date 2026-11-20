<?php

/**
 * We load the autoloader so that the Parser is executed.
 **/
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use JBBCode\Parser;
use JBBCode\DefaultCodeDefinitionSet;
use JBBCode\visitors\TagCountingVisitor;

error_reporting(E_ALL);

$parser = new Parser();
$parser->addCodeDefinitionSet(new DefaultCodeDefinitionSet());

$args = $argv ?? [];

if (count($args) >= 3) {
    // Execution from the console (CLI)
    $inputText = $args[1];
    $tagName = $args[2];
} elseif (isset($_GET['text'], $_GET['tag'])) {
    // Execution from a browser with parameters
    $inputText = $_GET['text'];
    $tagName = $_GET['tag'];
} else {
    // If parameters are missing in the console or browser, stop and show how to use it.
    $usage = PHP_SAPI === 'cli'
        ? "Usage: php " . ($args[0] ?? 'TagCountingVisitorTest.php') . " \"bbcode string\" <tag name>\n"
        : "You must provide the following as a parameter: ?text={your text}&tag={the bbcode}\n";
    exit($usage);
}

$parser->parse($inputText);

$tagCountingVisitor = new TagCountingVisitor();
$parser->accept($tagCountingVisitor);

echo $tagCountingVisitor->getFrequency($tagName) . "\n";
