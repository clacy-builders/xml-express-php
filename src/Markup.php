<?php

namespace ClacyBuilders;

/**
 * Base class for <code>Xml</code> and <code>ProcessingInstruction</code>.
 */
abstract class Markup
{
	protected $name;
	protected $content;
	protected $attributes;

	/**
	 * Appends a new or sets an already existing attribute.
	 *
	 * @param  string  $name   Name of the attribute.
	 * @param  mixed   $value  Value of the attribute.
	 * @return Xml
	 */
	public function attrib($name, $value)
	{
		$this->attributes->setAttrib($name, $value);
		return $this;
	}

	/**
	 * Sets or appends to a composable attribute.
	 *
	 * @param  string   $name       Name of the attribute.
	 * @param  mixed    $value      One or more (array) values.
	 * @param  string   $delimiter  The boundary string.
	 * @param  boolean  $check      Whether multiple entries shall be removed or not.
	 * @return Xml
	 */
	public function complexAttrib($name, $value, $delimiter = ' ', $check = false)
	{
		$this->attributes->setComplexAttrib($name, $value, $delimiter, $check);
		return $this;
	}

	/**
	 * Sets a boolean attribute, if applicable by comparing a value with the value of another
	 * attribute.
	 *
	 * @param  string  $name                 Name of the attribute.
	 * @param  mixed   $value                Boolean or one or more (array) values.
	 * @param  string  $comparisonAttribute  Name of the other attribute to compare with.
	 * @return Xml
	 */
	public function booleanAttrib($name, $value = true, $comparisonAttribute = null)
	{
		$this->attributes->setBooleanAttrib($name, $value, $comparisonAttribute);
		return $this;
	}

	/**
	 * Sets multiple attributes.
	 *
	 * @param  array  $attributes  Assotiative array: the keys are the attributes names.
	 */
	public function attributes($attributes)
	{
		$this->attributes->setAttributes($attributes);
		return $this;
	}
}