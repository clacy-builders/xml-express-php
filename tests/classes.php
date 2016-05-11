<?php

namespace ClacyBuilders\Tests;

use ClacyBuilders\Xml;
use ClacyBuilders\Adhoc;

class TestXml extends Xml
{
	const MIME_TYPE = 'application/vnd.ml-express.tml+xml';
	const FILENAME_EXTENSION = 'tml';
	const XML_VERSION = '1.1';
	const CHARACTER_ENCODING = 'ISO-8859-15';
}

class TestSgml extends TestXml
{
	const HTML_MODE = true;
	const XML_DECLARATION = false;
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

	const DOCTYPE = '<!DOCTYPE html>';
	const HTML_MODE = true;

	public static function createHtml($lang = null, $manifest = null)
	{
		return (new Html('html'))->attrib($lang)->attrib($manifest);
	}

	public function setSelected($selected = true)
	{
		return $this->booleanAttrib('selected', $selected, 'value');
	}
}

class Foo extends Xml
{
	const XML_DECLARATION = false;
	const DEFAULT_LINE_BREAK = '';
}

class Bar extends Xml
{
	const XML_DECLARATION = false;
	const DEFAULT_LINE_BREAK = '';
}

class FFoo extends Foo
{
	const NAMESP_PREFIX = 'f:';
}

class Baz extends Bar
{
	const XML_NAMESPACE = 'http://example.com/baz';

	public static function createBaz($namespaces = []) {
		return static::createRoot('baz', $namespaces);
	}
}