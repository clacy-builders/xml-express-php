<?php

namespace ML_Express\Shared;

trait DimensionAttributes
{
	public function setWidth($width)
	{
		return $this->attrib('width', $width);
	}

	public function setHeight($height)
	{
		return $this->attrib('height', $height);
	}

	public function setDimension($width, $height)
	{
		return $this->setWidth($width)->setHeight($height);
	}
}
