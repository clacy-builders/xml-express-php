<?php

namespace ML_Express\Tests;

class Express_TestCase extends \PHPUnit_Framework_TestCase
{
	const XML_DECL = '<?xml version="1.0" encoding="UTF-8" ?>';
	const LI = 'lorem ipsum';

	/**
	 * @dataProvider provider
	 */
	public function test($xml, $expectedMarkup, $root = true)
	{
		$this->assertExpectedMarkup($xml, $expectedMarkup, $root);
	}

	public function assertExpectedMarkup($xml, $expectedMarkup, $root = true)
	{
		if (is_callable($xml)) {
			$xml = call_user_func($xml);
		}
		if (!$root) {
			$xml = $xml->getMarkup();
		}
		$this->assertEquals($expectedMarkup, '' . $xml);
	}
}
