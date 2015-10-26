<?php

namespace ML_Express;

class XmlAttributes
{

	private $element;

	private $attributes;

	/**
	 *
	 * @param XML $element
	 */
	public function __construct(Xml $element)
	{
		$this->element = $element;
		$this->attributes = array();
	}

	/**
	 * Appends a new or sets an already existing attribute.
	 *
	 * @see Xml::attrib()
	 *
	 * @param	string			$name			Name of the attribute.
	 * @param	mixed			$value			Value of the attribute.
	 * @param	string|null		$glue
	 * @param	boolean			$appendArray
	 */
	public function append($name, $value, $glue = null, $appendArray = false, $check = false)
	{
		if (empty($name)) return;

		$isArray = is_array($value);

		if ($isArray & empty($glue)) return;

		$append = !empty($glue)
				&& !empty($this->attributes[$name])
				&& $value !== true
				&& ($appendArray || !$isArray);
		$check &= $append;

		if ($append && empty($value)) return;

		if ($check) {
			$old = explode($glue, $this->attributes[$name]);
			$new = $isArray ? $value : explode($glue, $value);

			foreach ($new as $val) {
				if (!in_array($val, $old)) {
					$this->attributes[$name] .= $glue . $val;
				}
			}
			return;
		}
		if ($isArray) {
			$value = implode($glue, $value);
		}
		if ($append) {
			$this->attributes[$name] .= $glue . $value;
		}
		else {
			$this->attributes[$name] = $value;
		}
	}

	/**
	 * Returns the value of the attribute.
	 *
	 * @param	string		$name	Name of the attribute.
	 * @return	mixed
	 */
	public function getAttrib($name)
	{
		return $this->attributes[$name];
	}

	/**
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

	private function getAttribStr($name)
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