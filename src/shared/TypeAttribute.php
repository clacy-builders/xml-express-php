<?php

namespace ML_Express\Shared;

trait TypeAttribute
{
	public function setType($type)
	{
		return $this->attrib('type', $type);
	}
}