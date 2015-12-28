<?php

namespace ML_Express\Tests\Shared;

require_once __DIR__ . '/../../src/XmlAttributes.php';
require_once __DIR__ . '/../../src/Xml.php';
require_once __DIR__ . '/../../src/shared/ClassAttribute.php';
require_once __DIR__ . '/../Express_TestCase.php';

use ML_Express\Xml;
use ML_Express\Shared\ClassAttribute;
use ML_Express\Tests\Express_TestCase;

class ClassAttrXml extends Xml
{
	use ClassAttribute;

	const HTML_MODE = true;

	public function table()
	{
		$table = $this->append('table');
		$tr = $table->append('tr');
		$tr->append('td', 'Foo');
		$tr->append('td', 'Berlin');
		$tr->append('td', '20');
		$tr = $table->append('tr');
		$tr->append('td', 'Foo');
		$tr->append('td', 'Berlin');
		$tr->append('td', '12');
		$tr = $table->append('tr');
		$tr->append('td', 'Foo');
		$tr->append('td', 'Cologne');
		$tr->append('td', '12');
		$tr = $table->append('tr');
		$tr->append('td', 'Bar');
		$tr->append('td', 'Cologne');
		$tr->append('td', '12');
		$tr = $table->append('tr');
		$tr->append('td', 'Bar');
		$tr->append('td', 'Hamburg');
		$tr->append('td', '15');
		$tr = $table->append('tr');
		$tr->append('td', 'Bar');
		$tr->append('td', 'Hamburg');
		$tr->append('td', '15');

		return $table;
	}
}

class ClassAttributeTest extends Express_TestCase
{
	public function provider()
	{
		return array(
				// setClass()
				array(
						ClassAttrXml::createSub('div')
								->setClass('lorem ipsum dolores')
								->setClass('dolor ipsum lorem'),
						'<div class="lorem ipsum dolores dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->setClass('lorem ipsum dolor')
								->setClass('dolores ipsum lorem dolores'),
						'<div class="lorem ipsum dolor dolores">'
				),
				array(
						ClassAttrXml::createSub('div')
								->setClass('lorem ipsum dolores')
								->setClass(['dolor', 'ipsum', 'lorem']),
						'<div class="lorem ipsum dolores dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->setClass('lorem ipsum dolor'),
						'<div class="lorem ipsum dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->setClass(null)
								->setClass('lorem ipsum dolor'),
						'<div class="lorem ipsum dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->setClass('lorem ipsum dolor')
								->setClass(null),
						'<div class="lorem ipsum dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->setClass(['lorem', 'ipsum', 'dolor', 'ipsum', 'lorem']),
						'<div class="lorem ipsum dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->setClass(null),
						'<div>'
				),
				// stripes()
				array(
						ClassAttrXml::createSub()->table()
								->stripes(['a-1st', 'a-2nd'], 0)
								->stripes([null, 'b-2nd', 'b-3rd'], 1)
								->stripes([null, 'c-2nd']),
						'<table>
	<tr class="a-1st">
		<td>Foo</td>
		<td>Berlin</td>
		<td>20</td>
	</tr>
	<tr class="a-1st c-2nd">
		<td>Foo</td>
		<td>Berlin</td>
		<td>12</td>
	</tr>
	<tr class="a-1st b-2nd">
		<td>Foo</td>
		<td>Cologne</td>
		<td>12</td>
	</tr>
	<tr class="a-2nd b-3rd c-2nd">
		<td>Bar</td>
		<td>Cologne</td>
		<td>12</td>
	</tr>
	<tr class="a-2nd">
		<td>Bar</td>
		<td>Hamburg</td>
		<td>15</td>
	</tr>
	<tr class="a-2nd c-2nd">
		<td>Bar</td>
		<td>Hamburg</td>
		<td>15</td>
	</tr>
</table>'
				)
		);
	}
}