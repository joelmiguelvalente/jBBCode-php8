<?php

namespace JBBCode;

use JBBCode\ElementNode;
use JBBCode\CodeDefinitionBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ElementNodeTest extends TestCase
{
    /** @var  \JBBCode\ElementNode */
    private $_elementNode;

    protected function setUp(): void
    {
        $this->_elementNode = new ElementNode();
    }

    public function testConstructor()
    {
        $this->assertNull($this->_elementNode->getCodeDefinition());
        $this->assertEmpty($this->_elementNode->getTagName());
        $this->assertEmpty($this->_elementNode->getAttribute());
        $this->assertEmpty($this->_elementNode->getChildren());
        $this->assertEmpty($this->_elementNode->getAsText());
        $this->assertEmpty($this->_elementNode->getAsHTML());
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @param list<non-empty-string> $onlyMethods
     * @return \PHPUnit\Framework\MockObject\MockObject&T
     */
    private function buildMock(string $className, string $method, mixed $will, array $onlyMethods = []): MockObject
    {
        $mock = $this->getMockBuilder($className)
            ->onlyMethods($onlyMethods ?: [$method])
            ->getMock();
        $mock->expects($this->once())
            ->method($method)
            ->willReturn($will);
        return $mock;
    }

    public function testAccept(): void
    {
        $mock = $this->getMockBuilder(\JBBCode\NodeVisitor::class)
            ->onlyMethods(['visitDocumentElement', 'visitTextNode', 'visitElementNode'])
            ->getMock();
        $mock->expects($this->never())
            ->method('visitDocumentElement');
        $mock->expects($this->never())
            ->method('visitTextNode');
        $mock->expects($this->once())
            ->method('visitElementNode')
            ->with($this->equalTo($this->_elementNode));
        $this->_elementNode->accept($mock);
    }

    public function testSetCodeDefinition(): void
    {
        $mock = $this->buildMock(
            className: \JBBCode\CodeDefinition::class,
            method: 'getTagName',
            will: 'foo',
            onlyMethods: ['getTagName']
        );
        $this->_elementNode->setCodeDefinition($mock);
        $this->assertSame($mock, $this->_elementNode->getCodeDefinition());
        $this->assertEquals('foo', $this->_elementNode->getTagName());
    }

    public function testAddChild(): void
    {
        $mock = $this->getMockBuilder(\JBBCode\ElementNode::class)
            ->onlyMethods(['setParent'])
            ->getMock();

        $mock->expects($this->once())
            ->method('setParent')
            ->with($this->equalTo($this->_elementNode));
        $this->_elementNode->addChild($mock);
        $this->assertContains($mock, $this->_elementNode->getChildren());
    }

    public function testIsTextNode(): void
    {
        $this->assertFalse($this->_elementNode->isTextNode());
    }

    public function testGetAsBBCode(): void
    {
        $builder = new CodeDefinitionBuilder('foo', 'bar');
        $codeDefinition = $builder->build();
        $this->_elementNode->setCodeDefinition($codeDefinition);
        $this->assertEquals('[foo][/foo]', $this->_elementNode->getAsBBCode());

        $this->_elementNode->setAttribute(array('bar' => 'baz'));
        $this->assertEquals('[foo bar=baz][/foo]', $this->_elementNode->getAsBBCode());

        /** @ticket 55 */
        $this->_elementNode->setAttribute(array(
            'bar' => 'baz',
            'foo' => 'bar'
        ));
        $this->assertEquals('[foo=bar bar=baz][/foo]', $this->_elementNode->getAsBBCode());
    }
}
