<?php
// $Id: multibyte.php,v 1.9 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// @iconv_strlen
// 2010-06-06 K.OHWADA
// BUG : forget return
// 2009-08-08 K.OHWADA
// build_config_mbstring()
// 2009-05-17 K.OHWADA
// changed build_summary_with_search()
// 2009-01-25 K.OHWADA
// str_replace_continuous_return_code()
// 2009-01-10 K.OHWADA
// build_summary_with_search()
// 2008-06-26 K.OHWADA
// fatal error in rss
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_multibyte
//=========================================================
class webphoto_lib_multibyte
{
    public $_is_japanese = false;

    public $_JA_KUTEN   = null;
    public $_JA_DOKUTEN = null;
    public $_JA_PERIOD  = null;
    public $_JA_COMMA   = null;

    public $_TRUST_NAME = 'WEBPHOTO';
    public $_FUNC_SEL   = 1;   // iconv

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->set_encoding(_CHARSET);
        $this->set_func_sel_by_const();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_lib_multibyte();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // func sel
    //---------------------------------------------------------
    public function set_func_sel_by_const()
    {
        $name = strtoupper('_C_' . $this->_TRUST_NAME . '_MULTIBYTE_FUNC_SEL');
        if (defined($name)) {
            $this->set_func_sel(constant($name));
        }
    }

    public function set_func_sel($val)
    {
        $this->_FUNC_SEL = (int)$val;
    }

    public function get_func_sel()
    {
        return $this->_FUNC_SEL;
    }

    //---------------------------------------------------------
    // config
    //---------------------------------------------------------
    public function build_config_priority()
    {
        $str = 'multibyte function priority: ';
        if ($this->_FUNC_SEL) {
            $str .= 'iconv';
        } else {
            $str .= 'mbstring';
        }
        $str .= "<br />\n";
        return $str;
    }

    public function build_config_iconv()
    {
        $str = '';
        if (function_exists('iconv_get_encoding')) {
            $vars = iconv_get_encoding();
            foreach ($vars as $k => $v) {
                $str .= 'iconv.' . $k . ': ' . $v . "<br />\n";
            }
        } else {
            $str .= $this->font_red('iconv: not loaded') . "<br />\n";
        }
        return $str;
    }

    public function build_config_mbstring()
    {
        $str = '';
        if (function_exists('mb_internal_encoding')) {
            $str .= 'mbstring.language: ' . mb_language() . "<br />\n";
            $str .= 'mbstring.detect_order: ' . implode(' ', mb_detect_order()) . "<br />\n";
            $str .= $this->build_ini_val('mbstring.http_input') . "<br />\n";
            $str .= 'mbstring.http_output: ' . mb_http_output() . "<br />\n";
            $str .= 'mbstring.internal_encoding: ' . mb_internal_encoding() . "<br />\n";
            $str .= $this->build_ini_val('mbstring.script_encoding') . "<br />\n";
            $str .= $this->build_ini_val('mbstring.substitute_character') . "<br />\n";
            $str .= $this->build_ini_val('mbstring.func_overload') . "<br />\n";
            $str .= $this->build_ini_int('mbstring.encoding_translation') . "<br />\n";
            $str .= $this->build_ini_int('mbstring.strict_encoding') . "<br />\n";
        } else {
            $str .= $this->font_red('mbstring: not loaded') . "<br />\n";
        }

        return $str;
    }

    public function build_ini_int($key)
    {
        $str = $key . ': ' . (int)ini_get($key);
        return $str;
    }

    public function build_ini_val($key)
    {
        $str = $key . ': ' . ini_get($key);
        return $str;
    }

    public function font_red($str)
    {
        $str = '<span style="color:#ff0000; font-weight:bold;">' . $str . '</span>';
        return $str;
    }

    //---------------------------------------------------------
    // encoding
    //---------------------------------------------------------
    public function set_encoding($charset)
    {
        $this->i_set_encoding('input_encoding', $charset);
        $this->i_set_encoding('output_encoding', $charset);
        $this->i_set_encoding('internal_encoding', $charset);
        $this->mb_set_internal_encoding($charset);
        return true;    // dummy
    }

    public function i_set_encoding($type, $charset)
    {
        if (function_exists('iconv_get_encoding')
            && function_exists('iconv_set_encoding')
        ) {
            $current = iconv_get_encoding($type);
            if (strtolower($current) == strtolower($charset)) {
                return true;
            }
            if (PHP_VERSION_ID < 50600) {
                $ret = iconv_set_encoding('internal_encoding', $charset);
            } else {
                $ret = ini_set('default_charset', $charset);
            }
            if ($ret === false) {
                if (PHP_VERSION_ID < 50600) {
                    iconv_set_encoding('internal_encoding', $current);
                } else {
                    ini_set('default_charset', $current);
                }
            }
            return $ret;
        }
        return true;    // dummy
    }

    public function mb_set_internal_encoding($charset)
    {
        if (function_exists('mb_internal_encoding')) {
            $current = mb_internal_encoding();
            if (strtolower($current) == strtolower($charset)) {
                return true;
            }
            $ret = mb_internal_encoding($charset);
            if ($ret === false) {
                mb_internal_encoding($current);
            }
            return $ret;
        }
        return true;    // dummy
    }

    public function i_iconv_get_encoding($type)
    {
        if (function_exists('iconv_get_encoding')) {
            return iconv_get_encoding($type);
        }
        return null;    // dummy
    }

    public function i_iconv_set_encoding($type, $charset)
    {
        if (function_exists('iconv_set_encoding')) {
            return iconv_set_encoding($type, $charset);
        }
        return true;    // dummy
    }

    public function m_mb_internal_encoding($encoding = null)
    {
        if (function_exists('mb_internal_encoding')) {
            if ($encoding) {
                return mb_internal_encoding($encoding);
            } else {
                return mb_internal_encoding();
            }
        }
        return true;    // dummy
    }

    public function m_mb_language($language = null)
    {
        if (function_exists('mb_language')) {
            if ($language) {
                return mb_language($language);
            } else {
                return mb_language();
            }
        }
    }

    public function m_mb_detect_encoding($str, $encoding_list = null, $strict = null)
    {
        if (function_exists('mb_detect_encoding')) {
            if ($encoding_list && $strict) {
                return mb_detect_encoding($str, $encoding_list, $strict);
            } elseif ($encoding_list) {
                return mb_detect_encoding($str, $encoding_list);
            }
            return mb_detect_encoding($str);
        }
        return false;
    }

    public function exists_convert_encoding()
    {
        if (function_exists('mb_convert_encoding')) {
            return true;
        }
        if (function_exists('iconv')) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // convert
    //---------------------------------------------------------
    public function convert_to_utf8($str, $encoding = _CHARSET)
    {
        if ($this->_FUNC_SEL && function_exists('iconv')) {
            return $this->i_iconv($encoding, 'UTF-8', $str);
        }
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($str, $encoding, 'UTF-8');
        }
        if (function_exists('iconv')) {
            return $this->i_iconv($encoding, 'UTF-8', $str);
        }

        // BUG : forget return
        return utf8_encode($str);
    }

    public function convert_from_utf8($str, $encoding = _CHARSET)
    {
        if ($this->_FUNC_SEL && function_exists('iconv')) {
            return $this->i_iconv('UTF-8', $encoding, $str);
        }
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($str, 'UTF-8', $encoding);
        }
        if (function_exists('iconv')) {
            return $this->i_iconv('UTF-8', $encoding, $str);
        }

        // BUG : forget return
        return utf8_decode($str);
    }

    public function convert_encoding($str, $to, $from)
    {
        if (strtolower($to) == strtolower($from)) {
            return $str;
        }
        if ($this->_FUNC_SEL && function_exists('iconv')) {
            return $this->i_iconv($from, $to, $str);
        }
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($str, $to, $from);
        }
        if (function_exists('iconv')) {
            return $this->i_iconv($from, $to, $str);
        }
        return $str;
    }

    public function i_iconv($from, $to, $str, $extra = '//IGNORE')
    {
        if (function_exists('iconv')) {
            return iconv($from, $to . $extra, $str);
        }
        return $str;
    }

    public function m_mb_convert_encoding($str, $to, $from = null)
    {
        if (function_exists('mb_convert_encoding')) {
            if ($from) {
                return mb_convert_encoding($str, $to, $from);
            } else {
                return mb_convert_encoding($str, $to);
            }
        }
        return $str;
    }

    public function convert_space_zen_to_han($str)
    {
        if (function_exists('mb_convert_kana')) {
            return mb_convert_kana($str, 's');
        }
        return $str;
    }

    public function m_mb_convert_kana($str, $option = 'KV', $encoding = null)
    {
        if (function_exists('mb_convert_kana')) {
            if ($encoding) {
                return mb_convert_kana($str, $option, $encoding);
            } else {
                return mb_convert_kana($str, $option);
            }
        }
        return $str;
    }

    //---------------------------------------------------------
    // strlen
    //---------------------------------------------------------
    public function str_len($str, $charset = null)
    {
        if ($this->_FUNC_SEL && function_exists('iconv_strlen')) {
            return $this->i_iconv_strlen($str, $charset);
        }
        if (function_exists('mb_strlen')) {
            return $this->m_mb_strlen($str, $charset);
        }
        if (function_exists('iconv_strlen')) {
            return $this->i_iconv_strlen($str, $charset);
        }
        return strlen($str);
    }

    public function i_iconv_strlen($str, $charset = null)
    {
        if (function_exists('iconv_strlen')) {
            if ($charset) {
                return @iconv_strlen($str, $charset);
            } else {
                return @iconv_strlen($str);
            }
        }
        return strlen($str);
    }

    public function m_mb_strlen($str, $encoding = null)
    {
        if (function_exists('mb_strlen')) {
            if ($encoding) {
                return mb_strlen($str, $encoding);
            } else {
                return mb_strlen($str);
            }
        }
        return strlen($str);
    }

    //---------------------------------------------------------
    // strpos
    //---------------------------------------------------------
    public function str_pos($haystack, $needle, $offset = 0, $charset = null)
    {
        if ($this->_FUNC_SEL && function_exists('iconv_strpos')) {
            return $this->i_iconv_strpos($haystack, $needle, $offset, $charset);
        }
        if (function_exists('mb_strpos')) {
            return $this->m_mb_strpos($haystack, $needle, $offset, $charset);
        }
        if (function_exists('iconv_strpos')) {
            return $this->i_iconv_strpos($haystack, $needle, $offset, $charset);
        }
        return strpos($haystack, $needle, $offset);
    }

    public function i_iconv_strpos($haystack, $needle, $offset = 0, $charset = null)
    {
        if (function_exists('iconv_strpos')) {
            if ($charset) {
                return iconv_strpos($haystack, $needle, $offset, $charset);
            } elseif ($offset) {
                return iconv_strpos($haystack, $needle, $offset);
            } else {
                return iconv_strpos($haystack, $needle);
            }
        }
        return strpos($haystack, $needle, $offset);
    }

    public function m_mb_strpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        if (function_exists('mb_strpos')) {
            if ($encoding) {
                return mb_strpos($haystack, $needle, $offset, $encoding);
            } elseif ($offset) {
                return mb_strpos($haystack, $needle, $offset);
            } else {
                return mb_strpos($haystack, $needle);
            }
        }
        return strpos($haystack, $needle, $offset);
    }

    //---------------------------------------------------------
    // strrpos
    //---------------------------------------------------------
    public function str_rpos($haystack, $needle, $offset = 0, $charset = null)
    {
        if ($this->_FUNC_SEL && function_exists('iconv_strrpos')) {
            return $this->i_iconv_strrpos($haystack, $needle, $offset, $charset);
        }
        if (function_exists('mb_strrpos')) {
            return $this->m_mb_strrpos($haystack, $needle, $offset, $charset);
        }
        if (function_exists('iconv_strrpos')) {
            return $this->i_iconv_strrpos($haystack, $needle, $offset, $charset);
        }
        return strrpos($haystack, $needle, $offset);
    }

    public function i_iconv_strrpos($haystack, $needle, $offset = 0, $charset = null)
    {
        if (function_exists('iconv_strrpos')) {
            if ($charset) {
                return iconv_strrpos($haystack, $needle, $offset, $charset);
            } else {
                return iconv_strrpos($haystack, $needle, $offset);
            }
        }
        return strrpos($haystack, $needle, $offset);
    }

    public function m_mb_strrpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        if (function_exists('mb_strrpos')) {
            if ($encoding) {
                return mb_strrpos($haystack, $needle, $offset, $encoding);
            } else {
                return mb_strrpos($haystack, $needle, $offset);
            }
        }
        return strrpos($haystack, $needle, $offset);
    }

    //---------------------------------------------------------
    // substr
    //---------------------------------------------------------
    public function sub_str($str, $start, $length = 0, $charset = null)
    {
        if ($this->_FUNC_SEL && function_exists('iconv_substr')) {
            return $this->i_iconv_substr($str, $start, $length, $charset);
        }
        if (function_exists('mb_substr')) {
            return $this->m_mb_substr($str, $start, $length, $charset);
        }
        if (function_exists('iconv_substr')) {
            return $this->i_iconv_substr($str, $start, $length, $charset);
        }
        return substr($str, $start, $length);
    }

    public function i_iconv_substr($str, $start, $length = 0, $charset = null)
    {
        if (function_exists('iconv_substr')) {
            if ($charset) {
                return iconv_substr($str, $start, $length, $charset);
            } else {
                return iconv_substr($str, $start, $length);
            }
        }
        return substr($str, $start, $length);
    }

    public function m_mb_substr($str, $start, $length = 0, $encoding = null)
    {
        if (function_exists('mb_substr')) {
            if ($encoding) {
                return mb_substr($str, $start, $length, $encoding);
            } else {
                return mb_substr($str, $start, $length);
            }
        }
        return substr($str, $start, $length);
    }

    //---------------------------------------------------------
    // other
    //---------------------------------------------------------
    public function m_mb_http_output($encoding = null)
    {
        if (function_exists('mb_http_output')) {
            if ($encoding) {
                return mb_http_output($encoding);
            } else {
                return mb_http_output();
            }
        }
        return false;
    }

    public function m_mb_send_mail($mailto, $subject, $message, $headers = null, $parameter = null)
    {
        if (function_exists('mb_send_mail')) {
            if ($parameter) {
                return mb_send_mail($mailto, $subject, $message, $headers, $parameter);
            } elseif ($headers) {
                return mb_send_mail($mailto, $subject, $message, $headers);
            } else {
                return mb_send_mail($mailto, $subject, $message);
            }
        }
        if ($parameter) {
            return mail($mailto, $subject, $message, $headers, $parameter);
        } elseif ($headers) {
            return mail($mailto, $subject, $message, $headers);
        }
        return mail($mailto, $subject, $message);
    }

    public function m_mb_ereg_replace($pattern, $replace, $string, $option = null)
    {
        if (function_exists('mb_ereg_replace')) {
            if ($option) {
                return mb_ereg_replace($pattern, $replace, $string, $option);
            } else {
                return mb_ereg_replace($pattern, $replace, $string);
            }
        }
    }

    //---------------------------------------------------------
    // shorten strings
    // max: plus=shorten, 0=null, -1=unlimited
    //---------------------------------------------------------
    public function shorten($str, $max, $tail = ' ...')
    {
        $text = $str;
        if (($max > 0) && ($this->str_len($str) > $max)) {
            $text = $this->sub_str($str, 0, $max) . $tail;
        } elseif ($max == 0) {
            $text = null;
        }
        return $text;
    }

    //---------------------------------------------------------
    // build summary
    //---------------------------------------------------------
    public function build_summary($str, $max, $tail = ' ...', $is_japanese = false)
    {
        $str = $this->build_plane_text($str, $is_japanese);
        $str = $this->str_replace_return_code($str);
        $str = $this->str_replace_continuous_space_code($str);
        $str = $this->str_set_empty_if_only_space($str);
        $str = $this->shorten($str, $max, $tail);
        return $str;
    }

    public function build_plane_text($str, $is_japanese = false)
    {
        if ($is_japanese || $this->_is_japanese) {
            $str = $this->convert_space_zen_to_han($str);
            $str = $this->str_add_space_after_punctuation_ja($str);
        }

        $str = $this->str_add_space_after_tag($str);
        $str = strip_tags($str);
        $str = $this->str_replace_control_code($str);
        $str = $this->str_replace_tab_code($str);
        $str = $this->str_replace_return_to_nl_code($str);
        $str = $this->str_replace_html_space_code($str);
        $str = $this->str_replace_continuous_space_code($str);
        $str = $this->str_replace_space_return_code($str);
        $str = $this->str_replace_continuous_return_code($str);
        return $str;
    }

    public function str_add_space_after_tag($str)
    {
        return $this->str_add_space_after_str('>', $str);
    }

    public function str_add_space_after_punctuation($str)
    {
        $str = $this->str_add_space_after_str(',', $str);
        $str = $this->str_add_space_after_str('.', $str);
        return $str;
    }

    public function str_add_space_after_str($word, $string)
    {
        return str_replace($word, $word . ' ', $string);
    }

    public function str_set_empty_if_only_space($str)
    {
        $temp = $this->str_replace_space_code($str, '');
        if (strlen($temp) == 0) {
            $str = '';
        }
        return $str;
    }

    //---------------------------------------------------------
    // TAB \x09 \t
    // LF  \xOA \n
    // CR  \xOD \r
    //---------------------------------------------------------
    public function str_replace_control_code($str, $replace = ' ')
    {
        $str = preg_replace('/[\x00-\x08]/', $replace, $str);
        $str = preg_replace('/[\x0B-\x0C]/', $replace, $str);
        $str = preg_replace('/[\x0E-\x1F]/', $replace, $str);
        $str = preg_replace('/[\x7F]/', $replace, $str);
        return $str;
    }

    public function str_replace_tab_code($str, $replace = ' ')
    {
        return preg_replace("/\t/", $replace, $str);
    }

    public function str_replace_return_code($str, $replace = ' ')
    {
        $str = preg_replace("/\n/", $replace, $str);
        $str = preg_replace("/\r/", $replace, $str);
        return $str;
    }

    public function str_replace_return_to_nl_code($str, $replace = "\n")
    {
        $str = preg_replace("/\r/", $replace, $str);
        return $str;
    }

    public function str_replace_continuous_return_code($str, $replace = "\n")
    {
        return preg_replace("/[\n|\r]+/", $replace, $str);
    }

    public function str_replace_space_return_code($str, $replace = "\n")
    {
        return preg_replace("/[\x20][\n|\r]/", $replace, $str);
    }

    public function str_replace_html_space_code($str, $replace = ' ')
    {
        return preg_replace('/&nbsp;/i', $replace, $str);
    }

    public function str_replace_space_code($str, $replace = ' ')
    {
        return preg_replace("/[\x20]/", $replace, $str);
    }

    public function str_replace_continuous_space_code($str, $replace = ' ')
    {
        return preg_replace("/[\x20]+/", $replace, $str);
    }

    //---------------------------------------------------------
    // summary
    //---------------------------------------------------------
    public function build_summary_with_search($text, $words, $len = 255, $head = '... ', $tail = ' ...')
    {
        // strip spaces
        $text = ltrim(preg_replace('/\s+/', ' ', $text));

        // return full when less than length
        if (strlen($text) <= $len) {
            return $text;
        }

        // return part from head when no search word
        if (!is_array($words)) {
            return $this->sub_str($text, 0, $len);
        }

        // array(aa,bb,cc) -> aa|bb|cc
        $q_word = str_replace(' ', '|', preg_quote(implode(' ', $words), '/'));

        // return part from head when no match
        if (!preg_match("/$q_word/i", $text, $match)) {
            return $this->sub_str($text, 0, $len);
        }

        $half = (int)($len / 2);

        $center = $match[0];
        list($left, $right) = preg_split("/$q_word/i", $text, 2);

        $len_l = $this->str_len($left);
        $len_r = $this->str_len($right);
        $len_c = $this->str_len($center);

        // pert from head when less than length
        if (($len_l + $len_c) <= $len) {
            $ret = $this->sub_str($text, 0, $len);
            $ret .= $tail;

            // part from tail when less than half length
        } elseif (($len_r + $len_c) <= $len) {
            $ret = $head;
            $ret .= $this->sub_str($text, -$len, $len);
        } else {
            if ($len_l <= $half) {
                $ret = $left;
            } else {
                $left_start = $len_l - $half + 1;
                $ret        = $head;
                $ret .= $this->sub_str($left, $left_start, $half);
            }
            $ret .= $match[0];
            $remainder = $len - $this->str_len($ret);
            if ($len_r <= $remainder) {
                $ret .= $right;
            } else {
                $ret .= $this->sub_str($right, 0, $remainder);
                $ret .= $tail;
            }
        }

        return $ret;
    }

    //---------------------------------------------------------
    // for japanese
    //---------------------------------------------------------
    public function str_add_space_after_punctuation_ja($str)
    {
        $str = $this->add_space_after_str_ja($str, $this->_JA_KUTEN);
        $str = $this->add_space_after_str_ja($str, $this->_JA_DOKUTEN);
        $str = $this->add_space_after_str_ja($str, $this->_JA_PERIOD);
        $str = $this->add_space_after_str_ja($str, $this->_JA_COMMA);
        return $str;
    }

    public function add_space_after_str_ja($str, $word)
    {
        if ($word) {
            return $this->m_mb_ereg_replace($word, $word . ' ', $str);
        }
        return $str;
    }

    public function set_is_japanese($val)
    {
        $this->_is_japanese = $val;
    }

    public function set_ja_kuten($val)
    {
        $this->_JA_KUTEN = $val;
    }

    public function set_ja_dokuten($val)
    {
        $this->_JA_DOKUTEN = $val;
    }

    public function set_ja_period($val)
    {
        $this->_JA_PERIOD = $val;
    }

    public function set_ja_comma($val)
    {
        $this->_JA_COMMA = $val;
    }

    // --- class end ---
}
