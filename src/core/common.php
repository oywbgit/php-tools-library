<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/10 0010
 * Time: 16:22
 */

/**
 * 把字符串转为32位符号整数
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
 * 生成随机字符串
 * @param int $length 生成随机字符串的长度
 * @param string $char 组成随机字符串的字符串
 * @return string $string 生成的随机字符串
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
 * 生成32位唯一字符串
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
        $key .= $pattern{mt_rand(0, 35)}; //生成php随机数
    }
    return $key;
}


/**
 * 获得随机小数
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
 * 将一个字符按比特位进行反转 eg: 65 (01000001) --> 130(10000010)
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
 * 将一个字节流按比特位反转 eg: 'AB'(01000001 01000010)  --> '\x42\x82'(01000010 10000010)
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

/* 列举一些常用的crc16算法
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
 * @param string $str 待校验字符串
 * @param int $polynomial 二项式
 * @param int $initValue 初始值
 * @param int $xOrValue 输出结果前异或的值
 * @param bool $inputReverse 输入字符串是否每个字节按比特位反转
 * @param bool $outputReverse 输出是否整体按比特位反转
 * @return int
 */
function crc16($str, $polynomial, $initValue, $xOrValue, $inputReverse = false, $outputReverse = false) {
    $crc = $initValue;

    for ($i = 0; $i < strlen($str); $i++) {
        if ($inputReverse) {
            // 输入数据每个字节按比特位逆转
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
        // 把低地址存低位，即采用小端法将整数转换为字符串
        $ret = pack('cc', $crc & 0xff, ($crc >> 8) & 0xff);
        // 输出结果按比特位逆转整个字符串
        $ret = reverseString($ret);
        // 再把结果按小端法重新转换成整数
        $arr = unpack('vshort', $ret);
        $crc = $arr['short'];
    }
    return $crc ^ $xOrValue;
}

/**
 * php实现的crc32函数
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
