<?php

namespace ClacyBuilders\Shared;

interface XLinkConstants
{
	const XLINK_NAMESPACE = 'http://www.w3.org/1999/xlink';

	const XLINK_TYPE_SIMPLE = 'simple';

	const XLINK_SHOW_EMBED = 'embed';
	const XLINK_SHOW_NEW = 'new';
	const XLINK_SHOW_REPLACE = 'replace';
	const XLINK_SHOW_OTHER = 'other';
	const XLINK_SHOW_NONE = 'none';

	const XLINK_ACTUATE_ONLOAD = 'onLoad';
	const XLINK_ACTUATE_ONREQUEST = 'onRequest';
	const XLINK_ACTUATE_OTHER = 'other';
	const XLINK_ACTUATE_NONE = 'none';
}