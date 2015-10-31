<?php

namespace ML_Express;

require_once __DIR__ . '/../../src/XmlAttributes.php';
require_once __DIR__ . '/../../src/Xml.php';
require_once __DIR__ . '/../../src/methods/StyleAttribute.php';
require_once __DIR__ . '/../Express_TestCase.php';

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
								->altStyle(null),
						'<circle/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->altStyle('stroke-opacity: 0.5')
								->altStyle('stroke: #567; fill: #def'),
						'<circle style="stroke-opacity: 0.5;stroke: #567; fill: #def"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->altStyle('stroke-opacity: 0.5')
								->altStyle(null),
						'<circle style="stroke-opacity: 0.5"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->altStyle('stroke-opacity: 0.5')
								->altStyle('fill', '#567'),
						'<circle style="stroke-opacity: 0.5;fill: #567"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->altStyle('stroke-opacity: 0.5')
								->altStyle(['stroke: #567', 'fill: #def']),
						'<circle style="stroke-opacity: 0.5;stroke: #567;fill: #def"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->altStyle('stroke-opacity: 0.5')
								->altStyle(['stroke' => '#567', 'fill' => '#def']),
						'<circle style="stroke-opacity: 0.5;stroke: #567;fill: #def"/>'
				)
		);
	}
}