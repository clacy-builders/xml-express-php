<?php

namespace ML_Express;

require_once __DIR__ . '/../src/functions.php';

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider valueProvider
	 */
	public function testValue($obj, $key, $expected)
	{
		$value = \ML_Express\value($obj, $key);
		$this->assertEquals($expected, $value);
	}

	public function valueProvider()
	{
		$arr = array('name' => 'Foo', 'level' => 1, 'points' => '12500');
		$obj = (object) $arr;
		return array(
				[$arr, 'whatever', ''],
				[$obj, 'whatever', ''],
				[$arr, 'level', 1],
				[$obj, 'level', 1]
		);
	}

	/**
	 * @dataProvider keysProvider
	 */
	public function testKeys($obj, $keys, $expected)
	{
		$keys = \ML_Express\keys($obj, $keys);
		$this->assertEquals($expected, $keys);
	}

	public function keysProvider()
	{
		$arr = array('name' => 'Foo', 'level' => 1, 'points' => '12500');
		$obj = (object) $arr;
		return array(
				[$arr, null, ['name', 'level', 'points']],
				[$obj, null, ['name', 'level', 'points']],
				[$arr, ['level', 'whatever'], ['level']],
				[$obj, ['level', 'whatever'], ['level']]
		);
	}
}