<?php

namespace ClacyBuilders\Tests\Shared;

require_once __DIR__ . '/../../src/Attributes.php';
require_once __DIR__ . '/../../src/Xml.php';
require_once __DIR__ . '/../../src/shared/XLink.php';
require_once __DIR__ . '/../../src/shared/XLinkConstants.php';
require_once __DIR__ . '/../ClacyBuilders_TestCase.php';

use ClacyBuilders\Attributes;
use ClacyBuilders\Xml;
use ClacyBuilders\Shared\XLink;
use ClacyBuilders\Shared\XLinkConstants;
use ClacyBuilders\Tests\ClacyBuilders_TestCase;

class XLinkXml extends Xml implements XLinkConstants
{
	use XLink;
}

class XLinkTest extends ClacyBuilders_TestCase
{
	public function provider()
	{
		return array(
				array(
						XLinkXml::createSub('e')->setXLinkType(),
						'<e xlink:type="simple"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkType(XLinkXml::XLINK_TYPE_SIMPLE),
						'<e xlink:type="simple"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkShow(XLinkXml::XLINK_SHOW_EMBED),
						'<e xlink:show="embed"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkShow(XLinkXml::XLINK_SHOW_NEW),
						'<e xlink:show="new"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkShow(XLinkXml::XLINK_SHOW_REPLACE),
						'<e xlink:show="replace"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkShow(XLinkXml::XLINK_SHOW_OTHER),
						'<e xlink:show="other"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkShow(XLinkXml::XLINK_SHOW_NONE),
						'<e xlink:show="none"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkActuate(XLinkXml::XLINK_ACTUATE_ONLOAD),
						'<e xlink:actuate="onLoad"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkActuate(XLinkXml::XLINK_ACTUATE_ONREQUEST),
						'<e xlink:actuate="onRequest"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkActuate(XLinkXml::XLINK_ACTUATE_OTHER),
						'<e xlink:actuate="other"/>'
				),
				array(
						XLinkXml::createSub('e')->setXLinkActuate(XLinkXml::XLINK_ACTUATE_NONE),
						'<e xlink:actuate="none"/>'
				),
		);
	}
}
