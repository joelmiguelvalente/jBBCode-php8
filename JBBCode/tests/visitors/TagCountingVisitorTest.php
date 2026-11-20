<?php

require_once dirname(dirname(__DIR__)) . '/visitors/TagCountingVisitor.php';

class TagCountingVisitorTest extends \PHPUnit\Framework\TestCase
{
    /** @var  JBBCode\visitors\TagCountingVisitor */
    protected $_tagCountingVisitor;

    protected function setUp(): void
    {
        $this->_tagCountingVisitor = new JBBCode\visitors\TagCountingVisitor();
    }

    public function testVisitTextNode()
    {
        $mock = $this->getMockBuilder(\JBBCode\TextNode::class)
            ->onlyMethods(['accept'])
            ->setConstructorArgs([''])
            ->getMock();
        $mock->expects($this->never())
                ->method('accept');
        $this->_tagCountingVisitor->visitTextNode($mock);
    }

    /**
     * @covers JBBCode\visitors\TagCountingVisitor::getFrequency()
     * @covers JBBCode\visitors\TagCountingVisitor::visitElementNode()
     */
    public function testVisitElementNode()
    {
        $childMock = $this->getMockBuilder(\JBBCode\ElementNode::class)
            ->onlyMethods(['accept'])
            ->getMock();
        $childMock->expects($this->once())
                  ->method('accept')
                  ->with($this->equalTo($this->_tagCountingVisitor));

        $mock = $this->getMockBuilder(\JBBCode\ElementNode::class)
            ->onlyMethods(['getChildren', 'getTagName'])
            ->getMock();
        $mock->expects($this->once())
             ->method('getChildren')
             ->willReturn(array(
                 $childMock,
             ));
        $mock->expects($this->once())
            ->method('getTagName')
            ->willReturn('foo');

        $this->assertEquals(0, $this->_tagCountingVisitor->getFrequency('foo'));

        $this->_tagCountingVisitor->visitElementNode($mock);
        $this->assertEquals(1, $this->_tagCountingVisitor->getFrequency('foo'));

        $mock = $this->getMockBuilder(\JBBCode\ElementNode::class)
            ->onlyMethods(['getChildren', 'getTagName'])
            ->getMock();
        $mock->expects($this->once())
             ->method('getChildren')
             ->willReturn(array());
        $mock->expects($this->once())
             ->method('getTagName')
             ->willReturn('foo');

        $this->_tagCountingVisitor->visitElementNode($mock);
        $this->assertEquals(2, $this->_tagCountingVisitor->getFrequency('foo'));
    }

    public function testVisitDocumentElement()
    {
        $childMock = $this->getMockBuilder(\JBBCode\ElementNode::class)
            ->onlyMethods(['accept'])
            ->getMock();
        $childMock->expects($this->once())
                  ->method('accept')
                  ->with($this->equalTo($this->_tagCountingVisitor));

        $mock = $this->getMockBuilder(\JBBCode\DocumentElement::class)
            ->onlyMethods(['getChildren'])
            ->getMock();
        $mock->expects($this->once())
             ->method('getChildren')
             ->willReturn(array(
                 $childMock,
             ));

        $this->_tagCountingVisitor->visitDocumentElement($mock);
    }
}
