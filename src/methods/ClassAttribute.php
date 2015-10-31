<?php

namespace ML_Express;

trait ClassAttribute
{
	/**
	 * Appends one or more class names to the class attribute.
	 *
	 * @param className string|array|null
	 */
	public function altClass($class)
	{
		return $this->complexAttrib('class', $class, ' ', true);
	}
}