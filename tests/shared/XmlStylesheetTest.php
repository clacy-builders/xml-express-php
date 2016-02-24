<?php

namespace ML_Express\Tests\Shared;

require_once __DIR__ . '/../../allIncl.php';
require_once __DIR__ . '/../Express_TestCase.php';

use ML_Express\Xml;
use ML_Express\Shared\XmlStylesheet;
use ML_Express\Tests\Express_TestCase;
use ML_Express\Shared\MediaAttributeConstants;
use ML_Express\Shared\MediaAttribute;

class StyledXml extends Xml implements MediaAttributeConstants// implements XmlStylesheetConstants
{
	use XmlStylesheet;

	public static function createStyled()
	{
		return static::createRoot('styled');
	}
}

class XmlStylesheetTest extends Express_TestCase
{
	public function provider()
	{
		return array(
				array(
						function() {
							$xml = StyledXml::createStyled();
							$xml->xmlStylesheet('one.css', StyledXml::MEDIA_SCREEN, true, 'One',
									'text/css', StyledXml::UTF8);
							$xml->xmlStylesheet('two.css')
									->setMedia(StyledXml::MEDIA_SCREEN)
									->setAlternate()
									->setTitle('Two')
									->setCharset(StyledXml::UTF8);
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
