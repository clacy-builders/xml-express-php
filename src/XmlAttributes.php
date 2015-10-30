<?php

namespace ML_Express;

/**
 * Represents the attributes of a XML element.
 *
 * @author M. Hoffmann
 */
class XmlAttributes
{
	private $element;

	private $attributes;

	/**
	 * The constructor.
	 *
	 * @param element XML
	 * <p>The XML element which contains the attributes.</p>
	 */
	public function __construct(Xml $element)
	{
		$this->element = $element;
		$this->attributes = array();
	}

	/**
	 * Sets an attribute.
	 *
	 * @param name string
	 * <p>Name of the attribute.</p>
	 *
	 * @param value string|boolean|null
	 * <p>Value of the attribute. Use <code>true</code> or <code>false</code> for boolean
	 * attributes like checked, selected in HTML.</p>
	 */
	public function setAttrib($name, $value)
	{
		$this->attributes[$name] = $value;
	}

	/**
	 * Sets a composable attribute like <code>class</code>, <code>style</code> (HTML)
	 * or <code>points</code> (SVG).
	 *
	 * @param name string
	 * <p>Name of the attribute.</p>
	 *
	 * @param value string|array|null
	 * <p>Value of the attribute.</p>
	 *
	 * @param delimiter string [optional]
	 * <p>The boundary string.</p>
	 *
	 * @param check boolean [optional]
	 * <p>Whether multiple entries shall be removed or not.</p>
	 */
	public function resetAttrib($name, $value, $delimiter = ' ', $check = false)
	{
		if ($value === null || is_bool($value)) {
			$this->setAttrib($name, $value);
			return;
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
	 * Append to a composable attribute like <code>class</code>, <code>style</code> (HTML)
	 * or <code>points</code> (SVG).
	 *
	 * @param name string
	 * <p>Name of the attribute.</p>
	 *
	 * @param value string|array|null
	 * <p>Value of the attribute.</p>
	 *
	 * @param delimiter string [optional]
	 * <p>The boundary string.</p>
	 *
	 * @param check boolean [optional]
	 * <p>Whether multiple entries shall be removed or not.</p>
	 */
	public function appendToAttrib($name, $value, $delimiter = ' ', $check = false)
	{
		if (empty($value) || is_bool($value)) return;
		if (empty($this->getAttrib($name))) {
			$this->resetAttrib($name, $value, $delimiter, $check);
		}
		elseif (is_array($value)) {
			$this->resetAttrib($name,
					array_merge(explode($delimiter, $this->getAttrib($name)), $value),
					$delimiter, $check);
		}
		else {
			$this->resetAttrib($name,
					$this->getAttrib($name) . $delimiter . $value,
					$delimiter, $check);
		}
	}

	/**
	 * Returns the value of the attribute.
	 *
	 * @param name string
	 * <p>Name of the attribute.</p>
	 *
	 * @return string|boolean|null
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
	 * @param name string
	 * <p>Name of the attribute.</p>
	 *
	 * @return string
	 */
	public function getAttribStr($name)
	{
		$value = $this->attributes[$name];
		if ($value === null || $value === false || $name === null) {
			return '';
		}
		if ($value === true) {
			$class = get_class($this->element->getRoot());
			if ($class::SGML_MODE) {
				return ' ' . $name;
			}
			else {
				$value = $name;
			}
		}
		return ' ' . $name . '="' . $value . '"';
	}
}