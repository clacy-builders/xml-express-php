<?php

namespace ML_Express\Tests\Shared;

require_once __DIR__ . '/../../src/XmlAttributes.php';
require_once __DIR__ . '/../../src/Xml.php';
require_once __DIR__ . '/../../src/shared/StyleAttribute.php';
require_once __DIR__ . '/../Express_TestCase.php';

use ML_Express\XmlAttributes;
use ML_Express\Xml;
use ML_Express\Shared\StyleAttribute;
use ML_Express\Tests\Express_TestCase;

class StyleAttrXml extends Xml
{
	use StyleAttribute;
}

class StyleAttributeTest extends Express_TestCase
{
	public function provider()
	{
		return array(
				array(
						StyleAttrXml::createSub('circle')
								->setStyle(null),
						'<circle/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->setStyle('stroke: #567; fill: #def'),
						'<circle style="stroke-opacity: 0.5;stroke: #567; fill: #def"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->setStyle(null),
						'<circle style="stroke-opacity: 0.5"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->setStyle('fill', '#567'),
						'<circle style="stroke-opacity: 0.5;fill: #567"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->setStyle(['stroke: #567', 'fill: #def']),
						'<circle style="stroke-opacity: 0.5;stroke: #567;fill: #def"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->setStyle(['stroke' => '#567', 'fill' => '#def']),
						'<circle style="stroke-opacity: 0.5;stroke: #567;fill: #def"/>'
				)
		);
	}
}