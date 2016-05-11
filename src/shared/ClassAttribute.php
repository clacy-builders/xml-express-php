<?php

namespace ClacyBuilders\Shared;

trait ClassAttribute
{
	/**
	 * Appends one or more class names to the <code>class</code> attribute.
	 *
	 * @param  string|string[]|null  $class  A class or a space separated list of classes
	 *                                       or an array of classes.
	 * @return Xml
	 */
	public function setClass($class)
	{
		return $this->complexAttrib('class', $class, ' ', true);
	}

	/**
	 * Adds alternating classes to child elements.
	 *
	 * @param  string[]   $classes
	 * @param  int|false  $column
	 * @return Xml
	 */
	public function stripes($classes, $column = false)
	{
		$count = count($classes);
		$index = -1;
		$prev = null;
		foreach ($this->children as $i => $row) {
			if ($column === false) {
				$index = ++$index % $count;
			}
			else {
				foreach ($row->children as $k => $cell) {
					if ($k > $column) break;
					if ($i == 0 || $cell->content != $prev->children[$k]->content) {
						$index = ++$index % $count;
						break;
					}
				}
				$prev = $row;
			}
			$this->children[$i]->setClass($classes[$index]);
		}
		return $this;
	}
}