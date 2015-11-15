<?php

namespace ML_Express\Tests;

require_once __DIR__ . '/../src/XmlAttributes.php';
require_once __DIR__ . '/../src/Xml.php';
require_once __DIR__ . '/../src/Adhoc.php';
require_once __DIR__ . '/Express_TestCase.php';
require_once __DIR__ . '/classes.php';

class AdhocTest extends Express_TestCase
{
	public function provider()
	{
		return array(
				array(Html::createSub()->section()->setId('main'), '<section id="main">'),
				array(Html::createSub()->section()->setId(null), '<section>'),
				array(Html::createSub()->option()->setSelected(), '<option selected>'),
				array(Html::createSub()->option()->setSelected(true), '<option selected>'),
				array(Html::createSub()->option()->setSelected(false), '<option>'),
				array(Html::em('lorem') . " ipsum" . Html::br(), '<em>lorem</em> ipsum<br>')
		);
	}
}