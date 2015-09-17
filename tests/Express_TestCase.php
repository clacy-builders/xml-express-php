<?php

namespace ML_Express;

class Express_TestCase extends \PHPUnit_Framework_TestCase
{
	const XML_DECL = '<?xml version="1.0" encoding="UTF-8" ?>';
	const LI = 'lorem ipsum';

	/**
	 * @dataProvider provider
	 */
	public function test($xml, $expectedMarkup, $root = true)
	{
		if (is_string($xml)) {
			$markup = $xml;
		}
		else {
			if (is_callable($xml)) {
				$xml = call_user_func($xml);
			}
			$markup = $root ? $xml->getRoot()->getMarkup() : $xml->getMarkup();
		}
		$this->assertEquals($expectedMarkup, $markup);
	}
}
