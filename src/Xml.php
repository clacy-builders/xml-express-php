<?php

namespace ML_Express;

/**
 * Simplifies respectively unifies the creation of XML documents.
 *
 */
class Xml extends Markup
{
	const MIME_TYPE = 'application/xml';
	const FILENAME_EXTENSION = 'xml';

	/** Most commonly '1.0' */
	const XML_VERSION = self::XML_VERSION_1_0;

	const CHARACTER_ENCODING = 'UTF-8';

	/** Whether the document should begin with a xml declaration or not. */
	const XML_DECLARATION = true;

	const DOCTYPE = null;
	const HTML_MODE = false;
	const XML_NAMESPACE = null;
	const NAMESP_PREFIX = '';

	const DEFAULT_LINE_BREAK = self::LF;
	const DEFAULT_INDENTATION = self::HT;
	const DEFAULT_LTRIM = true;
	const DEFAULT_TEXT_MODE = self::TEXT_MODE_DEFAULT;

	/** This option defines the characters used for line breaks. */
	const OPTION_LINE_BREAK = 'lineBreak';

	/** This option defines the characters used for indentation. */
	const OPTION_INDENTATION = 'indentation';

	const OPTION_TEXT_MODE = 'textMode';

	const TEXT_MODE_DEFAULT = 0;
	const TEXT_MODE_PREPEND = 1;
	const TEXT_MODE_NO_LTRIM = 2;

	const XML_VERSION_1_0 = '1.0';
	const XML_VERSION_1_1 = '1.1';
	const LF = "\n";
	const CR = "\r";
	const HT = "\t";

	const CDATA_START = '<![CDATA[';
	const CDATA_STOP = ']]>';

	protected $cdata;
	protected $processingInstr;
	protected $options;
	protected $children;
	protected $parent;
	protected $root;
	protected $ancestor;
	protected $sub = false;

	/**
	 * The constructor is used internally to create child elements.
	 *
	 * You must not overwrite the constructor. Write a static factory method instead.
	 *
	 * @param  string  $name      Name of the new element.
	 * @param  string  $content   Content of the new element.
	 * @param  Xml     $root      The root element.
	 * @param  Xml     $ancestor  Nearest ancestor.
	 * @param  Xml     $parent
	 */
	public final function __construct(
			$name = null,
			$content = null,
			Xml $root = null,
			Xml $ancestor = null,
			Xml $parent = null)
	{
		if (isset($root)) {
			$this->setRoot($root);
			$this->setAncestor($ancestor);
		}
		else {
			$this->setRoot($this);
			$this->setAncestor($this);
			if (isset($name)) {
				$this->getAncestor()->options = [
						self::OPTION_LINE_BREAK => static::DEFAULT_LINE_BREAK,
						self::OPTION_INDENTATION => static::DEFAULT_INDENTATION,
						self::OPTION_TEXT_MODE => static::DEFAULT_TEXT_MODE
				];
			}
			$this->processingInstr = array();
		}
		$this->name = $name;
		$this->content = $content;
		$this->cdata = false;
		$class = get_class($this->getRoot());
		$this->attributes = new Attributes($this);
		$this->children = array();
		$this->parent = $parent;
	}

	/**
	 * Creates a subtree.
	 *
	 * Use this function, if you don't want to start your tree with the root element.
	 *
	 * @param  string  $name     Name of the element.
	 * @param  string  $content  Content of the element.
	 * @return Xml
	 */
	public static function createSub($name = '', $content = null)
	{
		$class = get_called_class();
		$element = new $class($name, $content);
		$element->sub = true;
		return $element;
	}

	/**
	 * Returns the root element.
	 *
	 * @return Xml
	 */
	public function getRoot()
	{
		return $this->root->isRoot() ? $this->root: $this->root->getRoot();
	}

	/**
	 * Returns the parent element.
	 *
	 * @param  int|null  $n  Number of repetitions (>=2).
	 * @return Xml
	 */
	public function getParent($n = null)
	{
		return ($n > 1) ? $this->parent->getParent(--$n) : $this->parent;
	}

	/**
	 * Returns n-th child element.
	 *
	 * @param  int  $index
	 * @return Xml
	 */
	public function getChild($index = 0)
	{
		return $this->children[$index];
	}

	/**
	 * Removes a child element.
	 *
	 * @param  int  $index
	 * @return Xml
	 */
	public function removeChild($index)
	{
		unset($this->children[$index]);
		return $this;
	}

	/**
	 * Sets options for the current element and its childs.
	 *
	 * Options currently provided are:
	 * <ul>
	 * <li>OPTION_LINE_BREAK
	 * <li>OPTION_INDENTATION
	 * <li>OPTION_TEXT_MODE
	 * </ul>
	 *
	 * @param  array  $options  Use one of the constants listed above as array key.
	 * @return Xml
	 */
	public function setOptions($options)
	{
		if (!isset($options[self::OPTION_LINE_BREAK])
				&& !isset($options[self::OPTION_INDENTATION])
				&& !isset($options[self::OPTION_TEXT_MODE]))
			return $this;
		if (!$this->isAncestor()) {
			$prevAncestor = $this->getAncestor();
			$this->setAncestor($this);
			$this->options = [
					self::OPTION_LINE_BREAK => $prevAncestor->options[self::OPTION_LINE_BREAK],
					self::OPTION_INDENTATION => $prevAncestor->options[self::OPTION_INDENTATION],
					self::OPTION_TEXT_MODE => $prevAncestor->options[self::OPTION_TEXT_MODE]
			];
		}
		$this->setAncestorOption($options, self::OPTION_LINE_BREAK);
		$this->setAncestorOption($options, self::OPTION_INDENTATION);
		$this->setAncestorOption($options, self::OPTION_TEXT_MODE);
		return $this;
	}

	/**
	 * Sets a single option for the current element and its childs.
	 *
	 * Example:<br><code>$element->setOption(OPTION_INDENTATION, '  ');</code>
	 *
	 * @param  string  $key
	 * @param  string  $value
	 * @return Xml
	 */
	public function setOption($key, $value)
	{
		return $this->setOptions([$key => $value]);
	}

	/**
	 * Child elements appears in same line.
	 *
	 * A shortcut for <code>setOption(self::OPTION_LINE_BREAK, '')</code>
	 *
	 * @return Xml
	 */
	public function inLine()
	{
		return $this->setOption(self::OPTION_LINE_BREAK, '');
	}

	/**
	 * Subordinated content, elements will be inserted into a CDATA section.
	 *
	 * @param  boolean  $cdata
	 * @return Xml
	 */
	public function cdata($cdata = true)
	{
		$this->cdata = $cdata;
		return $this;
	}

	/**
	 * Adds a child element.
	 *
	 * @param  string       $name     Name of the new element.
	 * @param  string|null  $content  Content of the new element.
	 * @return Xml
	 */
	public function append($name, $content = null)
	{
		if (is_array($content)) {
			$first = $this->append($name, $content[0]);
			for ($i = 1; $i < count($content); $i++) {
				$this->append($name, $content[$i]);
			}
			return $first;
		}
		return $this->appendChild($this->newChild($name, $content));
	}

	/**
	 * Appends a previously created subtree.
	 *
	 * @param  Xml  $element  Root element of the subtree.
	 * @return Xml
	 */
	public function inject(Xml $element)
	{
		$element->setAncestor($this->getAncestor());
		$element->setRoot($this->getRoot());
		$element->parent = $this;
		return $this->appendChild($element);
	}

	/**
	 * Appends a text line.
	 *
	 * @param  string  $text
	 * @return Xml
	 */
	public function appendText($text = '')
	{
		$this->appendChild((string) $text);
		return $this;
	}

	/**
	 * Appends a comment.
	 *
	 * @param  string  $content  The comment.
	 * @return Xml
	 */
	public function comment($content)
	{
		return $this->appendText('<!-- ' . $content . ' -->');
	}

	/**
	 * Generates the desired markup.
	 *
	 * @param  string  $indentation  Initial indentation.
	 * @return string
	 */
	public function getMarkup($indentation = '')
	{
		$line = $this->getOption(self::OPTION_LINE_BREAK);
		$tab = $this->getOption(self::OPTION_INDENTATION);

		$xmlString = '';
		if (empty($this->name)) {
			$last = count($this->children) - 1;
			foreach ($this->children as $i => $child) {
				$xmlString .= $child->getMarkup($indentation);
				if ($i < $last) {
					$xmlString .= $line;
				}
			}
			return $xmlString;
		}

		// no linebreak -> no tabspace
		$indent1 = $indentation;
		if ($line == '') {
			$indent2 = '';
			$indent3 = '';
		}
		else {
			$indent2 = $indentation . $tab;
			$indent3 = $indentation;
		}

		if ($this->isRoot() && !$this->sub) {
			if (static::XML_DECLARATION) {
				$xmlString .= $this->xmlDecl();
			}
			if (count($this->processingInstr) > 0) {
				foreach ($this->processingInstr as $instr) {
					$xmlString .= $instr->getMarkup() . $line;
				}
			}
			if (static::DOCTYPE) {
				$xmlString .= static::DOCTYPE . $line;
			}
		}
		if (count($this->children) > 0) {
			$xmlString .= $indent1 . $this->openingTag() . $line;
			if ($this->cdata) {
				$xmlString .= $indent3 . self::CDATA_START . $line;
			}
			if ($this->content != null) {
				$xmlString .= $indent2 . $this->prepareContent(
						$this->content, $indent2, $line) . $line;
			}
			foreach ($this->children as $child) {
				if (is_string($child)) {
					$xmlString .= $indent2 . $this->prepareContent(
							$child, $indent2, $line) . $line;
				}
				else {
					$xmlString .= $child->getMarkup($indent2) . $line;
				}
			}
			if ($this->cdata) {
				$xmlString .= $indent3 . self::CDATA_STOP . $line;
			}
			$xmlString .= $indent3 . $this->closingTag();
		}
		else {
			$xmlString .= $indent1 . $this->element($indent1, $line, $this->cdata);
		}
		return $xmlString;
	}

	/**
	 * Sets the <code>xml:lang</code> attribute.
	 *
	 * @param  string  $lang  A BCP 47 language tag. For example 'en' or 'fr-CA'.
	 * @return Xml
	 */
	public function setLang($lang)
	{
		return $this->attrib('xml:lang', $lang);
	}

	const SPACE_DEFAULT = 'default';
	const SPACE_PRESERVE = 'preserve';

	/**
	 * Sets the <code>xml:space</code> attribute.
	 *
	 * @param  string  $space  <code>Xml::SPACE_PRESERVE</code> or <code>Xml::SPACE_DEFAULT</code>.
	 * @return Xml
	 */
	public function setSpace($space = self::SPACE_PRESERVE) {
		return $this->attrib('xml:space', $space);
	}

	/**
	 * Sets the <code>xml:base</code> attribute.
	 *
	 * @param  string  $base
	 * @return Xml
	 */
	public function setBase($base)
	{
		return $this->attrib('xml:base', $base);
	}

	/**
	 * Sets the <code>xml:id</code> attribute.
	 *
	 * @param  string  $id
	 * @return Xml
	 */
	public function setId($id)
	{
		return $this->attrib('xml:id', $id);
	}

	/**
	 * Sets the <code>xmlns</code> attribute.
	 *
	 * @param  string|array  $uri
	 * @param  string|null   $prefix
	 * @return Xml
	 */
	public function setXmlns($uri, $prefix = null)
	{
		if (is_array($uri)) {
			foreach ($uri as $prefix => $uri) {
				$this->setXmlns($uri, $prefix);
			}
			return $this;
		}
		return $this->attrib(empty($prefix) ? 'xmlns' : 'xmlns:' . $prefix, $uri);
	}

	public function addProcessingInstr($target, $content = null)
	{
		return $this->processingInstr[] =
				ProcessingInstruction::createProcessingInstruction($target, $content);
	}

	/**
	 * Shortcut for <code>getParent()</code>.
	 *
	 * @param  int|null  $n  Number of repetitions (>=2).
	 */
	public function p_($n = null)
	{
		return $this->getParent($n);
	}

	/**
	 * Shortcut for <code>getRoot()</code>.
	 *
	 * @return Xml
	 */
	public function r_()
	{
		return $this->getRoot();
	}

	/**
	 * Shortcut for <code>inLine()</code>.
	 *
	 * @return Xml
	 */
	public function l_()
	{
		return $this->inLine();
	}

	/**
	 * Shortcut for <code>appendText()</code>.
	 *
	 * @param  string  $text
	 * @return Xml
	 */
	public function t_($text = '')
	{
		return $this->appendText($text);
	}

	/**
	 * Shortcut for <code>createSub()</code>.
	 *
	 * @param  string       $name
	 * @param  string|null  $content
	 * @return Xml
	 */
	public static function c_($name = '', $content = null)
	{
		return static::createSub($name, $content);
	}

	/**
	 * Shortcut for<br>
	 * <code>::createSub()->inLine()</code>.
	 *
	 * @param  string       $name
	 * @param  string|null  $content
	 * @return Xml
	 */
	public static function cl_($name = '', $content = null)
	{
		return static::createSub($name, $content)->inLine();
	}

	/**
	 * Shortcut for<br>
	 * <code>->getParent()->appendText()</code>.
	 *
	 * @param  string  $text
	 * @return Xml
	 */
	public function pt_($text)
	{
		return $this->getParent()->appendText($text);
	}

	/**
	 * Shortcut for<br>
	 * <code>->getParent()->inLine()->appendText()</code>.
	 *
	 * @param  string  $text
	 * @return Xml
	 */
	public function plt_($text)
	{
		return $this->getParent()->inLine()->appendText($text);
	}

	/**
	 * Returns the markup of the root element and all its childs
	 * when current object is treated like a string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getRoot()->getMarkup();
	}

	/**
	 * Sets the header fields "Content-Type" and "Content-Disposition".
	 *
	 * Considers <code>MIME_TYPE</code>, <code>CHARACTER_ENCODING</code> and
	 * <code>FILENAME_EXTENSION</code>.
	 *
	 * @param  string   $filename      Eventually without extension.
	 * @param  boolean  $addExtension  Whether the filename extension should be appended or not.
	 */
	public static function headerfields($filename = null, $addExtension = true)
	{
		if (!empty($filename)) {
			header(self::getContentDispositionHeaderfield($filename, $addExtension));
		}
		header(self::getContentTypeHeaderfield());
	}

	/**
	 * Creates string for "Content-Disposition" header field.
	 *
	 * Considers <code>FILENAME_EXTENSION</code>.
	 *
	 * @param  string   $filename      Eventually without extension.
	 * @param  boolean  $addExtension  Whether the filename extension should be appended or not.
	 * @return string
	 */
	public static function getContentDispositionHeaderfield($filename, $addExtension = true)
	{
		if ($addExtension) {
			$filename .= '.' . static::FILENAME_EXTENSION;
		}
		return sprintf("Content-Disposition: attachment; filename=\"%s\"", $filename);
	}

	/**
	 * Creates string for "Content-type" header field.
	 *
	 * Considers <code>MIME_TYPE</code> and <code>CHARACTER_ENCODING</code>.
	 *
	 * @return string
	 */
	public static function getContentTypeHeaderfield()
	{
		return sprintf('Content-Type: %s; charset=%s',
				static::MIME_TYPE, static::CHARACTER_ENCODING);
	}

	/**
	 * Returns <code>options</code> array.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return $this->ancestor->options;
	}

	/**
	 * Returns single option.
	 *
	 * @param  string  $name One of the following: <code>Xml::OPTION_LINE_BREAK</code>,
	 *                       <code>Xml::OPTION_INDENTATION</code> or
	 *                       <code>Xml::OPTION_TEXT_MODE</code>.
	 * @return mixed
	 */
	protected function getOption($name)
	{
		return $this->ancestor->options[$name];
	}

	/**
	 * Returns value of the <code>ancestor</code> property.
	 *
	 * @return Xml
	 */
	protected function getAncestor()
	{
		return $this->ancestor;
	}

	/**
	 * Whether this element is the root element or not.
	 *
	 * @return boolean
	 */
	protected function isRoot()
	{
		return $this->root === $this;
	}

	/**
	 * Whether options set for this element or not.
	 *
	 * @return boolean
	 */
	protected function isAncestor()
	{
		return $this->ancestor === $this;
	}

	/**
	 * Sets the value for the <code>root</code> property.
	 *
	 * @param  Xml  $root
	 */
	protected function setRoot(Xml $root)
	{
		$this->root = $root;
	}

	/**
	 * Sets the value for the <code>ancestor</code> property.
	 *
	 * @param  Xml  $ancestor
	 */
	protected function setAncestor(Xml $ancestor)
	{
		$this->ancestor = $ancestor;
	}

	/**
	 * Appends a element to the <code>children</code> array.
	 *
	 * @param  Xml|string  $element
	 * @return Xml|string
	 */
	protected function appendChild($element)
	{
		$this->children[] = $element;
		return $element;
	}

	/**
	 * Creates a child element.
	 *
	 * @param  string  $name
	 * @param  string  $content
	 * @return Xml
	 */
	protected function newChild($name, $content = null)
	{
		$class = get_class($this);
		return new $class($name, $content, $this->getRoot(), $this->getAncestor(), $this);
	}

	/**
	 * Intended for call inside format specific public factory method.
	 *
	 * @param  string  $name        Name of the root element.
	 * @param  array   $namespaces  Key: prefix; value: uri.
	 * @return Xml
	 */
	protected static function createRoot($name, $namespaces = [])
	{
		$class = get_called_class();
		return (new $class($name, ''))->setXmlns(static::XML_NAMESPACE)->setXmlns($namespaces);
	}

	private function setAncestorOption($options, $key)
	{
		if (isset($options[$key])) {
			$this->getAncestor()->options[$key] = $options[$key];
		}
	}

	/* element without Children */
	private function element($indent, $line, $cdata)
	{
		if ($this->content === null || (empty($this->content) && !static::HTML_MODE)) {
			return $this->standaloneTag();
		}
		else {
			$content = $this->prepareContent($this->content, $indent, $line);
			if ($cdata) {
				$content = self::CDATA_START . $content . self::CDATA_STOP;
			}
			return $this->openingTag() . $content . $this->closingTag();
		}
	}

	private function openingTag()
	{
		return '<' . static::NAMESP_PREFIX . $this->name . $this->attributes->str() . '>';
	}

	private function closingTag()
	{
		return '</' . static::NAMESP_PREFIX . $this->name . '>';
	}

	private function standaloneTag()
	{
		return '<' . static::NAMESP_PREFIX . $this->name .
				$this->attributes->str() . (static::HTML_MODE ? '>' : '/>');
	}

	private function xmlDecl()
	{
		return ProcessingInstruction::createProcessingInstruction('xml')
				->attrib('version', static::XML_VERSION)
				->attrib('encoding', static::CHARACTER_ENCODING) .
						$this->getOption(self::OPTION_LINE_BREAK);
	}

	private function prepareContent($content, $indent, $line)
	{
		if ($this->getOption(self::OPTION_TEXT_MODE) == self::TEXT_MODE_PREPEND)
			return $content;
		if ($line) {
			$content = str_replace(["\r\n", "\r"], "\n", $content);
			$content = explode("\n", $content);
		}
		else {
			$content = str_replace(["\r\n", "\r", "\n"], ' ', $content);
			$content = str_replace("\t", '', $content);
			return $content;
		}
		if ($this->getOption(self::OPTION_TEXT_MODE) != self::TEXT_MODE_NO_LTRIM) {
			foreach ($content as $i => $row) {
				$content[$i] = ltrim($row);
			}
		}
		return implode($line . $indent, $content);
	}
}