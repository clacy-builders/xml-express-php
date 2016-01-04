<?php

namespace ML_Express;

function arrays($array1, $array2)
{
	if ($array1 == null) {
		$array1 = array_keys($array2);
		$array2 = array_values($array2);
	}
	else if (isset($array1[0]) && (is_array($array1[0]) || is_object($array1[0]))) {
		$arr1 = array();
		$arr2 = array();
		if (!isset($array2)) {
			$array2 = \ML_Express\keys($array1[0]);
		}
		foreach ($array1 as $i => $arr) {
			$arr1[] = \ML_Express\value($arr, $array2[0]);
			$arr2[] = \ML_Express\value($arr, $array2[1]);
		}
		$array1 = $arr1;
		$array2 = $arr2;
	}
	else {
		$array1 = array_values($array1);
		$array2 = array_values($array2);
	}
	return array($array1, $array2);
}

function values($obj)
{
	if (is_object($obj)) {
		$obj = get_object_vars($obj);
	}
	return array_values($obj);
}

function value($obj, $key)
{
	if (is_array($obj) && isset($obj[$key])) {
		return $obj[$key];
	}
	if (is_object($obj) && isset($obj->{$key})) {
		return $obj->{$key};
	}
	return '';
}

function keys($obj, $keys = null)
{
	if (is_array($obj)) {
		$existingKeys = array_keys($obj);
	}
	else {
		$existingKeys = array_keys(get_object_vars($obj));
	}
	if (is_array($keys)) {
		return array_intersect($keys, $existingKeys);
	}
	return $existingKeys;
}

function join($value, $glue = ' ')
{
	if (empty($value)) return null;
	if (is_array($value)) return implode($glue, $value);
	return $value;
}

function formatDateTime(\DateTime $datetime, $format, $encoding = XML::UTF8) {
	$datetimeStr = strftime($format, $datetime->getTimestamp());
	if ($encoding == XML::UTF8) {
		$datetimeStr = utf8_encode($datetimeStr);
	}
	return $datetimeStr;
}