<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/10 0010
 * Time: 16:22
 */

/**
 * ���ַ���תΪ32λ��������
 */
function crc32hash($str)
{
    return sprintf('%u', crc32($str));
}

function getFileMimeType($filename){
    $finfo = finfo_open(FILEINFO_MIME);
    $mimetype = finfo_file($finfo, $filename);
    finfo_close($finfo);
    return $mimetype;
}

function get_extension($file){
    return pathinfo($file, PATHINFO_EXTENSION);
}
/*
 * ��������ַ���
 * @param int $length ��������ַ����ĳ���
 * @param string $char �������ַ������ַ���
 * @return string $string ���ɵ�����ַ���
 */
function str_rand($length = 32, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    if (!is_int($length) || $length < 0) {
        return false;
    }

    $string = '';
    for ($i = $length; $i > 0; $i--) {
        $string .= $char[mt_rand(0, strlen($char) - 1)];
    }

    return $string;
}

/*
 * ����32λΨһ�ַ���
 */
function getUniqid()
{
    return md5(uniqid(microtime(true), true));
}


function getFileSize($size){
    $dw= "Byte" ;
    if ($size >= pow(2, 40)){
        $size=round($size/pow(2, 40), 2);
        $dw= "TB" ;
    } else if ($size >= pow(2, 30)){
        $size=round($size/pow(2, 30), 2);
        $dw= "GB" ;
    } else if ($size >= pow(2, 20)){
        $size=round($size/pow(2, 20), 2);
        $dw= "MB" ;
    } else if ($size >= pow(2, 10)){
        $size=round($size/pow(2, 10), 2);
        $dw= "KB" ;
    } else {
        $dw= "Bytes" ;
    }
    return $size.$dw;
}

function outLog($msg,$level=0,$logPath='')
{

    if(defined('OUT_FILE')){
        $h =  OUT_FILE;
    }else{
        $backtrace        = \debug_backtrace();
        $_startFile = $backtrace[\count($backtrace) - 1]['file'];
        $h = str_replace('.php','',basename($_startFile));
        !defined('OUT_FILE') && define('OUT_FILE',$h);
    }

    if (is_array($msg)) {
        $msg = json_encode($msg);
    } elseif (is_object($msg)) {
        $msg = print_r($msg);
    }

    if(empty($logPath)){
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $logPath = "D:/tmp/logs/";
        } else {
            $logPath = '/data/logs/';
        }
    }

    $showtime = date("Ymd");
    $logName = $h . "_".$showtime.".log";
    $logFile = $logPath . $logName;
    $outMsg = $h . ' | ' . time() . ' | ' . $msg . PHP_EOL;


    !defined('LOG_NO_ECHO') && define('LOG_NO_ECHO',true);
    if(LOG_NO_ECHO){
        echo $outMsg;
    }

    file_put_contents($logFile, $outMsg, FILE_APPEND | LOCK_EX);
}

function randomkeys($length)
{
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    for ($i = 0; $i < $length; $i++) {
        $key .= $pattern{mt_rand(0, 35)}; //����php�����
    }
    return $key;
}


/**
 * ������С��
 * @param int $min
 * @param int $max
 * @return float|int
 * USER: Administrator
 * TIME: 2020/3/6 0006 14:51
 */
function randFloat($min = 0, $max = 1)
{
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}




/**
 * ��һ���ַ�������λ���з�ת eg: 65 (01000001) --> 130(10000010)
 * @param $char
 * @return $char
 */
function reverseChar($char) {
    $byte = ord($char);
    $tmp = 0;
    for ($i = 0; $i < 8; ++$i) {
        if ($byte & (1 << $i)) {
            $tmp |= (1 << (7 - $i));
        }
    }
    return chr($tmp);
}

/**
 * ��һ���ֽ���������λ��ת eg: 'AB'(01000001 01000010)  --> '\x42\x82'(01000010 10000010)
 * @param $str
 */
function reverseString($str) {
    $m = 0;
    $n = strlen($str) - 1;
    while ($m <= $n) {
        if ($m == $n) {
            $str{$m} = reverseChar($str{$m});
            break;
        }
        $ord1 = reverseChar($str{$m});
        $ord2 = reverseChar($str{$n});
        $str{$m} = $ord2;
        $str{$n} = $ord1;
        $m++;
        $n--;
    }
    return $str;
}

/* �о�һЩ���õ�crc16�㷨
 // CRC-16/IBM
printf("%x\n", crc16('1234567890', 0x8005, 0, 0, true, true));

// CRC-16/MAXIM
printf("%x\n", crc16('1234567890', 0x8005, 0, 0xffff, true, true));

// CRC-16/USB
printf("%x\n", crc16('1234567890', 0x8005, 0xffff, 0xffff, true, true));

// CRC-16/MODBUS
printf("%x\n", crc16('1234567890', 0x8005, 0xffff, 0, true, true));

// CRC-16/CCITT
printf("%x\n", crc16('1234567890', 0x1021, 0, 0, true, true));

// CRC-16/CCITT-FALSE
printf("%x\n", crc16('1234567890', 0x1021, 0xffff, 0, false, false));

// CRC-16/X25
printf("%x\n", crc16('1234567890', 0x1021, 0xffff, 0xffff, true, true));

// CRC-16/XMODEM
printf("%x\n", crc16('1234567890', 0x1021, 0, 0, false, false));

// CRC-16/DNP
printf("%x\n", crc16('1234567890', 0x3d65, 0, 0xffff, true, true));
*/
/**
 * @param string $str ��У���ַ���
 * @param int $polynomial ����ʽ
 * @param int $initValue ��ʼֵ
 * @param int $xOrValue ������ǰ����ֵ
 * @param bool $inputReverse �����ַ����Ƿ�ÿ���ֽڰ�����λ��ת
 * @param bool $outputReverse ����Ƿ����尴����λ��ת
 * @return int
 */
function crc16($str, $polynomial, $initValue, $xOrValue, $inputReverse = false, $outputReverse = false) {
    $crc = $initValue;

    for ($i = 0; $i < strlen($str); $i++) {
        if ($inputReverse) {
            // ��������ÿ���ֽڰ�����λ��ת
            $c = ord(reverseChar($str{$i}));
        } else {
            $c = ord($str{$i});
        }
        $crc ^= ($c << 8);
        for ($j = 0; $j < 8; ++$j) {
            if ($crc & 0x8000) {
                $crc = (($crc << 1) & 0xffff) ^ $polynomial;
            } else {
                $crc = ($crc << 1) & 0xffff;
            }
        }
    }
    if ($outputReverse) {
        // �ѵ͵�ַ���λ��������С�˷�������ת��Ϊ�ַ���
        $ret = pack('cc', $crc & 0xff, ($crc >> 8) & 0xff);
        // ������������λ��ת�����ַ���
        $ret = reverseString($ret);
        // �ٰѽ����С�˷�����ת��������
        $arr = unpack('vshort', $ret);
        $crc = $arr['short'];
    }
    return $crc ^ $xOrValue;
}

/**
 * phpʵ�ֵ�crc32����
 * @param $str
 * @return int|mixed|string
 * USER: Administrator
 * TIME: 2020/3/19 0019 18:10
 */
function php_crc32($str) {
    $polynomial = 0x04c11db7;
    $crc = 0xffffffff;
    for ($i = 0; $i < strlen($str); $i++) {
        $c = ord(reverseChar($str{$i}));
        $crc ^= ($c << 24);
        for ($j = 0; $j < 8; $j++) {
            if ($crc & 0x80000000) {
                $crc = (($crc << 1) & 0xffffffff) ^ $polynomial;
            } else {
                $crc = ($crc << 1) & 0xffffffff;
            }
        }
    }
    $ret = pack('cccc', $crc & 0xff, ($crc >> 8) & 0xff, ($crc >> 16) & 0xff, ($crc >> 24) & 0xff);
    $ret = reverseString($ret);
    $arr = unpack('Vret', $ret);
    $ret = $arr['ret'] ^ 0xffffffff;
    return $ret;
}
