<?php

namespace ML_Express;

require_once __DIR__ . '/../../src/XmlAttributes.php';
require_once __DIR__ . '/../../src/Xml.php';
require_once __DIR__ . '/../../src/methods/ClassAttribute.php';
require_once __DIR__ . '/../Express_TestCase.php';

class ClassAttrXml extends Xml
{
	use ClassAttribute;

	const SGML_MODE = true;
}

class ClassAttributeTest extends Express_TestCase
{
	public function provider()
	{
		return array(
				// altClass()
				array(
						ClassAttrXml::createSub('div')
								->altClass('lorem ipsum dolores')
								->altClass('dolor ipsum lorem'),
						'<div class="lorem ipsum dolores dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->altClass('lorem ipsum dolor')
								->altClass('dolores ipsum lorem dolores'),
						'<div class="lorem ipsum dolor dolores">'
				),
				array(
						ClassAttrXml::createSub('div')
								->altClass('lorem ipsum dolores')
								->altClass(['dolor', 'ipsum', 'lorem']),
						'<div class="lorem ipsum dolores dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->altClass('lorem ipsum dolor'),
						'<div class="lorem ipsum dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->altClass(null)
								->altClass('lorem ipsum dolor'),
						'<div class="lorem ipsum dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->altClass('lorem ipsum dolor')
								->altClass(null),
						'<div class="lorem ipsum dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->altClass(['lorem', 'ipsum', 'dolor', 'ipsum', 'lorem']),
						'<div class="lorem ipsum dolor">'
				),
				array(
						ClassAttrXml::createSub('div')
								->altClass(null),
						'<div>'
				),
		);
	}
}