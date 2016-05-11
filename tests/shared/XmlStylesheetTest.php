<?php

namespace ClacyBuilders\Tests\Shared;

require_once __DIR__ . '/../../allIncl.php';
require_once __DIR__ . '/../ClacyBuilders_TestCase.php';

use ClacyBuilders\Xml;
use ClacyBuilders\Shared\XmlStylesheet;
use ClacyBuilders\Tests\ClacyBuilders_TestCase;
use ClacyBuilders\Shared\MediaAttributeConstants;

class StyledXml extends Xml implements MediaAttributeConstants
{
	use XmlStylesheet;

	public static function createStyled()
	{
		return static::createRoot('styled');
	}
}

class XmlStylesheetTest extends ClacyBuilders_TestCase
{
	public function provider()
	{
		return array(
				array(
						function() {
							$xml = StyledXml::createStyled();
							$xml->xmlStylesheet('one.css', StyledXml::MEDIA_SCREEN, true, 'One',
									'text/css', 'UTF-8');
							$xml->xmlStylesheet('two.css')
									->setMedia(StyledXml::MEDIA_SCREEN)
									->setAlternate()
									->setTitle('Two')
									->setCharset('UTF-8');
							return $xml;
						},
						self::XML_DECL . "\n" .
						'<?xml-stylesheet href="one.css" type="text/css"' .
						' media="screen" alternate="yes" title="One" charset="UTF-8" ?>' . "\n" .
						'<?xml-stylesheet href="two.css" type="text/css"' .
						' media="screen" alternate="yes" title="Two" charset="UTF-8" ?>' . "\n" .
						'<styled/>'
				)
		);
	}
}
