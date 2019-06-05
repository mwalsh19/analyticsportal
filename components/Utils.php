<?php

namespace app\components;

use Yii;
use yii\helpers\Url;

class Utils {

    const REFERRER_CODE = 'Ad Media';
    const TOTAL = 'Total';
    const HIRED = 'Hired';
    const ATTENDING_ACADEMY = 'Attending Academy';
    const NOT_QUALIFIED = 'Not Qualified';
    const NOT_INTERESTED = 'Not Interested';
    const NO_RESPONSE = 'No Response';
    const DUPLICATE_APP = 'Duplicate App';
    const UNQUILIFIED_DA = 'Unqualified Da';
    const DO_NOT_CONTACT = 'Do Not Contact';

    public static $possible_headers = array(
        self::REFERRER_CODE,
        self::TOTAL,
        self::HIRED,
        self::ATTENDING_ACADEMY,
        self::NOT_QUALIFIED,
        self::NOT_INTERESTED,
        self::NO_RESPONSE,
        self::DUPLICATE_APP,
        self::UNQUILIFIED_DA,
        self::DO_NOT_CONTACT);

    public static function getStateArray() {
        return[
            'AL' => 'ALABAMA',
            'AK' => 'ALASKA',
            'AS' => 'AMERICAN SAMOA',
            'AZ' => 'ARIZONA',
            'AR' => 'ARKANSAS',
            'CA' => 'CALIFORNIA',
            'CO' => 'COLORADO',
            'CT' => 'CONNECTICUT',
            'DE' => 'DELAWARE',
            'DC' => 'DISTRICT OF COLUMBIA',
            'FM' => 'FEDERATED STATES OF MICRONESIA',
            'FL' => 'FLORIDA',
            'GA' => 'GEORGIA',
            'GU' => 'GUAM GU',
            'HI' => 'HAWAII',
            'ID' => 'IDAHO',
            'IL' => 'ILLINOIS',
            'IN' => 'INDIANA',
            'IA' => 'IOWA',
            'KS' => 'KANSAS',
            'KY' => 'KENTUCKY',
            'LA' => 'LOUISIANA',
            'ME' => 'MAINE',
            'MH' => 'MARSHALL ISLANDS',
            'MD' => 'MARYLAND',
            'MA' => 'MASSACHUSETTS',
            'MI' => 'MICHIGAN',
            'MN' => 'MINNESOTA',
            'MS' => 'MISSISSIPPI',
            'MO' => 'MISSOURI',
            'MT' => 'MONTANA',
            'NE' => 'NEBRASKA',
            'NV' => 'NEVADA',
            'NH' => 'NEW HAMPSHIRE',
            'NJ' => 'NEW JERSEY',
            'NM' => 'NEW MEXICO',
            'NY' => 'NEW YORK',
            'NC' => 'NORTH CAROLINA',
            'ND' => 'NORTH DAKOTA',
            'MP' => 'NORTHERN MARIANA ISLANDS',
            'OH' => 'OHIO',
            'OK' => 'OKLAHOMA',
            'OR' => 'OREGON',
            'PW' => 'PALAU',
            'PA' => 'PENNSYLVANIA',
            'PR' => 'PUERTO RICO',
            'RI' => 'RHODE ISLAND',
            'SC' => 'SOUTH CAROLINA',
            'SD' => 'SOUTH DAKOTA',
            'TN' => 'TENNESSEE',
            'TX' => 'TEXAS',
            'UT' => 'UTAH',
            'VT' => 'VERMONT',
            'VI' => 'VIRGIN ISLANDS',
            'VA' => 'VIRGINIA',
            'WA' => 'WASHINGTON',
            'WV' => 'WEST VIRGINIA',
            'WI' => 'WISCONSIN',
            'WY' => 'WYOMING',
            'AE' => 'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
            'AA' => 'ARMED FORCES AMERICA (EXCEPT CANADA)',
            'AP' => 'ARMED FORCES PACIFIC'
        ];
    }

    public static function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function deleteFileFromDirectory($baseDir, $files) {
        $di = new \RecursiveDirectoryIterator($baseDir);
        foreach (new \RecursiveIteratorIterator($di) as $filename => $file) {
            if (in_array($file->getFilename(), $files)) {
                @unlink($file->getPathName());
            }
        }
    }

    public static function getAvatarImage($photo, $width_max, $offset) {
        $basePath = Url::base();
        $baseDir = Yii::getAlias('@webroot');

        if (!empty($photo)) {

            list($width, $height) = getimagesize($baseDir . '/uploads/contact_photo/' . $photo);
            if ($width == $height) {
                ?>
                <div class="box-image" style="width: <?php echo $width_max; ?>; height: <?php echo $width_max; ?>"><img class="circular--square" src="<?php echo $basePath . '/uploads/contact_photo/' . $photo ?>"></div>
                <?php
            } else if ($width > $height) {
                ?>
                <div class="circular--landscape" style="width: <?php echo $width_max; ?>; height: <?php echo $width_max; ?>">
                    <img src="<?php echo $basePath . '/uploads/contact_photo/' . $photo ?>" style="margin-left: -<?php echo $offset; ?>px">
                </div>
                <?php
            } else {
                ?>
                <div class="circular--portrait" style="width: <?php echo $width_max; ?>; height: <?php echo $width_max; ?>">
                    <img src="<?php echo $basePath . '/uploads/contact_photo/' . $photo ?>">
                </div>
                <?php
            }
        }
    }

    public static function httpErrors($http_code) {
        $http_codes_array = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => 'Switch Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a teapot',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'Unordered Collection',
            426 => 'Upgrade Required',
            449 => 'Retry With',
            450 => 'Blocked by Windows Parental Controls',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended'
        );

        return $http_codes_array[$http_code];
    }

    public static function getMonthArray() {
        return [
            1 => "January",
            2 => "February",
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December"
        ];
    }
    public static function getShortMonthArray() {
        return [
            1 => "Jan",
            2 => "Feb",
            3 => "Mar",
            4 => "Apr",
            5 => "May",
            6 => "Jun",
            7 => "Jul",
            8 => "Aug",
            9 => "Sep",
            10 => "Oct",
            11 => "Nov",
            12 => "Dec"
        ];
    }

    public static function getColsReference($headers) {
        $header_ref = array();

        foreach ($headers as $key => $value) {
            $test_header = trim($value);
            if (in_array($test_header, self::$possible_headers)) {
                $header_ref[$test_header] = $key;
            }
        }
        return $header_ref;

    }

}
