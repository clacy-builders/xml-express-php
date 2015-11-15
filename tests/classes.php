<?php

namespace ML_Express\Tests;

use ML_Express\Xml;
use ML_Express\Adhoc;

class TestXml extends Xml
{
	const MIME_TYPE = 'application/vnd.ml-express.tml+xml';
	const FILENAME_EXTENSION = 'tml';
	const XML_VERSION = '1.1';
	const CHARACTER_ENCODING = 'ISO-8859-15';
}

class TestSgml extends TestXml
{
	//use ClassAttribute;

	const SGML_MODE = true;
	const DOCTYPE = '<!DOCTYPE tml>';
}

class Compact extends TestSgml
{
	const DOCTYPE = null;
	const DEFAULT_LINE_BREAK = '';
}

class Html extends Xml
{
	use Adhoc;

	const doctype = '<!DOCTYPE html>';
	const SGML_MODE = true;

	public static function createHtml($lang = null, $manifest = null)
	{
		return (new AdhocHtml('html'))->attrib($lang)->attrib($manifest);
	}

	public function setSelected($selected = true)
	{
		return $this->booleanAttrib($selected, 'selected', 'value');
	}
}