<?php

namespace ML_Express;

class ProcessingInstruction extends Markup
{
	public function __construct($target, $content = null, Attributes $attributes = null)
	{
		$this->name = $target;
		$this->content = $content;
		$this->attributes = $attributes;
	}

	public function getMarkup()
	{
		$str = '<?' . $this->name;
		if ($this->content !== null) {
			$str .= ' ' . $this->content;
		}
		if ($this->attributes !== null) {
			$str .= $this->attributes->str();
		}
		$str .= ' ?>';
		return $str;
	}
}