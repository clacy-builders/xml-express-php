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
				// setStyle()
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('unseen')
								->setStyle('stroke: #567; fill: #def'),
						'<circle style="stroke: #567; fill: #def"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('unseen')
								->setStyle(null),
						'<circle/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('unseen')
								->setStyle('fill', '#567'),
						'<circle style="fill: #567"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('unseen')
								->setStyle(['stroke: #567', 'fill: #def']),
						'<circle style="stroke: #567;fill: #def"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('unseen')
								->setStyle(['stroke' => '#567', 'fill' => '#def']),
						'<circle style="stroke: #567;fill: #def"/>'
				),
				// addStyle()
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->addStyle('stroke: #567; fill: #def'),
						'<circle style="stroke-opacity: 0.5;stroke: #567; fill: #def"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->addStyle(null),
						'<circle style="stroke-opacity: 0.5"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->addStyle('fill', '#567'),
						'<circle style="stroke-opacity: 0.5;fill: #567"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->addStyle(['stroke: #567', 'fill: #def']),
						'<circle style="stroke-opacity: 0.5;stroke: #567;fill: #def"/>'
				),
				array(
						StyleAttrXml::createSub('circle')
								->setStyle('stroke-opacity: 0.5')
								->addStyle(['stroke' => '#567', 'fill' => '#def']),
						'<circle style="stroke-opacity: 0.5;stroke: #567;fill: #def"/>'
				)
		);
	}
}