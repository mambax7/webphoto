<?php
/*
#
# QRcode image class library for PHP4  version 0.50beta9 (C)2002-2004,Y.Swetake
#
# This version supports QRcode model2 version 1-40.
#
*/

require __DIR__ . '/qrcode.php';

/**
 * Class Qrcode_image
 */
class Qrcode_image extends Qrcode
{
    public $module_size;
    public $quiet_zone;

    public function __construct()
    {
        parent::__construct();
        $this->module_size = 4;
        $this->quiet_zone = 4;
    }

    /**
     * @param $z
     */
    public function set_module_size($z)
    {
        if ($z > 0 && $z < 9) {
            $this->module_size = $z;
        }
    }

    /**
     * @param $z
     */
    public function set_quietzone($z)
    {
        if ($z > 0 && $z < 9) {
            $this->quiet_zone = $z;
        }
    }

    /**
     * @param        $org_data
     * @param string $image_type
     * @param string $filename
     */
    public function qrcode_image_out($org_data, $image_type = 'png', $filename = '')
    {
        $this->image_out($this->cal_qrcode($org_data), $image_type, $filename);
    }

    /**
     * @param        $data
     * @param string $image_type
     * @param string $filename
     */
    public function image_out($data, $image_type = 'png', $filename = '')
    {
        $im = $this->mkimage($data);
        if ('jpeg' == $image_type) {
            if (mb_strlen($filename) > 0) {
                imagejpeg($im, $filename);
            } else {
                imagejpeg($im);
            }
        } else {
            if (mb_strlen($filename) > 0) {
                imagepng($im, $filename);
            } else {
                imagepng($im);
            }
        }
    }

    /**
     * @param $data
     * @return resource
     */
    public function mkimage($data)
    {
        $data_array = explode("\n", $data);
        $c = count($data_array) - 1;
        $image_size = $c;
        $output_size = ($c + $this->quiet_zone * 2) * $this->module_size;

        $img = imagecreate($image_size, $image_size);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);

        $im = imagecreate($output_size, $output_size);

        $white2 = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $white2);

        $y = 0;
        foreach ($data_array as $row) {
            $x = 0;
            while ($x < $image_size) {
                if ('1' == mb_substr($row, $x, 1)) {
                    imagesetpixel($img, $x, $y, $black);
                }
                ++$x;
            }
            ++$y;
        }
        $quiet_zone_offset = $this->quiet_zone * $this->module_size;
        $image_width = $image_size * $this->module_size;

        imagecopyresized($im, $img, $quiet_zone_offset, $quiet_zone_offset, 0, 0, $image_width, $image_width, $image_size, $image_size);

        return $im;
    }
}
