# Tests upgrade (PHP 8 / PHPUnit 9)

General migration notes collected while updating the test suite under `JBBCode/tests/`.

## assertInternalType()
Removed in modern PHPUnit versions (from PHPUnit 8/9).

|                  Before                   |                  After                   |
|-------------------------------------------|------------------------------------------|
| $this->assertInternalType('string', $val) |   $this->assertIsString($val)            |
| $this->assertInternalType('int', $val)    |   $this->assertIsInt($val)               |
| $this->assertInternalType('bool', $val)   |   $this->assertIsBool($val)              |
| $this->assertInternalType('array', $val)  |   $this->assertIsArray($val)             |
| $this->assertInternalType('float', $val)  |   $this->assertIsFloat($val)             |
| $this->assertInternalType('object', $val) |   $this->assertIsObject($val)            |

## getMock()
Removed in modern PHPUnit versions and replaced by `createMock()` or `getMockBuilder()`.

|                  Before                   |                  After                   |
|-------------------------------------------|------------------------------------------|
| $this->getMock('JBBCode\InputValidator')  |$this->createMock(\JBBCode\InputValidator::class)|
| $this->getMock('JBBCode\InputValidator', ['validate'])    |$this->getMockBuilder(\JBBCode\InputValidator::class)->onlyMethods(['validate'])->getMock()|

## Mocking a single method with a fixed return value
Use a small helper (e.g. `buildMock()`) wrapping `getMockBuilder()->onlyMethods(...)->getMock()`.

|                  Before                   |                  After                   |
|-------------------------------------------|------------------------------------------|
| $mock = $this->getMock('JBBCode\ElementNode', array('getAsText')); | $mock = $this->buildMock(className: \JBBCode\ElementNode::class, onlyMethods: ['getAsText'], method: 'getAsText', will: 'foo'); |
| $mock->expects($this->once())->method('getAsText')->will($this->returnValue('foo')); | (handled inside buildMock) |

Notes:
- `onlyMethods` must list the mocked method, otherwise PHPUnit will not allow setting an expectation on it.
- Fix typos in method names (e.g. `'getAsBBCOde'` -> `'getAsBBCode'`); PHP method names are case-insensitive, but the mock declaration and the expectation must match for clarity.
- `->will($this->returnValue(...))` becomes `->willReturn(...)`.

## Mocking an interface with multiple expectations
For mocks needing several expectations (`once()`/`never()`), use `createMock()` on the interface.

|                  Before                   |                  After                   |
|-------------------------------------------|------------------------------------------|
| $mock = $this->getMock('JBBCode\NodeVisitor', array('visitDocumentElement', 'visitTextNode', 'visitElementNode')); | $mock = $this->createMock(\JBBCode\NodeVisitor::class); |

Notes:
- `createMock()` mocks every method of the interface, so `expects($this->never())` can be set on any of them.
- Add `: void` return types to `setUp()` and test methods for PHP 8 compatibility.
- Update the base class: `\PHPUnit_Framework_TestCase` -> `PHPUnit\Framework\TestCase`.

## @expectedException annotation
Removed in modern PHPUnit; use `$this->expectException()` inside the test body.

|                  Before                   |                  After                   |
|-------------------------------------------|------------------------------------------|
| /** @expectedException InvalidArgumentException */ | $this->expectException(\InvalidArgumentException::class); |

Notes:
- When the tested method has typed parameters (e.g. `int $limit`), passing `null`/`false` may raise a `TypeError` instead of the expected `InvalidArgumentException`. Adjust the data provider or expected exception accordingly.

## onlyMethods([]) caveat
Passing an empty array to `onlyMethods()` makes PHPUnit mock **all** methods. Prefer `onlyMethods([$method])` or omit the call when you want the original behavior.