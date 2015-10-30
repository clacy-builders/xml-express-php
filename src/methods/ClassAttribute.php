<?php

namespace ML_Express;

trait ClassAttribute
{
	/**
	 * Sets or resets the class attribute.
	 *
	 * @param className string|array|null
	 */
	public function setClass($className)
	{
 		return $this->complexAttrib('class', $className, ' ', true);
	}

	/**
	 * Appends one or more class names to the class attribute.
	 *
	 * @param className string|array|null
	 */
	public function addClass($className)
	{
		return $this->complexAttribAppend('class', $className, ' ', true);
	}
}