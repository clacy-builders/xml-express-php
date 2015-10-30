<?php

namespace ML_Express;

require_once __DIR__ . '/../src/XmlAttributes.php';
require_once __DIR__ . '/../src/Xml.php';
require_once __DIR__ . '/classes.php';

class XmlAttributesTest extends \PHPUnit_Framework_TestCase
{
	public function setAttribProvider()
	{
		return array(
				[null, '', false],
				[null, '', true],
				['foo', ' a="foo"', false],
				['foo', ' a="foo"', true],
				['', ' a=""', false],
				['', ' a=""', true],
				[false, '', false],
				[false, '', true],
				[true, ' a="a"', false],
				[true, ' a', true]
		);
	}

	/**
	 * @dataProvider setAttribProvider
	 */
	public function testSetAttrib($value, $expected, $sgml = false)
	{
		$xml = $sgml ? new TestSgml('e') : new TestXml('e');
		$attributes = new XmlAttributes($xml);
		$attributes->setAttrib('a', $value);
		$this->assertEquals($expected, $attributes->str());
	}

	public function resetAttribProvider()
	{
		return array(
				['lorem ipsum dolor dolores', false, ' a="lorem ipsum dolor dolores"'],
				['lorem ipsum dolor dolores', true, ' a="lorem ipsum dolor dolores"'],
				['lorem ipsum dolor ipsum', false, ' a="lorem ipsum dolor ipsum"'],
				['lorem ipsum dolor ipsum', true, ' a="lorem ipsum dolor"'],
				[['lorem', 'ipsum', 'dolor', 'dolores'], false, ' a="lorem ipsum dolor dolores"'],
				[['lorem', 'ipsum dolor', 'dolores'], true, ' a="lorem ipsum dolor dolores"'],
				[['lorem', 'ipsum', 'dolor', 'ipsum'], false, ' a="lorem ipsum dolor ipsum"'],
				[['lorem', 'ipsum', 'dolor', 'ipsum'], true, ' a="lorem ipsum dolor"'],
				['', false, ' a=""'],
				['', true, ' a=""'],
				[[], false, ' a=""'],
				[[], true, ' a=""'],
				[null, false, ''],
				[null, true, ''],
				[false, false, ''],
				[false, true, ''],
				[true, false, ' a="a"', false],
				[true, true, ' a="a"', false],
				[true, false, ' a', true],
				[true, true, ' a', true],
		);
	}

	/**
	 * @dataProvider resetAttribProvider
	 */
	public function testResetAttrib($value, $check, $expected, $sgml = false)
	{
		$xml = $sgml ? new TestSgml('e') : new TestXml('e');
		$attributes = new XmlAttributes($xml);
		$attributes->resetAttrib('a', $value, ' ', $check);
		$this->assertEquals($expected, $attributes->str());
	}

	public function appendToAttribProvider()
	{
		return array(
				['ipsum dolor sit ipsum', false, ' a="lorem ipsum dolor ipsum dolor sit ipsum"'],
				['ipsum dolor sit ipsum', true, ' a="lorem ipsum dolor sit"'],
				[['ipsum', 'dolor', 'sit', 'ipsum'], false,
						' a="lorem ipsum dolor ipsum dolor sit ipsum"'],
				[['ipsum', 'dolor', 'sit', 'ipsum'], true, ' a="lorem ipsum dolor sit"'],
				['', false, ' a="lorem ipsum dolor"'],
				['', true, ' a="lorem ipsum dolor"'],
				[[], false, ' a="lorem ipsum dolor"'],
				[[], true, ' a="lorem ipsum dolor"'],
				[null, false, ' a="lorem ipsum dolor"'],
				[null, true, ' a="lorem ipsum dolor"'],
				[false, false, ' a="lorem ipsum dolor"'],
				[false, true, ' a="lorem ipsum dolor"'],
				[true, false, ' a="lorem ipsum dolor"', false],
				[true, true, ' a="lorem ipsum dolor"', false],
				[true, false, ' a="lorem ipsum dolor"', true],
				[true, true, ' a="lorem ipsum dolor"', true],
		);
	}

	/**
	 * @dataProvider appendToAttribProvider
	 */
	public function testAppendToAttrib($value, $check, $expected, $sgml = false)
	{
		$xml = $sgml ? new TestSgml('e') : new TestXml('e');
		$attributes = new XmlAttributes($xml);
		$attributes->resetAttrib('a', 'lorem ipsum dolor');
		$attributes->appendToAttrib('a', $value, ' ', $check);
		$this->assertEquals($expected, $attributes->str());
	}
}