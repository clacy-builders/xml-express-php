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
		);
	}
}