<?php

namespace ML_Express;

require_once __DIR__ . '/../src/XmlAttributes.php';
require_once __DIR__ . '/../src/Xml.php';
require_once __DIR__ . '/../src/Adhoc.php';
require_once __DIR__ . '/Express_TestCase.php';
require_once __DIR__ . '/classes.php';

class XmlTest extends Express_TestCase
{

	public function provider()
	{
		return array(
				// Constructor
				array(new Xml, ''),
				array(new Xml('e'), self::XML_DECL . "\n<e/>"),
				array(new Xml('e', 'content'), self::XML_DECL . "\n<e>content</e>"),

				// Derived classes
				array(
						(new TestXml('tml'))->append('e'),
						"<?xml version=\"1.1\" encoding=\"ISO-8859-15\" ?>\n<tml>\n\t<e/>\n</tml>"
				),
				array(
						(new TestSgml('tml'))->append('e'),
						"<!DOCTYPE tml>\n<tml>\n\t<e>\n</tml>"
				),
				array(
						(new Compact('tml'))->append('e'),
						"<tml><e></tml>"
				),

				// setOptions()
				array(
						Xml::createSub('e')
								->setOptions(array(
										Xml::OPTION_LINE_BREAK => '',
										Xml::OPTION_INDENTATION => '    '
								))
								->append('f')->append('e'),
						"<e><f><e/></f></e>"),
				array(
						Xml::createSub('e')
								->setOptions(array(
										Xml::OPTION_LINE_BREAK => "\r",
										Xml::OPTION_INDENTATION => '..',
								))
								->append('f')->append('e'),
						"<e>\r..<f>\r....<e/>\r..</f>\r</e>"),

				// in_line
				array(
						Xml::createSub()
								->append('e')->append('f', 'content')->in_line()
								->append('g', 'content'),
						"<e>\n\t<f>content<g>content</g></f>\n</e>"
				),

				// createSub()
				array(
						Xml::createSub('e')->append('f', 'content'),
						"<e>\n\t<f>content</f>\n</e>"
				),
				array(
						Xml::createSub()->append('e', 'content'),
						"<e>content</e>"
				),
				array(
						Xml::createSub()
								->append('e', 'content')->getRoot()
								->append('f', 'content'),
						"<e>content</e><f>content</f>"
				),

				// inject()
				array(
						Xml::createSub('e')->inject((new Xml)->append('f', 'content')->getRoot()),
						"<e>\n\t<f>content</f>\n</e>"
				),
				array(
						Xml::createSub('e')->inject(Xml::createSub('f')),
						"<e>\n\t<f/>\n</e>"
				),
				array(
						Xml::createSub('e')->inject(Xml::createSub('f', 'content')),
						"<e>\n\t<f>content</f>\n</e>"
				),
				array(
						Xml::createSub('e')->inject((new Xml('f'))->append('g')->getRoot()),
						"<e>\n\t<f>\n\t\t<g/>\n\t</f>\n</e>"
				),
				array(
						(new Compact('e'))->inject((new Xml)
								->append('f1')->getRoot()->append('f2')->getRoot()),
						"<e><f1><f2></e>"
				),

				// cdata()
				array(
						Xml::createSub()->append('e')->cdata(),
						"<e/>"
				),
				array(
						Xml::createSub()->append('e', 'content')->cdata(),
						"<e><![CDATA[content]]></e>"
				),
				array(
						Xml::createSub()->append('e')->cdata()->append('f', 'content'),
						"<e>\n<![CDATA[\n\t<f>content</f>\n]]>\n</e>"
				),

				// append()
				array(TestXml::createSub()->append(null), ''),
				array(TestXml::createSub()->append(''), ''),
				array(TestXml::createSub()->append(null, 'content'), ''),
				array(TestXml::createSub()->append('', 'content'), ''),
				array(TestXml::createSub()->append('e'), '<e/>'),
				array(TestXml::createSub()->append('e', ''), '<e/>'),
				array(TestXml::createSub()->append('e', 'content'), '<e>content</e>'),
				array(TestSgml::createSub()->append(null), ''),
				array(TestSgml::createSub()->append(''), ''),
				array(TestSgml::createSub()->append(null, 'content'), ''),
				array(TestSgml::createSub()->append('', 'content'), ''),
				array(TestSgml::createSub()->append('e'), '<e>'),
				array(TestSgml::createSub()->append('e', ''), '<e></e>'),
				array(TestSgml::createSub()->append('e', 'content'), '<e>content</e>'),

				// text()
				array(
						Xml::createSub()->append('e')->text('Lorem')->text('Ipsum')->text('Dolor'),
						"<e>\n\tLorem\n\tIpsum\n\tDolor\n</e>"
				),

				// comment()
				array(
						Xml::createSub()->append('e')->comment('content'),
						"<e>\n\t<!-- content -->\n</e>"
				),

				// getContentDispositionHeaderfield, getContentTypeHeaderfield()
				array(
						TestXml::getContentDispositionHeaderfield('test'),
						"Content-Disposition: attachment; filename=\"test.tml\""
				),
				array(
						TestXml::getContentDispositionHeaderfield('test.xml', false),
						"Content-Disposition: attachment; filename=\"test.xml\""
				),
				array(
						TestXml::getContentTypeHeaderfield(),
						"Content-Type: application/vnd.ml-express.tml+xml; charset=ISO-8859-15"
				),

				// setBoolAttrib()
				array(
						Html::createSub('option', 'PHP')->setValue('php')->setSelected(),
						'<option value="php" selected>PHP</option>'
				),
				array(
						Html::createSub('option', 'PHP')->setValue('php')->setSelected('php'),
						'<option value="php" selected>PHP</option>'
				),
				array(
						Html::createSub('option', 'PHP')->setValue('php')
								->setSelected(['php', 'python']),
						'<option value="php" selected>PHP</option>'
				),
				array(
						Html::createSub('option', 'PHP')->setValue('php')->setSelected(false),
						'<option value="php">PHP</option>'
				),
				array(
						Html::createSub('option', 'PHP')->setValue('php')->setSelected('python'),
						'<option value="php">PHP</option>'
				),
				array(
						Html::createSub('option', 'PHP')->setValue('php')
								->setSelected(['perl', 'python']),
						'<option value="php">PHP</option>'
				)
		);
	}
}