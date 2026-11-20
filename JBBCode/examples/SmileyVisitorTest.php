<?php

/**
 * We load the autoloader so that the Parser is executed.
 **/
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use JBBCode\Parser;
use JBBCode\DefaultCodeDefinitionSet;
use JBBCode\visitors\SmileyVisitor;

error_reporting(E_ALL);

$parser = new Parser();
$parser->addCodeDefinitionSet(new DefaultCodeDefinitionSet());

// In case it’s used via the CLI or from the browser.
$args = $argv ?? [];

if (count($args) >= 2) {
    // Execution from the console (CLI)
    $inputText = $args[1];
} elseif (isset($_GET['text'])) {
    // Execution from a browser with parameters
    $inputText = $_GET['text'];
} else {
    // If parameters are missing in the console or browser, stop and show how to use it.
    $usage = PHP_SAPI === 'cli'
        ? "Usage: php " . ($args[0] ?? 'SmileyVisitorTest.php') . " \"bbcode string\"\n"
        : "You must provide the following as a parameter: ?text={your text :)} (e.g. ?text=Hello+world+:))\n";
    exit($usage);
}

$parser->parse($inputText);

$smileyVisitor = new SmileyVisitor();
$parser->accept($smileyVisitor);

echo $parser->getAsHTML() . "\n";
