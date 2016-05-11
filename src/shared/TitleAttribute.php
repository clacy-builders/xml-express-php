<?php

namespace ClacyBuilders\Shared;

trait TitleAttribute
{
	public function setTitle($title)
	{
		return $this->attrib('title', $title);
	}
}