<?php
// $Id: xml.php,v 1.2 2009/03/06 04:11:37 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-01 K.OHWADA
// sanitize()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_xml
//=========================================================

/**
 * Class webphoto_lib_xml
 */
class webphoto_lib_xml
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_xml
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    // --------------------------------------------------------
    // htmlspecialchars
    // http://www.w3.org/TR/REC-xml/#dt-markup
    // http://www.fxis.co.jp/xmlcafe/tmp/rec-xml.html#dt-markup
    //   &  -> &amp;    // without html entity
    //   <  -> &lt;
    //   >  -> &gt;
    //   "  -> &quot;
    //   '  -> &apos;
    // --------------------------------------------------------

    /**
     * @param $str
     * @return mixed|null|string|string[]
     */
    public function xml_text($str)
    {
        return $this->xml_htmlspecialchars_strict($str);
    }

    /**
     * @param $str
     * @return null|string|string[]
     */
    public function xml_url($str)
    {
        return $this->xml_htmlspecialchars_url($str);
    }

    /**
     * @param $str
     * @return null|string|string[]
     */
    public function xml_htmlspecialchars($str)
    {
        $str = $this->replace_control_code($str, '');
        $str = $this->replace_return_code($str);
        $str = htmlspecialchars($str, ENT_QUOTES | ENT_HTML5);
        $str = preg_replace("/'/", '&apos;', $str);

        return $str;
    }

    /**
     * @param $str
     * @return mixed|null|string|string[]
     */
    public function xml_htmlspecialchars_strict($str)
    {
        $str = $this->xml_strip_html_entity_char($str);
        $str = $this->xml_htmlspecialchars($str);
        $str = str_replace('?', '&#063;', $str);

        return $str;
    }

    /**
     * @param $str
     * @return null|string|string[]
     */
    public function xml_htmlspecialchars_url($str)
    {
        $str = preg_replace('/&amp;/sU', '&', $str);
        $str = $this->xml_strip_html_entity_char($str);
        $str = $this->xml_htmlspecialchars($str);

        return $str;
    }

    /**
     * @param      $str
     * @param bool $flag_control
     * @param bool $flag_undo
     * @return null|string|string[]
     */
    public function xml_cdata($str, $flag_control = true, $flag_undo = true)
    {
        $str = $this->replace_control_code($str, '');
        $str = $this->xml_undo_html_special_chars($str);

        // not sanitize
        $str = $this->xml_convert_cdata($str);

        return $str;
    }

    /**
     * @param $str
     * @return null|string|string[]
     */
    public function xml_convert_cdata($str)
    {
        return preg_replace('/]]>/', ']]&gt;', $str);
    }

    // --------------------------------------------------------
    // strip html entities
    //   &abc; -> ' '
    // --------------------------------------------------------

    /**
     * @param $str
     * @return null|string|string[]
     */
    public function xml_strip_html_entity_char($str)
    {
        return preg_replace('/&[0-9a-zA-z]+;/sU', ' ', $str);
    }

    // --------------------------------------------------------
    // undo XOOPS HtmlSpecialChars
    //   &lt;   -> <
    //   &gt;   -> >
    //   &quot; -> "
    //   &#039; -> '
    //   &amp;  -> &
    //   &amp;nbsp; -> &nbsp;
    // --------------------------------------------------------

    /**
     * @param $str
     * @return null|string|string[]
     */
    public function xml_undo_html_special_chars($str)
    {
        $str = preg_replace('/&gt;/i', '>', $str);
        $str = preg_replace('/&lt;/i', '<', $str);
        $str = preg_replace('/&quot;/i', '"', $str);
        $str = preg_replace('/&#039;/i', "'", $str);
        $str = preg_replace('/&amp;nbsp;/i', '&nbsp;', $str);

        return $str;
    }

    //---------------------------------------------------------
    // TAB \x09 \t
    // LF  \xOA \n
    // CR  \xOD \r
    //---------------------------------------------------------

    /**
     * @param        $str
     * @param string $replace
     * @return null|string|string[]
     */
    public function replace_control_code($str, $replace = ' ')
    {
        $str = preg_replace('/[\x00-\x08]/', $replace, $str);
        $str = preg_replace('/[\x0B-\x0C]/', $replace, $str);
        $str = preg_replace('/[\x0E-\x1F]/', $replace, $str);
        $str = preg_replace('/[\x7F]/', $replace, $str);

        return $str;
    }

    /**
     * @param        $str
     * @param string $replace
     * @return null|string|string[]
     */
    public function replace_return_code($str, $replace = ' ')
    {
        $str = preg_replace("/\n/", $replace, $str);
        $str = preg_replace("/\r/", $replace, $str);

        return $str;
    }

    //---------------------------------------------------------
    // sanitize
    //---------------------------------------------------------

    /**
     * @param $str
     * @return string
     */
    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    // --- class end ---
}
