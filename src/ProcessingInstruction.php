<?php

namespace ML_Express;

class ProcessingInstruction extends Markup
{
	protected function __construct($target, $content = null)
	{
		$this->name = $target;
		$this->content = $content;
		$this->attributes = new Attributes();
	}

	public static function createProcessingInstruction($target, $content = null)
	{
		return new ProcessingInstruction($target, $content);
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

	public function __toString()
	{
		return $this->getMarkup();
	}
}