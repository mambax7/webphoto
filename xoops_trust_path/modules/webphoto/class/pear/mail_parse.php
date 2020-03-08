<?php
// $Id: mail_parse.php,v 1.1 2011/05/10 03:02:30 ohwada Exp $

//=========================================================
// webphoto module
// 2011-05-01 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_lib_mail_parse
//=========================================================

/**
 * Class webphoto_pear_mail_parse
 */
class webphoto_pear_mail_parse extends webphoto_pear_mail_decode
{
    public $_result = null;

    public $_MAILER_LIST = ['x_mailer', 'x_mail_agent', 'user_agent'];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \webphoto_pear_mail_decode|\webphoto_pear_mail_parse
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // parse_mail
    //---------------------------------------------------------

    /**
     * @param $mail_text
     * @return bool
     */
    public function parse_mail($mail_text)
    {
        $param = $this->decode($mail_text);

        $date = $this->_get_header_by_key($param, 'date');

        list($bodies, $gps) = $this->_bodies($param);

        $this->_result = [
            'mail_to' => $this->_get_header_by_key($param, 'to_addr'),
            'mail_from' => $this->_get_header_by_key($param, 'from_addr'),
            'subject' => $this->_get_header_by_key($param, 'subject'),
            'charset' => $this->_get_header_by_key($param, 'charset'),
            'reply_to' => $this->_get_header_by_key($param, 'reply_to'),
            'return_path' => $this->_get_header_by_key($param, 'return_path'),
            'date' => $date,
            'datetime' => $this->build_datetime($date),
            'attaches' => $this->_attaches($param),
            'mailer' => $this->_mailer($param),
            'bodies' => $bodies,
            'gps' => $gps,
        ];

        return true;
    }

    public function get_result()
    {
        return $this->_result;
    }

    /**
     * @param $param
     * @return false|int
     */
    public function _datetime($param)
    {
        $date = $this->_get_header_by_key($param, $key);

        return $this->build_datetime($date);
    }

    /**
     * @param $param
     * @return array
     */
    public function _bodies($param)
    {
        $body = isset($param['body']) ? $param['body'] : null;
        if (!$body) {
            return [null, null];
        }

        $ctype_secondary = isset($body['ctype_secondary']) ? $body['ctype_secondary'] : null;
        if (!$ctype_secondary) {
            return [null, null];
        }

        $type = $body['ctype_primary'] . '/' . $ctype_secondary;
        $text = $body['body_converted'];
        $html = null;
        $plane = null;
        $gps = null;

        switch ($ctype_secondary) {
            case 'plain':
                $docomo = $this->parse_gps_docomo($text);
                if (isset($docomo['flag']) && $docomo['flag']) {
                    $gps = $docomo;
                }
                break;
            case 'html':
                $html = $text;
                $plane = preg_replace('#<head>.*</head>#is', '', $html);
                $plane = strip_tags($plane);
                break;
        }

        $arr = [
            'text' => $text,
            'html' => $html,
            'plane' => $plane,
            'charset' => $body['charset'],
            'type' => $type,
        ];

        return [[$arr], $gps];
    }

    /**
     * @param $param
     * @return array|bool
     */
    public function _attaches($param)
    {
        $attaches = $param['attaches'];
        if (!is_array($attaches) || !count($attaches)) {
            return false;
        }
        $arr = [];
        foreach ($attaches as $attache) {
            $charset = isset($attache['charset']) ? $attache['charset'] : null;
            $type = $attache['ctype_primary'] . '/' . $attache['ctype_secondary'];

            $arr[] = [
                'filename' => $attache['name'],
                'content' => $attache['body'],
                'charset' => $charset,
                'type' => $type,
            ];
        }

        return $arr;
    }

    /**
     * @param $param
     */
    public function _mailer($param)
    {
        foreach ($this->_MAILER_LIST as $key) {
            $mailer = $this->_get_header_by_key($param, $key);
            if ($mailer) {
                return $mailer;
            }
        }

        return null;
    }

    /**
     * @param $param
     * @return array|null
     */
    public function _gps($param)
    {
        $str = $this->_get_body_by_key($param, 'body_converted');
        $gps = $this->parse_gps_docomo($str);
        if (isset($gps['flag']) && $gps['flag']) {
            return $gps;
        }

        return null;
    }

    /**
     * @param $param
     * @param $key
     */
    public function _get_header_by_key($param, $key)
    {
        $str = isset($param['headers'][$key]) ? $param['headers'][$key] : null;

        return $str;
    }

    /**
     * @param $param
     * @param $key
     */
    public function _get_body_by_key($param, $key)
    {
        $str = isset($param['body'][$key]) ? $param['body'][$key] : null;

        return $str;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param $date
     * @return false|int
     */
    public function build_datetime($date)
    {
        $time = strtotime($date);
        if ($time <= 0) {
            $time = time();
        }

        return $time;
    }

    //---------------------------------------------------------
    // gps
    //---------------------------------------------------------

    /**
     * @param $data
     * @return array
     */
    public function parse_gps_docomo($data)
    {
        // http://www.nttdocomo.co.jp/service/imode/make/content/gps/index.html
        // http://www.docomo.co.jp/gps.cgi?lat=%2B35.00.35.600&lon=%2B135.41.35.600&geo=wgs84&x-acc=3
        // http://docomo.ne.jp/cp/map.cgi?lat=%2B35.00.35.600&lon=%2B135.41.35.600&geo=wgs84

        $flag = false;
        $gmap_lat = null;
        $gmap_lon = null;
        $docomo_lat = null;
        $docomo_lon = null;

        $pattern = '/http:\/\/.*?docomo.*jp\/.*lat=(%2B[\d\.]+)&lon=(%2B[\d\.]+)/i';

        if (preg_match($pattern, $data, $match)) {
            $flag = true;
            $docomo_lat = rawurldecode($match[1]);
            $docomo_lon = rawurldecode($match[2]);
            $gmap_lat = $this->parse_gps_docomo_lonlat($docomo_lat);
            $gmap_lon = $this->parse_gps_docomo_lonlat($docomo_lon);
        }

        $arr = [
            'flag' => $flag,
            'gmap_latitude' => $gmap_lat,
            'gmap_longitude' => $gmap_lon,
            'docomo_latitude' => $docomo_lat,
            'docomo_longitude' => $docomo_lon,
        ];

        return $arr;
    }

    /**
     * @param $str
     * @return float|int
     */
    public function parse_gps_docomo_lonlat($str)
    {
        $arr = explode('.', $str);
        $fig = 0;
        if (isset($arr[0])) {
            $fig += (float)$arr[0];
        }
        if (isset($arr[1])) {
            $fig += (float)$arr[1] / 60;
        }
        if (isset($arr[2])) {
            $fig += (float)$arr[2] / 3600;
        }
        if (isset($arr[3])) {
            $fig += (float)$arr[3] / 3600000;
        }

        return $fig;
    }

    // --- class end ---
}
