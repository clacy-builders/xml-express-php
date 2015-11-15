<?php

namespace ML_Express\Shared;

trait StyleAttribute
{
	/**
	 * Appends one or more CSS properties to the style attribute.
	 *
	 * Examples:<ul>
	 * <li><code>setStyle('width', '40em')</code>
	 * <li><code>setStyle('width: 40em;color: #369')</code>
	 * <li><code>setStyle(['width: 40em', 'color: #369'])</code>
	 * <li><code>setStyle(['width' => '40em', 'color' => '#369'])</code>
	 * </ul>
	 *
	 * @param style string|array|null
	 * <p>See examples above.</p>
	 *
	 * @param value string [optional]
	 * <p>See first example above.</p>
	 */
	public function setStyle($style, $value = null)
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