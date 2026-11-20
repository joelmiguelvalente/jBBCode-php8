<?php

require_once dirname(dirname(__DIR__)) . '/visitors/SmileyVisitor.php';

class SmileyVisitorTest extends \PHPUnit\Framework\TestCase
{
    /** @var \JBBCode\visitors\SmileyVisitor */
    private $_smileyVisitor;

    protected function setUp(): void
    {
        $this->_smileyVisitor = new \JBBCode\visitors\SmileyVisitor();
    }

    public function testVisitDocumentElement()
    {
        $childMock = $this->getMockBuilder(\JBBCode\ElementNode::class)
            ->onlyMethods(['accept'])
            ->getMock();
        $childMock->expects($this->once())
                  ->method('accept')
                  ->with($this->equalTo($this->_smileyVisitor));

        $mock = $this->getMockBuilder(\JBBCode\DocumentElement::class)
            ->onlyMethods(['getChildren'])
            ->getMock();
        $mock->expects($this->once())
             ->method('getChildren')
             ->willReturn(array(
                 $childMock,
             ));

        $this->_smileyVisitor->visitDocumentElement($mock);
    }

    public function testVisitElementNode()
    {
        $builder = new \JBBCode\CodeDefinitionBuilder('foo', 'bar');
        $builder->setParseContent(false);

        $mock = $this->getMockBuilder(\JBBCode\DocumentElement::class)
            ->onlyMethods(['getChildren', 'getCodeDefinition'])
            ->getMock();
        $mock->expects($this->never())
            ->method('getChildren');
        $mock->expects($this->once())
            ->method('getCodeDefinition')
            ->willReturn(
                $builder->build()
            );
        $this->_smileyVisitor->visitElementNode($mock);

        $childMock = $this->getMockBuilder(\JBBCode\ElementNode::class)
            ->onlyMethods(['accept'])
            ->addMethods(['parseContent'])
            ->getMock();
        $childMock->expects($this->once())
                ->method('accept')
                ->with($this->equalTo($this->_smileyVisitor));

        $mock = $this->getMockBuilder(\JBBCode\DocumentElement::class)
            ->onlyMethods(['getChildren', 'getCodeDefinition'])
            ->getMock();
        $mock->expects($this->once())
            ->method('getChildren')
            ->willReturn(array(
                $childMock,
            ));
        $mock->expects($this->once())
            ->method('getCodeDefinition')
            ->willReturn($builder->setParseContent(true)->build());
        $this->_smileyVisitor->visitElementNode($mock);
    }

    public function testVisitTextNodeEmpty()
    {
        $textNode = new JBBCode\TextNode('');
        $textNode->accept($this->_smileyVisitor);
        $this->assertEmpty($textNode->getValue());
    }

    /**
     * @param $string
     * @dataProvider smileyProvider()
     */
    public function testVisitTextNode($string)
    {
        $textNode = new JBBCode\TextNode($string);
        $textNode->accept($this->_smileyVisitor);
        $this->assertNotFalse(strpos($textNode->getValue(), '<img src="/smiley.png" alt=":)" />'));
    }

    public function smileyProvider()
    {
        return array(
            array( ':)'),
            array( ':) foo'),
            array( 'foo :)'),
            array( 'foo :) bar'),
        );
    }
}
