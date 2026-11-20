<?php

namespace JBBCode;

use PHPUnit\Framework\TestCase;
use JBBCode\DefaultCodeDefinitionSet;

/**
 * Test cases for the default bbcode set.
 *
 * @author jbowens
 * @since May 2013
 */
class DefaultCodeDefinitionSetTest extends TestCase
{
    public function testGetCodeDefinitions()
    {
        $dcds = new DefaultCodeDefinitionSet();
        $definitions = $dcds->getCodeDefinitions();
        $this->assertIsArray($definitions);

        $parser = new Parser();

        $this->assertFalse($parser->codeExists('b'));
        $this->assertFalse($parser->codeExists('i'));
        $this->assertFalse($parser->codeExists('u'));
        $this->assertFalse($parser->codeExists('url', true));
        $this->assertFalse($parser->codeExists('img'));
        $this->assertFalse($parser->codeExists('img', true));
        $this->assertFalse($parser->codeExists('color', true));

        $parser->addCodeDefinitionSet($dcds);

        $this->assertTrue($parser->codeExists('b'));
        $this->assertTrue($parser->codeExists('i'));
        $this->assertTrue($parser->codeExists('u'));
        $this->assertTrue($parser->codeExists('url', true));
        $this->assertTrue($parser->codeExists('img'));
        $this->assertTrue($parser->codeExists('img', true));
        $this->assertTrue($parser->codeExists('color', true));
    }
}
