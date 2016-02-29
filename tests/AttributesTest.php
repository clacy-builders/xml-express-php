<?php

namespace ML_Express\Tests;

require_once __DIR__ . '/../src/Markup.php';
require_once __DIR__ . '/../src/Attributes.php';
require_once __DIR__ . '/../src/Xml.php';
require_once __DIR__ . '/classes.php';

use ML_Express\Attributes;
use ML_Express\Xml;

class MyAttributes extends Attributes
{
	public function setAttrib($name, $value)
	{
		parent::setAttrib($name, $value);
		return $this;
	}

	public function setComplexAttrib($name, $value, $delimiter = ' ', $check = false)
	{
		parent::setComplexAttrib($name, $value, $delimiter, $check);
		return $this;
	}

	public function setBooleanAttrib($name, $value = true, $comparisonAttribute = null)
	{
		parent::setBooleanAttrib($name, $value, $comparisonAttribute);
		return $this;
	}

	public function setAttributes($attributes)
	{
		parent::setAttributes($attributes);
		return $this;
	}
}

class AttributesTest extends \PHPUnit_Framework_TestCase
{
	public function attribProvider()
	{
		return array(
				// setAttrib()
				array(
						self::attr()->setAttrib('a', 'lorem ipsum'), ' a="lorem ipsum"'
				),
				array(
						self::attr(true)->setAttrib('a', 'lorem ipsum'), ' a="lorem ipsum"'
				),
				array(
						self::attr()->setAttrib('a', ''), ' a=""'
				),
				array(
						self::attr(true)->setAttrib('a', ''), ' a=""'
				),
				array(
						self::attr()->setAttrib('a', 0), ' a="0"'
				),
				array(
						self::attr(true)->setAttrib('a', 0), ' a="0"'
				),
				array(
						self::attr()->setAttrib('a', 1), ' a="1"'
				),
				array(
						self::attr(true)->setAttrib('a', 1), ' a="1"'
				),
				array(
						self::attr()->setAttrib('a', false), ''
				),
				array(
						self::attr(true)->setAttrib('a', false), ''
				),
				array(
						self::attr()->setAttrib('a', true), ' a="a"'
				),
				array(
						self::attr(true)->setAttrib('a', true), ' a'
				),
				array(
						self::attr()->setAttrib('a', null), ''
				),
				array(
						self::attr(true)->setAttrib('a', null), ''
				),
				array(
						self::attr()
								->setAttrib('a', null)
								->setAttrib('b', 'ipsum')
								->setAttrib('a', 'lorem'),
						' a="lorem" b="ipsum"'
				),
				// setComplexAttrib()
				array(
						self::attr()
								->setComplexAttrib('a', 'lorem ipsum dolor lorem')
								->setComplexAttrib('a', 'ipsum dolores'),
						' a="lorem ipsum dolor lorem ipsum dolores"'
				),
				array(
						self::attr()
								->setComplexAttrib('a', 'lorem ipsum dolor lorem')
								->setComplexAttrib('a', 'ipsum dolores ipsum', ' ', true),
						' a="lorem ipsum dolor dolores"'
				),
				array(
						self::attr()
								->setComplexAttrib('a', 'lorem ipsum dolor lorem')
								->setComplexAttrib('a', ['ipsum', 'dolores']),
						' a="lorem ipsum dolor lorem ipsum dolores"'
				),
				array(
						self::attr()
								->setComplexAttrib('a', 'lorem ipsum dolor lorem')
								->setComplexAttrib('a', ['ipsum', 'dolores', 'ipsum'], ' ', true),
						' a="lorem ipsum dolor dolores"'
				),
				array(
						self::attr()
								->setComplexAttrib('a', 'lorem ipsum dolor')
								->setComplexAttrib('a', true),
						' a="a"'
				),
				array(
						self::attr(true)
								->setComplexAttrib('a', 'lorem ipsum dolor')
								->setComplexAttrib('a', true, ' ', true),
						' a'
				),
				array(
						self::attr()
								->setComplexAttrib('a', 'lorem ipsum dolor')
								->setComplexAttrib('a', false),
						''
				),
				array(
						self::attr(true)
								->setComplexAttrib('a', 'lorem ipsum dolor')
								->setComplexAttrib('a', false, ' ', true),
						''
				),
				array(
						self::attr()
								->setComplexAttrib('a', 'lorem ipsum dolor')
								->setComplexAttrib('a', null),
						' a="lorem ipsum dolor"'
				),
				array(
						self::attr()
								->setComplexAttrib('a', 'lorem ipsum dolor')
								->setComplexAttrib('a', null, ' ', true),
						' a="lorem ipsum dolor"'
				),
				array(
						self::attr()
								->setComplexAttrib('x', null),
						''
				),
				array(
						self::attr()
								->setComplexAttrib('y', null, ' ', true),
						''
				),
				array(
						self::attr()
								->setComplexAttrib('a', null)
								->setComplexAttrib('b', 'ipsum')
								->setComplexAttrib('a', 'lorem'),
						' a="lorem" b="ipsum"'
				),
				array(
						self::attr(true)
								->setAttrib('a', 'lorem')
								->setBooleanAttrib('b'),
						' a="lorem" b'
				),
				array(
						self::attr(true)
								->setAttrib('a', 'lorem')
								->setBooleanAttrib('b', false),
						' a="lorem"'
				),
				array(
						self::attr(true)
								->setAttrib('a', 'lorem')
								->setBooleanAttrib('b', 'lorem', 'a'),
						' a="lorem" b'
				),
				array(
						self::attr(true)
								->setAttrib('a', 'lorem')
								->setBooleanAttrib('b', 'ipsum', 'a'),
						' a="lorem"'
				),
				array(
						self::attr(true)
								->setAttrib('a', 'lorem')
								->setBooleanAttrib('b', ['lorem', 'ipsum'], 'a'),
						' a="lorem" b'
				),
				array(
						self::attr(true)
								->setAttrib('a', 'lorem')
								->setBooleanAttrib('b', [true, 'ipsum'], 'a'),
						' a="lorem"'
				),
				// setAttributes()
				array(
						self::attr()->setAttributes(
								['a' => 'lorem',  'b' => true, 'c' => false, 'd' => null]),
						' a="lorem" b="b"'
				)
		);
	}

	/**
	 * @dataProvider attribProvider
	 */
	public function testAttrib($attributes, $expected)
	{
		$this->assertEquals($expected, $attributes->str());
	}

	private static function attr($sgmlMode = false)
	{
		return new MyAttributes($sgmlMode ? new TestSgml('e') : new TestXml('e'));
	}
}