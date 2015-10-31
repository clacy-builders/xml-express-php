<?php

namespace ML_Express;

trait StyleAttribute
{
	/**
	 * Appends one or more CSS properties to the style attribute.
	 *
	 * @param style mixed
	 *
	 * @param value string [optional]
	 */
	public function altStyle($style, $value = null)
	{
		$style = self::prepare($style, $value);
		return $this->complexAttrib('style', $style, ';');
	}

	private static function prepare($style, $value)
	{
		if (!empty($value) && is_string($style)) {
			return $style . ': ' . $value;
		}
		if (is_array($style) && $style !== array_values($style)) { // is associative array?
			$prepared = [];
			foreach ($style as $name => $value) {
				$prepared[] = self::prepare($name, $value);
			}
			return $prepared;
		}
		return $style;
	}
}