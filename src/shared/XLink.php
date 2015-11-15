<?php

namespace ML_Express\Shared;

/**
 * The XLink attributes, but for element type <code>simple</code> only.
 */
trait XLink
{
	public function setXLinkType($type = XLinkConstants::XLINK_TYPE_SIMPLE)
	{
		return $this->attrib('xlink:type', $type);
	}

	public function setXLinkHref($href)
	{
		return $this->attrib('xlink:href', $href);
	}

	public function setXLinkRole($role)
	{
		return $this->attrib('xlink:role', $role);
	}

	public function setXLinkArcrole($arcrole)
	{
		return $this->attrib('xlink:arcrole', $arcrole);
	}

	public function setXLinkTitle($title)
	{
		return $this->attrib('xlink:title', $title);
	}

	public function setXLinkShow($show)
	{
		return $this->attrib('xlink:show', $show);
	}

	public function setXLinkActuate($actuate)
	{
		return $this->attrib('xlink:actuate', $actuate);
	}
}