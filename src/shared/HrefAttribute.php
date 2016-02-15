<?php

namespace ML_Express\Shared;

trait HrefAttribute
{
	public function setHref($href)
	{
		return $this->attrib('href', $href);
	}

	/**
	 * Adds a query string to an attribute, by default to the <code>href</code> attribute.
	 *
	 * @param  array   $queryParts  Assotiave array with query arguments.
	 * @param  string  $attribute   Name of the attribute.
	 */
	public function addQuery($queryParts, $attribute = 'href')
	{
		$url = $this->attributes->getAttrib($attribute);

		$query = array();
		foreach ($queryParts as $key => $value) {
			if (empty($value)) continue;
			$query[] = is_int($key) ? $value : $key . '=' . urlencode($value);
		}
		if (empty($query)) return $this;

		$prefix = strpos($url, '?') === false ? '?' : '&';
		$query = $prefix . implode('&', $query);

		$position = strpos($url, '#');
		if ($position === false) {
			return $this->attrib($attribute, $url . $query);
		}
		return $this->attrib($attribute, substr_replace($url, $query, $position, 0));
	}

}
