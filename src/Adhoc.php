<?php

namespace ClacyBuilders;

/**
 * Adds support for undefined methods to add corresponding XML elements or attributes.
 *
 * Adhoc allows you to
 * <ul>
 * <li>use any method name not previously defined starting with “set” to add attributes.
 * <li>use any other undefined method name to add XML elements.
 * <li>call static methods with any name to get the markup for the corresponding XML element.
 * </ul>
 * Example: <nobr><code>$links->a('Packagist')->setHref('https://packagist.org');</code></nobr>
 * instead of:
 * <nobr><code>$links->append('a', 'Packagist')->attrib('href', 'https://packagist.org');</code>
 * </nobr>
 */
trait Adhoc
{
	/**
	 * Adds an XML element or attribute, depending on the name of the method.
	 *
	 * @param  string  $method     Start the name of the method with “set” to add an attribute.
	 * @param  array   $arguments  Expected length is 0 or 1 (content/value).
	 * @return Xml
	 */
	public function __call($method, $arguments)
	{
		if (strpos($method, 'set') === 0) {
			$method = strtolower(substr($method, 3, strlen($method) - 3));
			$value = count($arguments) ? $arguments[0] : true;
			return $this->attrib($method, $value);
		}
		$content = count($arguments) ? $arguments[0] : null;
		return $this->append($method, $content);
	}

	/**
	 * Generates the markup for an XML element.
	 *
	 * @param  string  $method     Name of the XML element.
	 * @param  array   $arguments  Expected length is 0 or 1 (content).
	 * @return string
	 */
	public static function __callstatic($method, $arguments)
	{
		$content = count($arguments) ? $arguments[0] : null;
		return self::createSub($method, $content);
	}
}