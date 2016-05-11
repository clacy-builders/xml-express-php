<?php

namespace ClacyBuilders\Tests;

require_once __DIR__ . '/../src/Markup.php';
require_once __DIR__ . '/../src/Attributes.php';
require_once __DIR__ . '/../src/Xml.php';
require_once __DIR__ . '/../src/Adhoc.php';
require_once __DIR__ . '/ClacyBuilders_TestCase.php';
require_once __DIR__ . '/classes.php';

class AdhocTest extends ClacyBuilders_TestCase
{
	public function provider()
	{
		return array(
				array(Html::createSub()->section()->setClass('main'), '<section class="main">'),
				array(Html::createSub()->section()->setClass(null), '<section>'),
				array(Html::createSub()->option()->setSelected(), '<option selected>'),
				array(Html::createSub()->option()->setSelected(true), '<option selected>'),
				array(Html::createSub()->option()->setSelected(false), '<option>'),
				array(Html::em('lorem') . " ipsum" . Html::br(), '<em>lorem</em> ipsum<br>')
		);
	}
}