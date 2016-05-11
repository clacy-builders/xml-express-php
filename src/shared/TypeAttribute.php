<?php

namespace ClacyBuilders\Shared;

trait TypeAttribute
{
	public function setType($type)
	{
		return $this->attrib('type', $type);
	}
}