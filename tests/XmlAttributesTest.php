<?php

namespace ML_Express;

require_once __DIR__ . '/../src/XmlAttributes.php';
require_once __DIR__ . '/../src/Xml.php';
require_once __DIR__ . '/classes.php';

class XmlAttributesTest extends \PHPUnit_Framework_TestCase
{
	public function attributeProvider() {
		return array(
				array(' attribute="lorem ipsum"', 'lorem ipsum'),
				array(' attribute=""', ''),
				array('', null),
				array('', array('lorem ipsum', null)),
				array(' attribute="0"', 0),
				array(array(' attribute="attribute"', ' attribute'), true),
				array('', false),
				array(array(' attribute="attribute"', ' attribute'), array(false, true)),
				array('', array(true, false)),
				array(' attribute="ipsum"', array('lorem', 'ipsum')),
				array(' attribute="lorem ipsum"', array('lorem', 'ipsum'), ' '),
				array(' attribute="lorem ipsum"', array(null, array('lorem', 'ipsum')), ' '),
				array(' attribute="ipsum dolor"', array('lorem', array('ipsum', 'dolor')), ' '),
				array(' attribute="lorem ipsum dolor"',
						array('lorem', array('ipsum', 'dolor')), ' ', true),
				array(' attribute="lorem"', array('lorem', array('ipsum', 'dolor')), null, true));
	}

	/**
	 * @dataProvider attributeProvider
	 */
	public function testAttribute($expected, $values, $glue = null, $appendArray = false)
	{
		if (!is_array($expected)) {
			$expected = array($expected, $expected);
		}
		if (!is_array($values)) {
			$values = array($values);
		}
		foreach (array(false, true) as $sgmlMode) {
			$ml = $sgmlMode ? new TestSgml : new TestXml;
			$attributes = new XmlAttributes($ml);
			foreach($values as $value) {
				$attributes->append('attribute', $value, $glue, $appendArray);
			}
			$this->assertEquals($expected[$sgmlMode], $attributes->str());
		}
	}

	public function noNameProvider()
	{
		return array(array(null), array(''), array(false));
	}

	/**
	 * @dataProvider noNameProvider
	 */
	public function testNoName($name)
	{
		$attributes = new XmlAttributes(new TestXml);
		$attributes->append($name, 'lorem ipsum');
		$this->assertEquals('', $attributes->str());
	}

	public function testAttributes()
	{
		$attributes = new XmlAttributes(new TestXml);
		$attributes->append('attr1', 'lorem');
		$attributes->append('attr2', 'ipsum');
		$this->assertEquals(' attr1="lorem" attr2="ipsum"', $attributes->str());
	}

	public function valueProvider() {
		return array(
				array('lorem ipsum', 'lorem ipsum'),
				array('', ''),
				array(true, true),
				array(false, false),
				array(null, null)
		);
	}

	/**
	 * @dataProvider valueProvider
	 */
	public function testGetAttribute($value, $expected) {
		$attributes = new XmlAttributes(new TestXml);
		$attributes->append('attribute', $value);
		$this->assertEquals($expected, $attributes->getAttrib('attribute'));
	}
}