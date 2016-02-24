<?php

namespace ML_Express\Shared;

use ML_Express\ProcessingInstruction;

class XmlStylesheetInstruction extends ProcessingInstruction
{
	use MediaAttribute, TitleAttribute, TypeAttribute;

	public static function createXmlStylesheetInstruction($href, $media = null,
			$alternate = false, $title = null, $type = 'text/css', $charset = null)
	{
		return (new XmlStylesheetInstruction('xml-stylesheet'))
				->attrib('href', $href)
				->setType($type)
				->setMedia($media)
				->setAlternate($alternate)
				->setTitle($title)
				->setCharset($charset);
	}

	public function setAlternate($alternate = true)
	{
		return $this->attrib('alternate', $alternate === true ? 'yes' : $alternate);
	}

	public function setCharset($charset)
	{
		return $this->attrib('charset', $charset);
	}
}