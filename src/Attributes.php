<?php

namespace ClacyBuilders;

/**
 * Represents the attributes of a XML element.
 *
 * @author M. Hoffmann
 */
class Attributes
{
	private $element;

	private $attributes;

	/**
	 * The constructor.
	 *
	 * @param  XML  $element  The XML element which contains the attributes.
	 */
	public function __construct(Xml $element = null)
	{
		$this->element = $element;
		$this->attributes = array();
	}

	/**
	 * Sets an attribute.
	 *
	 * @param  string               $name   Name of the attribute.
	 * @param  string|boolean|null  $value  Value of the attribute. Use <code>true</code> or
	 *                                      <code>false</code> for boolean attributes like checked,
	 *                                      selected in HTML.
	 */
	public function setAttrib($name, $value)
	{
		$this->attributes[$name] = $value;
	}

	/**
	 * Sets or appends to a composable attribute like <code>class</code> (HTML)
	 * or <code>points</code> (SVG).
	 *
	 * @param  string   $name       Name of the attribute.
	 * @param  mixed    $value      One or more (array) values.
	 * @param  string   $delimiter  The boundary string.
	 * @param  boolean  $check      Whether multiple entries shall be removed or not.
	 */
	public function setComplexAttrib($name, $value, $delimiter = ' ', $check = false)
	{
		if (is_bool($value) || (empty($this->getAttrib($name)) && empty($value))) {
			$this->setAttrib($name, $value);
			return;
		}
		if (!empty($this->getAttrib($name))) {
			if (empty($value)) return;
			if (is_array($value)) {
				$value = array_merge(explode($delimiter, $this->getAttrib($name)), $value);
			}
			else {
				$value = $this->getAttrib($name) . $delimiter . $value;
			}
		}
		if ($check) {
			if (!is_array($value)) {
				$value = explode($delimiter, $value);
			}
			$value = array_unique($value);
		}
		if (is_array($value)) {
			$value = implode($delimiter, $value);
		}
		$this->setAttrib($name, $value);
	}

	/**
	 * Sets a boolean attribute by comparing a value with the value of another attribute.
	 *
	 * Helpful for attributes like <code>selected</code> in HTML.
	 *
	 * @param  string   $name       Name of the attribute.
	 * @param  mixed    $value      One or more (array) values.
	 * @param  string   $delimiter  The boundary string.
	 * @param  boolean  $check      Whether multiple entries shall be removed or not.
	 */
	public function setBooleanAttrib($name, $value = true, $comparisonAttribute = null)
	{
		if (!is_bool($value) && !is_null($comparisonAttribute)) {
			$compare = $this->getAttrib($comparisonAttribute);
			if (is_array($value)) {
				$value = in_array($compare, $value, true);
			}
			else {
				$value = $value === $compare;
			}
		}
		$this->setAttrib($name, $value);
	}

	/**
	 * Sets multiple attributes.
	 *
	 * @param  array  $attributes  Assotiative array: the keys are the attributes names.
	 */
	public function setAttributes($attributes)
	{
		foreach ($attributes as $name => $value) {
			$this->setAttrib($name, $value);
		}
	}

	/**
	 * Returns the value of the attribute.
	 *
	 * @param  string  $name  Name of the attribute.
	 * @return mixed
	 */
	public function getAttrib($name)
	{
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}

	/**
	 * Returns the string representation of all attributes.
	 *
	 * @return string
	 */
	public function str()
	{
		$str = '';
		foreach ($this->attributes as $name => $value) {
			$str .= $this->getAttribStr($name);
		}
		return $str;
	}

	/**
	 * Returns the string representation of a single attribute.
	 *
	 * @param  string  $name  Name of the attribute.
	 * @return string
	 */
	public function getAttribStr($name)
	{
		$value = $this->attributes[$name];
		if ($value === null || $value === false || $name === null) {
			return '';
		}
		if ($value === true) {
			if ($this->element) {
				$class = get_class($this->element);
				if ($class::HTML_MODE) {
					return ' ' . $name;
				}
			}
			$value = $name;
		}
		return ' ' . $name . '="' . $value . '"';
	}
}