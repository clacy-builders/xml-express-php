<?php

namespace ClacyBuilders\Shared;

trait XmlStylesheet
{
	public function xmlStylesheet($href, $media = null, $alternate = false, $title = null,
			$type = 'text/css', $charset = null)
	{
		return $this->processingInstr[] = XmlStylesheetInstruction::createXmlStylesheetInstruction(
				$href, $media, $alternate, $title, $type, $charset);
	}
}