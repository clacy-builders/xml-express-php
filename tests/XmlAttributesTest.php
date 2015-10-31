<?php

namespace ML_Express;

require_once __DIR__ . '/../src/XmlAttributes.php';
require_once __DIR__ . '/../src/Xml.php';
require_once __DIR__ . '/classes.php';

class Attributes extends XmlAttributes
{
	public function setAttrib($name, $value)
	{
		parent::setAttrib($name, $value);
		return $this;
	}

	public function altAttrib($name, $value, $delimiter = ' ', $check = false)
	{
		parent::altAttrib($name, $value, $delimiter, $check);
		return $this;
	}
}

class XmlAttributesTest extends \PHPUnit_Framework_TestCase
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
				// altAttrib()
				array(
						self::attr()
								->altAttrib('a', 'lorem ipsum dolor lorem')
								->altAttrib('a', 'ipsum dolores'),
						' a="lorem ipsum dolor lorem ipsum dolores"'
				),
				array(
						self::attr()
								->altAttrib('a', 'lorem ipsum dolor lorem')
								->altAttrib('a', 'ipsum dolores ipsum', ' ', true),
						' a="lorem ipsum dolor dolores"'
				),
				array(
						self::attr()
								->altAttrib('a', 'lorem ipsum dolor lorem')
								->altAttrib('a', ['ipsum', 'dolores']),
						' a="lorem ipsum dolor lorem ipsum dolores"'
				),
				array(
						self::attr()
								->altAttrib('a', 'lorem ipsum dolor lorem')
								->altAttrib('a', ['ipsum', 'dolores', 'ipsum'], ' ', true),
						' a="lorem ipsum dolor dolores"'
				),
				array(
						self::attr()
								->altAttrib('a', 'lorem ipsum dolor')
								->altAttrib('a', true),
						' a="a"'
				),
				array(
						self::attr(true)
								->altAttrib('a', 'lorem ipsum dolor')
								->altAttrib('a', true, ' ', true),
						' a'
				),
				array(
						self::attr()
								->altAttrib('a', 'lorem ipsum dolor')
								->altAttrib('a', false),
						''
				),
				array(
						self::attr(true)
								->altAttrib('a', 'lorem ipsum dolor')
								->altAttrib('a', false, ' ', true),
						''
				),
				array(
						self::attr()
								->altAttrib('a', 'lorem ipsum dolor')
								->altAttrib('a', null),
						' a="lorem ipsum dolor"'
				),
				array(
						self::attr()
								->altAttrib('a', 'lorem ipsum dolor')
								->altAttrib('a', null, ' ', true),
						' a="lorem ipsum dolor"'
				),
				array(
						self::attr()
								->altAttrib('x', null),
						''
				),
				array(
						self::attr()
								->altAttrib('y', null, ' ', true),
						''
				),
				array(
						self::attr()
								->altAttrib('a', null)
								->altAttrib('b', 'ipsum')
								->altAttrib('a', 'lorem'),
						' a="lorem" b="ipsum"'
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
		return new Attributes($sgmlMode ? new TestSgml('e') : new TestXml('e'));
	}
}