<?php

namespace ML_Express;

trait ClassAttribute
{
	/**
	 * Appends one or more class names to the class attribute.
	 *
	 * @param className string|array|null
	 * <p>A class or a space separated list of classes or an array of classes.</p>
	 */
	public function setClass($class)
	{
		return $this->complexAttrib('class', $class, ' ', true);
	}
}