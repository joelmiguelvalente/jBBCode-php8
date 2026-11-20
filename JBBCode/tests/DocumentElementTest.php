<?php

namespace JBBCode;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DocumentElementTest extends TestCase
{
	/**
	 * @var DocumentElement
	 */
	private $_documentElement;

	protected function setUp(): void
	{
		$this->_documentElement = new DocumentElement();
	}

	public function testGetTagName(): void
	{
		$this->assertEquals('Document', $this->_documentElement->getTagName());
	}

	/**
	 * @template T of object
	 * @param class-string<T> $className
	 * @return \PHPUnit\Framework\MockObject\MockObject&T
	 */
	private function buildMock(string $className, string $method, mixed $will): MockObject
	{
		$mock = $this->getMockBuilder($className)
			->onlyMethods([$method])
			->getMock();
		$mock->expects($this->once())
			->method($method)
			->willReturn($will);
		return $mock;
	}

	public function testGetAsText(): void
	{
		$this->assertEmpty($this->_documentElement->getAsText());
		$mock = $this->buildMock(
			className: \JBBCode\ElementNode::class,
			method: 'getAsText',
			will: 'foo'
		);
		$this->_documentElement->addChild($mock);
		$this->assertEquals('foo', $this->_documentElement->getAsText());
	}

	public function testGetAsHTML(): void
	{
		$this->assertEmpty($this->_documentElement->getAsHTML());
		$mock = $this->buildMock(
			className: \JBBCode\ElementNode::class,
			method: 'getAsHTML',
			will: '<strong>foo</strong>'
		);
		$this->_documentElement->addChild($mock);
		$this->assertEquals('<strong>foo</strong>', $this->_documentElement->getAsHTML());
	}

	public function testGetAsBBCode(): void
	{
		$this->assertEmpty($this->_documentElement->getAsBBCode());
		$mock = $this->buildMock(
			className: \JBBCode\ElementNode::class,
			method: 'getAsBBCode',
			will: '[b]foo[/b]'
		);
		$this->_documentElement->addChild($mock);
		$this->assertEquals('[b]foo[/b]', $this->_documentElement->getAsBBCode());
	}

	public function testAccept(): void
	{
		$mock = $this->createMock(\JBBCode\NodeVisitor::class);
		$mock->expects($this->once())
			->method('visitDocumentElement')
			->with($this->equalTo($this->_documentElement));
		$mock->expects($this->never())
			->method('visitTextNode');
		$mock->expects($this->never())
			->method('visitElementNode');
		$this->_documentElement->accept($mock);
	}

	public function testIsTextNode()
	{
		$this->assertFalse($this->_documentElement->isTextNode());
	}
}
