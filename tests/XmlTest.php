<?php

namespace ML_Express\Tests;

require_once __DIR__ . '/../src/XmlAttributes.php';
require_once __DIR__ . '/../src/Xml.php';
require_once __DIR__ . '/../src/Adhoc.php';
require_once __DIR__ . '/Express_TestCase.php';
require_once __DIR__ . '/classes.php';

use ML_Express\XmlAttributes;
use ML_Express\XML;
use ML_Express\Adhoc;

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
						Xml::c_('e')
								->setOptions(array(
										Xml::OPTION_LINE_BREAK => '',
										Xml::OPTION_INDENTATION => '    '
								))
								->append('f')->append('e'),
						"<e><f><e/></f></e>"
				),
				array(
						Xml::c_('e')
								->setOptions(array(
										Xml::OPTION_LINE_BREAK => "\r",
										Xml::OPTION_INDENTATION => '..',
								))
								->append('f')->append('e'),
						"<e>\r..<f>\r....<e/>\r..</f>\r</e>"
				),

				// setOption()
				array(
						Xml::c_('e')
								->setOption(Xml::OPTION_LINE_BREAK, Xml::CR)
								->setOption(Xml::OPTION_INDENTATION, '..')
								->append('f')->append('e'),
						"<e>\r..<f>\r....<e/>\r..</f>\r</e>"
				),

				// l_(), inLine()
				array(
						Xml::c_()
								->append('e')
								->append('f', 'content')->l_()
								->append('g', 'content'),
						"<e>\n\t<f>content<g>content</g></f>\n</e>"
				),
				array(
						Xml::c_()
								->l_()
								->append('e')
								->append('f', "lorem ipsum\ndolor ")
								->append('g', 'sit'),
						"<e><f>lorem ipsum dolor <g>sit</g></f></e>"
				),

				// c_(), createSub()
				array(
						Xml::c_('e')->append('f', 'content'),
						"<e>\n\t<f>content</f>\n</e>"
				),
				array(
						Xml::c_()->append('e', 'content'),
						"<e>content</e>"
				),
				array(
						Xml::c_()
								->append('e', 'content')->r_()
								->append('f', 'content'),
						"<e>content</e>\n<f>content</f>"
				),

				// inject()
				array(
						Xml::c_('e')->inject((new Xml)->append('f', 'content')->r_()),
						"<e>\n\t<f>content</f>\n</e>"
				),
				array(
						Xml::c_('e')->inject(Xml::c_('f')),
						"<e>\n\t<f/>\n</e>"
				),
				array(
						Xml::c_('e')->inject(Xml::c_('f', 'content')),
						"<e>\n\t<f>content</f>\n</e>"
				),
				array(
						Xml::c_('e')->inject((new Xml('f'))->append('g')->r_()),
						"<e>\n\t<f>\n\t\t<g/>\n\t</f>\n</e>"
				),
				array(
						(new Compact('e'))->inject((new Xml)
								->append('f1')->r_()->append('f2')->r_()),
						"<e><f1><f2></e>"
				),

				// cdata()
				array(
						Xml::c_()->append('e')->cdata(),
						"<e/>"
				),
				array(
						Xml::c_()->append('e', 'content')->cdata(),
						"<e><![CDATA[content]]></e>"
				),
				array(
						Xml::c_()->append('e')->cdata()->append('f', 'content'),
						"<e>\n<![CDATA[\n\t<f>content</f>\n]]>\n</e>"
				),

				// append()
				array(TestXml::c_()->append(null), ''),
				array(TestXml::c_()->append(''), ''),
				array(TestXml::c_()->append(null, 'content'), ''),
				array(TestXml::c_()->append('', 'content'), ''),
				array(TestXml::c_()->append('e'), '<e/>'),
				array(TestXml::c_()->append('e', ''), '<e/>'),
				array(TestXml::c_()->append('e', 'content'), '<e>content</e>'),
				array(TestSgml::c_()->append(null), ''),
				array(TestSgml::c_()->append(''), ''),
				array(TestSgml::c_()->append(null, 'content'), ''),
				array(TestSgml::c_()->append('', 'content'), ''),
				array(TestSgml::c_()->append('e'), '<e>'),
				array(TestSgml::c_()->append('e', ''), '<e></e>'),
				array(TestSgml::c_()->append('e', 'content'), '<e>content</e>'),
				array(
						TestXml::c_()->append('e', ['lorem', 'ipsum', 'dolor'])
								->attrib('foo', 'bar'),
						"<e foo=\"bar\">lorem</e>\n<e>ipsum</e>\n<e>dolor</e>"
				),

				// append to parent without name
				array(
						function () {
							$xml = Xml::c_()->append('e')->append('f')->append(null);
							$xml->append('g1');
							$xml->append('g2', 'content');
							return $xml;
						},
						"<e>\n\t<f>\n\t\t<g1/>\n\t\t<g2>content</g2>\n\t</f>\n</e>"
				),
				array(
						function () {
							$xml = Xml::c_();
							$xml->append('g1', 'content');
							$xml->append('g2');
							return Xml::c_()->append('e')->append('f')->inject($xml);
						},
						"<e>\n\t<f>\n\t\t<g1>content</g1>\n\t\t<g2/>\n\t</f>\n</e>"
				),

				// appendText()
				array(
						Xml::c_()->append('e')
								->appendText('Lorem')
								->appendText('Ipsum')
								->appendText('Dolor'),
						"<e>\n\tLorem\n\tIpsum\n\tDolor\n</e>"
				),
				array(
						Xml::c_()->append('e', '')
								->appendText(100)
								->appendText(200)
								->appendText(300),
						"<e>\n\t100\n\t200\n\t300\n</e>"
				),

				// comment()
				array(
						Xml::c_()->append('e')->comment('content'),
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

				// booleanAttrib()
				array(
						Html::c_('option', 'PHP')->setValue('php')->setSelected(),
						'<option value="php" selected>PHP</option>'
				),
				array(
						Html::c_('option', 'PHP')->setValue('php')->setSelected('php'),
						'<option value="php" selected>PHP</option>'
				),
				array(
						Html::c_('option', 'PHP')->setValue('php')
								->setSelected(['php', 'python']),
						'<option value="php" selected>PHP</option>'
				),
				array(
						Html::c_('option', 'PHP')->setValue('php')->setSelected(false),
						'<option value="php">PHP</option>'
				),
				array(
						Html::c_('option', 'PHP')->setValue('php')->setSelected('python'),
						'<option value="php">PHP</option>'
				),
				array(
						Html::c_('option', 'PHP')->setValue('php')
								->setSelected(['perl', 'python']),
						'<option value="php">PHP</option>'
				),

				// prepareContent(): content with linebreaks
				array(
						Xml::c_('e')->append('e', 'The quick brown fox
								jumps over the lazy dog.'),
						"<e>\n\t<e>The quick brown fox\n\tjumps over the lazy dog.</e>\n</e>"
				),
				array(
						Xml::c_('e')->append('e')->appendText('The quick brown fox
								jumps over the lazy dog.'),
						"<e>\n\t<e>\n\t\tThe quick brown fox\n\t\tjumps over the lazy dog.\n\t" .
						"</e>\n</e>"
				),
				array(
						Xml::c_('e')->l_()->append('e', 'The quick brown fox
								jumps over the lazy dog.'),
						"<e><e>The quick brown fox jumps over the lazy dog.</e></e>"
				),
				array(
						Xml::c_('e')->l_()->append('e')->appendText('The quick brown fox
								jumps over the lazy dog.'),
						"<e><e>The quick brown fox jumps over the lazy dog.</e></e>"
				),
				array(
						Xml::c_('e')
								->setOption(Xml::OPTION_TEXT_MODE, Xml::TEXT_MODE_NO_LTRIM)
								->append('e', 'The quick brown fox jumps over the lazy
	dog. The quick brown fox jumps over the
		lazy dog. The quick brown fox jumps over
			the lazy dog.'),
						'<e>
	<e>The quick brown fox jumps over the lazy
		dog. The quick brown fox jumps over the
			lazy dog. The quick brown fox jumps over
				the lazy dog.</e>
</e>'
				),
				array(
						Xml::c_('e')
								->setOption(Xml::OPTION_TEXT_MODE, Xml::TEXT_MODE_NO_LTRIM)
								->append('e')->appendText('The quick brown fox jumps over the lazy
	dog. The quick brown fox jumps over the
		lazy dog. The quick brown fox jumps over
			the lazy dog.'),
						'<e>
	<e>
		The quick brown fox jumps over the lazy
			dog. The quick brown fox jumps over the
				lazy dog. The quick brown fox jumps over
					the lazy dog.
	</e>
</e>'
				),

				// setLang(), setBase(), setId(), setSpace()
				array(Xml::c_('e')->setLang('en'), '<e xml:lang="en"/>'),
				array(
						Xml::c_('e')->setBase('http://example.com'),
						'<e xml:base="http://example.com"/>'
				),
				array(Xml::c_('e')->setId('foo'), '<e xml:id="foo"/>'),
				array(Xml::c_('e')->setSpace(), '<e xml:space="preserve"/>'),

				// setXmlns()
				array(
						Xml::c_('e')->setXmlns('http://www.w3.org/1999/xhtml'),
						'<e xmlns="http://www.w3.org/1999/xhtml"/>'
				),
				array(
						Xml::c_('e')->setXmlns('http://www.w3.org/1999/xhtml', 'xhtml'),
						'<e xmlns:xhtml="http://www.w3.org/1999/xhtml"/>'
				),

				// p_(), getParent()
				array(
						Xml::c_('e')->l_()->append('f')->append('g')->p_(),
						'<f><g/></f>', false
				),
				array(
						Xml::c_('e')->l_()->append('f')->append('g')->append('h')->p_(2),
						'<f><g><h/></g></f>', false
				),
				array(
						Xml::c_('e')->l_()->append('f')->append('g')->append('h')->p_(3),
						'<e><f><g><h/></g></f></e>', false
				),

				// r_(), getRoot()
				array(
						Xml::c_('e')->l_()->append('f')->append('g')->append('h')->r_(),
						'<e><f><g><h/></g></f></e>'
				),

				// t_(), pt_(), plt_()
				array(
						Xml::c_('e', 'lorem')->append('f', 'ipsum')->pt_('dolor'),
						"<e>\n\tlorem\n\t<f>ipsum</f>\n\tdolor\n</e>"
				),
				array(
						Xml::c_('e', 'lorem')->append('f', 'ipsum')->plt_('dolor'),
						"<e>lorem<f>ipsum</f>dolor</e>"
				),
		);
	}
}