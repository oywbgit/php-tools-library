<?php

namespace Toollibrary;

/**
 * Autoload.
 */
class Autoloader
{
    /**
     * Autoload root path.
     *
     * @var string
     */
    protected static $_autoloadRootPath = '';

    /**
     * Set autoload root path.
     *
     * @param string $root_path
     * @return void
     */
    public static function setRootPath($root_path)
    {
        self::$_autoloadRootPath = $root_path;
    }

    /**
     * Load files by namespace.
     *
     * @param string $name
     * @return boolean
     */
    public static function loadByNamespace($name)
    {
        $class_path = \str_replace('\\', \DIRECTORY_SEPARATOR, $name);
        if (\strpos($name, 'TCPServer\\') === 0) {
            $class_file = __DIR__ . \substr($class_path, \strlen('TCPServer')) . '.php';
        } elseif (\strpos($name, 'app\\') === 0) {
            $tmp_file = str_replace('\\','/',realpath(dirname(dirname(__FILE__)).'/')).'/application'. \substr($class_path, \strlen('app')) . '.php';
            $class_file = str_replace('\\','/',$tmp_file);
        }  else {
            if (self::$_autoloadRootPath) {
                $class_file = self::$_autoloadRootPath . \DIRECTORY_SEPARATOR . $class_path . '.php';
                echo $class_file.PHP_EOL;
            }
            if(file_exists(__DIR__ . \DIRECTORY_SEPARATOR . "$class_path.php")){
                $class_file = __DIR__ . \DIRECTORY_SEPARATOR . "$class_path.php";
            }elseif (empty($class_file) || !\is_file($class_file)) {
                $class_file = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . "$class_path.php";
            }
        }

        if (\is_file($class_file)) {
            require_once($class_file);
            if (\class_exists($name, false)) {
                return true;
            }
        }
        return false;
    }

    public static function initCommon(){
        include_once "core/common.php";
    }

    /**
     * 将读取到的目录以数组的形式展现出来
     * @return array
     * opendir() 函数打开一个目录句柄，可由 closedir()，readdir() 和 rewinddir() 使用。
     * is_dir() 函数检查指定的文件是否是目录。
     * readdir() 函数返回由 opendir() 打开的目录句柄中的条目。
     * @param array $files 所有的文件条目的存放数组
     * @param string $file 返回的文件条目
     * @param string $dir 文件的路径
     * @param resource $handle 打开的文件目录句柄
     */
    public static function my_scandir($dir)
    {
        //定义一个数组
        $files = array();
        //检测是否存在文件
        if (is_dir($dir)) {
            //打开目录
            if ($handle = opendir($dir)) {
                //返回当前文件的条目
                while (($file = readdir($handle)) !== false) {
                    //去除特殊目录
                    if ($file != "." && $file != "..") {
                        //判断子目录是否还存在子目录
                        if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                            //递归调用本函数，再次获取目录
                            $files = array_merge($files,Autoloader::my_scandir($dir .DIRECTORY_SEPARATOR . $file));
                        } else {
                            //获取目录数组
                            $files[] = $dir . DIRECTORY_SEPARATOR . $file;
                        }
                    }
                }
                //关闭文件夹
                closedir($handle);
                //返回文件夹数组
                return $files;
            }
        }
    }
}

Autoloader::initCommon();

//config/config.php
\spl_autoload_register('\toollibrary\Autoloader::loadByNamespace');

