<?php

namespace ClacyBuilders\Shared;

trait DimensionAttributes
{
	/**
	 * Sets the <code>width</code> attribute.
	 *
	 * @param  mixed  $width   CSS pixels (Html5), for SVG also a string: '160px', '10em' etc.
	 * @return Xml
	 */
	public function setWidth($width)
	{
		return $this->attrib('width', $width);
	}

	/**
	 * Sets the <code>height</code> attribute.
	 *
	 * @param  mixed  $height  CSS pixels (Html5), for SVG also a string: '160px', '10em' etc.
	 * @return Xml
	 */
	public function setHeight($height)
	{
		return $this->attrib('height', $height);
	}

	/**
	 * Sets the <code>width</code> and <code>height</code> attributes.
	 *
	 * @param  mixed  $width   CSS pixels (Html5), for SVG also a string: '160px', '10em' etc.
	 * @param  mixed  $height  CSS pixels (Html5), for SVG also a string: '160px', '10em' etc.
	 * @return Xml
	 */
	public function setDimensions($width, $height)
	{
		return $this->setWidth($width)->setHeight($height);
	}
}
