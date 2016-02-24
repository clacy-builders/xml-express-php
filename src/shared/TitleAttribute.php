<?php

namespace ML_Express\Shared;

trait TitleAttribute
{
	public function setTitle($title)
	{
		return $this->attrib('title', $title);
	}
}