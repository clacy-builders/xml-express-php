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

	/**
	 * Adds alternating classes to child elements.
	 *
	 * @param classes string[]
	 * @param column int|false
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