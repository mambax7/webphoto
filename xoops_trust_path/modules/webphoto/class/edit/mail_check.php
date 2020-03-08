<?php
// $Id: mail_check.php,v 1.5 2011/11/12 17:17:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// webphoto_lib_mail
// 2011-11-03 K.OHWADA
// Function ereg() is deprecated
// 2011-05-16 K.OHWADA
// check_mail_addr()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_user_handler
// 2009-01-10 K.OHWADA
// webphoto_edit_mail_check -> webphoto_edit_mail_check
// 2008-08-24 K.OHWADA
// supported i-phone
//---------------------------------------------------------

//=========================================================
// class webphoto_edit_mail_check
// base on mailbbs's pop.php
//=========================================================

/**
 * Class webphoto_edit_mail_check
 */
class webphoto_edit_mail_check
{
    public $_config_class;
    public $_user_handler;
    public $_mime_class;
    public $_utility_class;
    public $_mail_class;

    public $_reject_msg_arr = [];
    public $_result;

    public $_BODY_REJECT_MAXBYTE = 200;    // 200 char
    public $_BODY_MAXBYTE = 1000;    // 1000 char
    public $_ATTACH_MAXBYTE = 1049000; // 1 MB

    public $_DENY_MAILER_PREG = '/(Oshirase|Microsoft\s*CDO|Mail\s*Magic|Easy\s*DM|Friend\s*Mailer|Extra\s*Japan|The\s*Bat|BSMTP|magmag|Blat|Douhou|DM\s*Mailer|IM2001|=\?ISO\-2202\-JP\?Q\?Q`dsV!0Ji;]Id9\?=)/i';

    public $_DENY_MAIL_FROM_ARRAY = [
        '163.com',
        'bigfoot.com',
        'boss.com',
        'mine.nu',
        '51444.tv',
        'nyan_nyan_cat_2004@yahoo.co.jp',
        'motto.zapto.org',
        'i-towns.net',
        'ori-g.net',
        'jewelry.polty.cc',
        'birabira4u.com',
        'wecl-online.com',
    ];

    // Function ereg() is deprecated
    // the underlinea are continued 25 or more
    public $_PREG_UNDERLINE_25 = '/[_]{25,}/';

    // 03-5321-1111
    public $_PREG_TEL_FORMAT_1 = "/[\d\-]{12,13}/";

    // 0353211111
    public $_PREG_TEL_FORMAT_2 = "/\d{10,11}/";

    public $_MARK_ASTERISK_10 = '**********';

    public $_AD_WORD_ARRAY = [
        'http://auction.msn.co.jp/',
        'Do You Yahoo!?',
        'Yahoo! BB is Broadband by Yahoo!',
        'http://bb.yahoo.co.jp/',
        'http://messenger.msn.co.jp',
    ];

    // for i-phone
    public $_ALLOW_BODY_US_ASCII = true;
    public $_US_ASCII = 'us-ascii';

    public $_FLAG_STRICT = true;

    public $_MAILTO = null;
    public $_DENY_TITLE_PREG = null;
    public $_DENY_BODY_PREG = null;
    public $_REMOVE_WORD_ARRAY = null;

    public $_allowed_mimes = null;
    public $_allowed_exts = null;
    public $_allowed_charsets = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_mail_check constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->set_deny_title_preg(_WEBPHOTO_MAIL_DENY_TITLE_PREG);
        $this->set_deny_body_preg(_WEBPHOTO_MAIL_DENY_TITLE_PREG);
        $this->push_ad_word_array(_WEBPHOTO_MAIL_AD_WORD_1);
        $this->push_ad_word_array(_WEBPHOTO_MAIL_AD_WORD_2);

        $this->_user_handler = webphoto_user_handler::getInstance($dirname, $trust_dirname);
        $this->_mime_class = webphoto_mime::getInstance($dirname, $trust_dirname);

        $this->_config_class = webphoto_config::getInstance($dirname);
        $this->_utility_class = webphoto_lib_utility::getInstance();
        $this->_mail_class = webphoto_lib_mail::getInstance();

        $cfg_fsize = $this->_config_class->get_by_name('fsize');
        $cfg_mail_addr = $this->_config_class->get_by_name('mail_addr');
        $cfg_mail_charset = $this->_config_class->get_by_name('mail_charset');

        $this->set_attach_maxbyte($cfg_fsize);
        $this->set_mailto($cfg_mail_addr);
        $this->set_allowed_charset_list($cfg_mail_charset);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_mail_check
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_flag_strict($val)
    {
        $this->_FLAG_STRICT = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_body_maxbyte($val)
    {
        $this->_BODY_MAXBYTE = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_attach_maxbyte($val)
    {
        $this->_ATTACH_MAXBYTE = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_charset_local($val)
    {
        $this->_CHARSET_LOCAL = $val;
    }

    /**
     * @param $val
     */
    public function set_mailto($val)
    {
        $this->_MAILTO = $val;
    }

    /**
     * @param $val
     */
    public function set_deny_title_preg($val)
    {
        $this->_DENY_TITLE_PREG = $val;
    }

    /**
     * @param $val
     */
    public function set_deny_body_preg($val)
    {
        $this->_DENY_BODY_PREG = $val;
    }

    /**
     * @param $str
     */
    public function push_ad_word_array($str)
    {
        if ($str) {
            array_push($this->_AD_WORD_ARRAY, $str);
        }
    }

    /**
     * @param $arr
     */
    public function set_remove_word_array($arr)
    {
        $this->_REMOVE_WORD_ARRAY = $arr;
    }

    /**
     * @param $val
     */
    public function set_allowed_mimes($val)
    {
        $this->_allowed_mimes = $val;
    }

    /**
     * @param $val
     */
    public function set_allowed_exts($val)
    {
        $this->_allowed_exts = $val;
    }

    /**
     * @param $val
     */
    public function set_allowed_charset_list($val)
    {
        $list = explode('|', $val);
        $arr = [];
        foreach ($list as $v) {
            $v = trim($v);
            if ($v) {
                $arr[] = mb_strtolower($v);
            }
        }
        $this->_allowed_charsets = $arr;
    }

    //---------------------------------------------------------
    // parse_mail
    //---------------------------------------------------------

    /**
     * @param $groups
     */
    public function set_mail_groups($groups)
    {
        if (!is_array($groups)) {
            $groups = [$groups];
        }

        list($allowed_mimes, $allowed_exts) = $this->_mime_class->get_allowed_mimes_by_groups($groups);

        $this->set_allowed_mimes($allowed_mimes);
        $this->set_allowed_exts($allowed_exts);
    }

    /**
     * @param $param
     * @return bool
     */
    public function check_mail($param)
    {
        $this->clear_reject_msg();

        $this->_result = $param;
        $return_code = true;

        $mail_to = $param['mail_to'];
        $mail_from = $param['mail_from'];
        $reply_to = $param['reply_to'];
        $return_path = $param['return_path'];
        $mailer = $param['mailer'];
        $charset = $param['charset'];
        $date = $param['date'];
        $subject = $param['subject'];
        $attaches = $param['attaches'];
        $bodies = $param['bodies'];

        if (!$this->check_mailto($mail_to)) {
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        if (!$this->check_mailer($mailer)) {
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        if (!$this->check_charset($charset)) {
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        if (!$this->check_subject($subject)) {
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        if (!$this->check_mail_from($mail_from)) {
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        if (!$this->check_mail_from($reply_to)) {
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        if (!$this->check_mail_from($return_path)) {
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        if (!$this->check_bodies($bodies)) {
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        if (!is_array($attaches)) {
            $this->set_reject_msg('no attach file');
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        $mail_from = $this->select_mail_from($param);

        if (!$this->check_exists_email($mail_from)) {
            $return_code = false;
            if ($this->_FLAG_STRICT) {
                return false;
            }
        }

        list($subject, $rotate) = $this->parse_subject_rotate($subject);

        $param['mail_from'] = $mail_from;
        $param['subject'] = $subject;
        $param['rotate'] = $rotate;
        $param['body'] = $this->proofread_bodies($bodies);
        $param['attaches'] = $this->check_attaches($attaches);

        $this->_result = $param;

        return $return_code;
    }

    public function get_result()
    {
        return $this->_result;
    }

    /**
     * @param $mailto
     * @return bool
     */
    public function check_mailto($mailto)
    {
        if (!$this->check_mail_addr($mailto)) {
            $this->set_reject_msg('not allow format in mailto : ' . $mailto);

            return false;
        }
        $pattern = '/' . quotemeta($this->_MAILTO) . '/';
        if ($mailto && !preg_match($pattern, $mailto)) {
            $this->set_reject_msg('not allow mailto : ' . $mailto);

            return false;
        }

        return true;
    }

    /**
     * @param $mailer
     * @return bool
     */
    public function check_mailer($mailer)
    {
        if ($mailer && $this->_DENY_MAILER_PREG) {
            if (preg_match($this->_DENY_MAILER_PREG, $mailer)) {
                $this->set_reject_msg('not allow mailer : ' . $mailer);

                return false;
            }
        }

        return true;
    }

    /**
     * @param      $charset
     * @param bool $flag_us_ascii
     * @return bool
     */
    public function check_charset($charset, $flag_us_ascii = false)
    {
        // no check if not detect charset
        if (empty($charset)) {
            return true;
        }

        // no check if not set config
        if (!is_array($this->_allowed_charsets) || !count($this->_allowed_charsets)) {
            return true;
        }

        // ok if in config
        if (in_array(mb_strtolower($charset), $this->_allowed_charsets)) {
            return true;
        }

        // ok if us_ascii
        if ($flag_us_ascii && (mb_strtolower($charset) == $this->_US_ASCII)) {
            return true;
        }

        $this->set_reject_msg('not allow charset : ' . $charset);

        return false;
    }

    /**
     * @param $subject
     * @return bool
     */
    public function check_subject($subject)
    {
        if ($subject && $this->_DENY_TITLE_PREG) {
            if (preg_match($this->_DENY_TITLE_PREG, $subject)) {
                $this->set_reject_msg('not allow word in subject : ' . $subject);

                return false;
            }
        }

        return true;
    }

    /**
     * @param $mail_from
     * @return bool
     */
    public function check_mail_from($mail_from)
    {
        if (empty($mail_from)) {
            return true;
        }
        if (!$this->check_mail_addr($mail_from)) {
            $this->set_reject_msg('not allow format in from mail : ' . $mail_from);

            return false;
        }
        for ($i = 0; $i < count($this->_DENY_MAIL_FROM_ARRAY); ++$i) {
            // Function ereg() is deprecated
            $pattern = '/' . $this->_DENY_MAIL_FROM_ARRAY[$i] . '/i';
            if (preg_match($pattern, $mail_from)) {
                $this->set_reject_msg('not allow from mail : ' . $mail_from);

                return false;
            }
        }

        return true;
    }

    /**
     * @param $email
     * @return bool
     */
    public function check_exists_email($email)
    {
        $row = $this->_user_handler->get_cached_row_by_email($email);
        if (!is_array($row)) {
            $this->set_reject_msg('not allow from mail : ' . $email);

            return false;
        }

        return true;
    }

    /**
     * @param $subject
     * @return array
     */
    public function parse_subject_rotate($subject)
    {
        $rotate = null;

        if ($subject && preg_match('/(.*)(?:(r|l)@)$/i', $subject, $match)) {
            $subject = rtrim($match[1]);
            $rl = mb_strtolower($match[2]);
            if ('r' == $rl) {
                $rotate = 'rot90';
            } elseif ('l' == $rl) {
                $rotate = 'rot270';
            }
        }

        return [$subject, $rotate];
    }

    /**
     * @param $param
     */
    public function select_mail_from($param)
    {
        if ($param['mail_from']) {
            return $param['mail_from'];
        } elseif ($param['reply_to']) {
            return $param['reply_to'];
        } elseif ($param['return_path']) {
            return $param['return_path'];
        }

        return null;
    }

    //---------------------------------------------------------
    // check body
    //---------------------------------------------------------

    /**
     * @param $bodies
     * @return bool
     */
    public function check_bodies($bodies)
    {
        if (!is_array($bodies) || !count($bodies)) {
            return true;
        }

        foreach ($bodies as $body) {
            $ret = $this->check_single_body($body);
            if (!$ret) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $body
     * @return bool
     */
    public function check_single_body($body)
    {
        $text = $body['text'];
        $html = $body['html'];
        $plane = $body['plane'];
        $charset = $body['charset'];
        $type = $body['type'];

        if (!$this->check_charset($charset, $this->_ALLOW_BODY_US_ASCII)) {
            return false;
        }

        if (!$this->check_body_text($text)) {
            return false;
        }

        if (!$this->check_body_text($html)) {
            return false;
        }

        if (!$this->check_body_text($plane)) {
            return false;
        }

        return true;
    }

    /**
     * @param $text
     * @return bool
     */
    public function check_body_text($text)
    {
        if ($text && null !== $this->_DENY_BODY_PREG && $this->_DENY_BODY_PREG) {
            if (preg_match($this->_DENY_BODY_PREG, $text)) {
                $msg = 'not allow word in body';
                $msg .= $this->shorten_text($text, $this->_BODY_REJECT_MAXBYTE);
                $this->set_reject_msg($msg);

                return false;
            }
        }

        return true;
    }

    /**
     * @param $text
     * @param $max
     * @return string
     */
    public function shorten_text($text, $max)
    {
        if (mb_strlen($text) > $max) {
            $text = mb_substr($text, 0, $max) . '...';
        }

        return $text;
    }

    //---------------------------------------------------------
    // proofread body
    //---------------------------------------------------------

    /**
     * @param $bodies
     * @return null|string
     */
    public function proofread_bodies($bodies)
    {
        if (!is_array($bodies) || !count($bodies)) {
            return null;
        }

        $str = '';
        foreach ($bodies as $body) {
            $str .= $this->proofread_single_body($body);
        }

        return $str;
    }

    /**
     * @param $body
     * @return mixed|null|string|string[]
     */
    public function proofread_single_body($body)
    {
        if ($body['plane']) {
            $text = $body['plane'];
        } else {
            $text = $body['text'];
        }

        $text = $this->replace_return_code($text);
        $text = $this->replace_tel($text);
        $text = $this->remove_underline($text);
        $text = $this->remove_word($text);
        $text = $this->remove_del_reg($text);
        $text = $this->shorten_text($text, $this->_BODY_MAXBYTE);

        return $text;
    }

    /**
     * @param $text
     * @return mixed|null|string|string[]
     */
    public function replace_return_code($text)
    {
        $text = str_replace("\r\n", "\r", $text);
        $text = str_replace("\r", "\n", $text);
        $text = preg_replace("/\n{2,}/", "\n\n", $text);

        return $text;
    }

    /**
     * @param $text
     * @return null|string|string[]
     */
    public function replace_tel($text)
    {
        // Function ereg() is deprecated
        $text = preg_replace($this->_PREG_TEL_FORMAT_1, $this->_MARK_ASTERISK_10, $text);
        $text = preg_replace($this->_PREG_TEL_FORMAT_2, $this->_MARK_ASTERISK_10, $text);

        return $text;
    }

    /**
     * @param $text
     * @return null|string|string[]
     */
    public function remove_underline($text)
    {
        // Function ereg() is deprecated
        return preg_replace($this->_PREG_UNDERLINE_25, '', $text);
    }

    /**
     * @param $text
     * @return mixed
     */
    public function remove_word($text)
    {
        if (is_array($this->_AD_WORD_ARRAY)) {
            foreach ($this->_AD_WORD_ARRAY as $delstr) {
                $text = str_replace($delstr, '', $text);
            }
        }

        return $text;
    }

    /**
     * @param $text
     * @return null|string|string[]
     */
    public function remove_del_reg($text)
    {
        if (is_array($this->_REMOVE_WORD_ARRAY)) {
            foreach ($this->_REMOVE_WORD_ARRAY as $delstr) {
                if ($delstr) {
                    $text = preg_replace($delstr, '', $text);
                }
            }
        }

        return $text;
    }

    //---------------------------------------------------------
    // attach
    //---------------------------------------------------------

    /**
     * @param $attaches
     * @return array
     */
    public function check_attaches($attaches)
    {
        $arr = [];

        foreach ($attaches as $attach) {
            $filename = $attach['filename'];
            $content = $attach['content'];
            $charset = $attach['charset'];
            $type = $attach['type'];
            $ext = '';

            if ($filename) {
                $ext = $this->_utility_class->parse_ext($filename);
            }

            $msg = [];
            if (empty($ext)) {
                $msg[] = 'no ext';
            } elseif (!$this->check_attach_ext($ext)) {
                $msg[] = 'not allow ext : ' . $ext;
            }
            if (!$this->check_attach_mimetype($type)) {
                $msg[] = 'not allow mime : ' . $type;
            }
            if (!$this->check_attach_maxbyte($content)) {
                $msg[] = 'over maxbyte';
            }

            $attach['ext'] = $ext;
            $attach['reject'] = implode("\n", $msg);
            $arr[] = $attach;
        }

        return $arr;
    }

    /**
     * @param $ext
     * @return bool
     */
    public function check_attach_ext($ext)
    {
        if ($ext && in_array($ext, $this->_allowed_exts)) {
            return true;
        }

        return false;
    }

    /**
     * @param $type
     * @return bool
     */
    public function check_attach_mimetype($type)
    {
        if ($type && in_array($type, $this->_allowed_mimes)) {
            return true;
        }

        return false;
    }

    /**
     * @param $content
     * @return bool
     */
    public function check_attach_maxbyte($content)
    {
        if (mb_strlen($content) > $this->_ATTACH_MAXBYTE) {
            return false;
        }

        return true;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param $addr
     * @return bool
     */
    public function check_mail_addr($addr)
    {
        return $this->_mail_class->check_valid_addr($addr);
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_reject_msg()
    {
        $this->_reject_msg_arr = [];
    }

    /**
     * @param $mail
     */
    public function set_reject_msg($mail)
    {
        $this->_reject_msg_arr[] = $mail;
    }

    /**
     * @return array
     */
    public function get_reject_msgs()
    {
        return $this->_reject_msg_arr;
    }

    // --- class end ---
}
