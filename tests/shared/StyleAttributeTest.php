<?php

namespace ClacyBuilders\Tests\Shared;

require_once __DIR__ . '/../../src/Attributes.php';
require_once __DIR__ . '/../../src/Xml.php';
require_once __DIR__ . '/../../src/shared/StyleAttribute.php';
require_once __DIR__ . '/../ClacyBuilders_TestCase.php';

use ClacyBuilders\Attributes;
use ClacyBuilders\Xml;
use ClacyBuilders\Shared\StyleAttribute;
use ClacyBuilders\Tests\ClacyBuilders_TestCase;

class StyleAttrXml extends Xml
{
	use StyleAttribute;
}

class StyleAttributeTest extends ClacyBuilders_TestCase
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