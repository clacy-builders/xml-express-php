<?php

namespace ML_Express\Shared;

trait MediaAttribute
{
	/**
	 * Appends one or more entries of a media query to the <code>media</code> attribute.
	 *
	 * @param  string|string[]  $media
	 */
	public function setMedia($media)
	{
		return $this->complexAttrib('media', $media, ',', true);
	}
}