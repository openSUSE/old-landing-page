<?php
/**
 * simple templating engine with overloading
 *
 * @author Andreas Demmer <mail@andreas-demmer.de>
 * @version 1.0.2
 */

/**
 * class templateLoop
 * loop for templating enginge
 */
class templateLoop {
    /**
     * content and loop-objects for loop
     *
     * @var array
     */
    protected $_content;

    /**
     * overloading function: sets content
     *
     * @return bool
     * @param string $key
     * @param mixed $val
     */
    public function __set($key, $value) {
        $this->_content[$key] = $value;
        return TRUE;
    }

    /**
     * overloading function: gets contentd
     *
     * @return mixed
     * @param string $key
     * @param mixed $value
     */
    public function __get($key) {
        return isset($this->_content[$key]) ? $this->_content[$key] : FALSE;
    }

    /**
     * adds repetition to loop and delivers its object
     *
     * @return object
     * @param string $loopName
     */
    public function addLoop($loopName) {
        if(!is_array($this->_content)) $this->_content = array();
        if(!isset($this->_content[$loopName])) $this->_content[$loopName] = array();

        $loopObject = new templateLoop();
        $this->_content[$loopName][] = $loopObject;

        return $loopObject;
    }
}

/**
 * class template
 * templating engine
 */
class template extends templateLoop {
    /**
     * path to templates
     *
     * @var string
     */
    protected $_templatePath;

    /**
     * filecontent of template
     *
     * @var string
     */
    protected $_template;

    /**
     * indicates whether template has been processed
     *
     * @var bool
     */
    protected $_parsed;

    /**
     * content for actual scope
     *
     * @var array
     */
    protected $_tempContent;

    /**
     * constructor: loads template
     *
     * @return void
     * @param string $templatePath
     */
    public function __construct($template) {
        $this->_templatePath = $template;
        $this->_template = NULL;
        $this->_parsed = FALSE;
        $this->_content = array();
        $this->getTemplate();

    }

    /**
     * loads template
     *
     * @return void
     * @param string $templatePath
     */
    public function setTemplate($template) {
        $this->_templatePath = $template;
        $this->_template = NULL;
        $this->_parsed = FALSE;
        $this->getTemplate();
    }

    /**
     * gets filecontent of template
     *
     * @return void
     */
    protected function getTemplate() {
        if(file_exists($this->_templatePath)) {
            $this->_template = file_get_contents($this->_templatePath);
        } else {
            $templateFile = preg_replace('|^.*/(.+)$|i', '\\1', $this->_templatePath);

            $details = array(
                'reason: file not found',
                'file: "'.$templateFile.'"',
            );

            throw new myException('Could not load template', $details, $this->_templatePath);
        }
    }

    /**
     * delivers processed template
     *
     * @return string
     */
    public function fetch() {
        if(!$this->_parsed) $this->parse();
        return $this->_template;
    }

    /**
     * processes template
     *
     * @return void
     */
    protected function parse() {
        $this->_template = $this->substituteLoops($this->_template, $this->_content);
        $this->_template = $this->substituteContentTags($this->_template, $this->_content);
        $this->_parsed = TRUE;
    }

    /**
     * gets content of a content tag
     *
     * @return bool
     * @param array $contentTag
     */
    protected function getContentOfContentTag($contentTag) {
        $contentName = preg_replace('|^\{([a-z0-9_\-]+)\}$|i', '\\1', $contentTag[0]);

        if(isset($this->_tempContent)) {
            if(!is_object($this->_tempContent) && !isset($this->_tempContent[$contentName]))  {
                $this->_tempContent[$contentName] = NULL;
            }

            $returnValue = ($this->_tempContent instanceof templateLoop) ? $this->_tempContent->$contentName : $this->_tempContent[$contentName];
        } else {
            $returnValue = FALSE;
        }

        return $returnValue;
    }

    /**
     * substitutes loops in string with content
     *
     * @return string
     * @param string $string
     * @param array $content
     */
    protected function substituteLoops($string, $content) {
        while(preg_match('/[^!\-]\[([a-z]+[a-z0-9_\-]*)\]/i', $string, $matches)) {
            $parsedLoop = NULL;
            $loopName = $matches[1];

            preg_match('|\['.$loopName.'\](.*?)\[/'.$loopName.'\]|is', $string, $matches);

            if(count($matches) == 0) {
                $detail =  '';
                $templateFile = preg_replace('|^.*/(.+)$|i', '\\1', $this->_templatePath);

                $details = array(
                    'reason: mismatched loop tag',
                    'loop: "'.$loopName.'"',
                    'template: "'.$templateFile.'"'
                );

                throw new myException('template could not be parsed', $details, $this->_templatePath);
            }

            $outlineString = $matches[0];
            $inlineString = $matches[1];

            if((is_array($content) && isset($content[$loopName]) && is_array($content[$loopName])) || (is_object($content) && is_array($content->$loopName))) {

                $contentArray = is_array($content) ? $content[$loopName] : $content->$loopName;

                foreach($contentArray as $loopContent) {
                    $inlineStringCopy = $inlineString;

                    $inlineStringCopy = $this->substituteLoops($inlineStringCopy, $loopContent);
                    $dummy = $this->substituteContentTags($inlineStringCopy, $loopContent);

                    $parsedLoop .= $dummy;
                }
            }

            $string = str_replace($outlineString, $parsedLoop, $string);
        }

        return $string;
    }

    /**
     * substitutes tags in string with content
     *
     * @return string
     * @param string $string
     * @param array $content
     */
    protected function substituteContentTags($string, $content) {
        $this->_tempContent = $content;
        return preg_replace_callback('|\{[a-z0-9_\-]+\}|i', array($this, 'getContentOfContentTag'), $string);
    }
}
?>