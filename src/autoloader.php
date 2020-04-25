<?php


namespace phplibrary;
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
        if (\strpos($name, 'phplibrary\\') === 0) {
            $class_file = __DIR__ . \substr($class_path, \strlen('phplibrary')) . '.php';
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
//        echo $name.' => '.$class_file.PHP_EOL;

        if (\is_file($class_file)) {
            require_once($class_file);
            if (\class_exists($name, false)) {
                return true;
            }
        }
        return false;
    }

    public static function initCommon(){
        $path = dirname(__FILE__). DIRECTORY_SEPARATOR.'core';
        $pf = Autoloader::my_scandir($path);
        foreach ($pf as $f){
            if(is_file($f)){
                include_once $f;
            }
        }
    }

    /**
     * ����ȡ����Ŀ¼���������ʽչ�ֳ���
     * @return array
     * opendir() ������һ��Ŀ¼��������� closedir()��readdir() �� rewinddir() ʹ�á�
     * is_dir() �������ָ�����ļ��Ƿ���Ŀ¼��
     * readdir() ���������� opendir() �򿪵�Ŀ¼����е���Ŀ��
     * @param array $files ���е��ļ���Ŀ�Ĵ������
     * @param string $file ���ص��ļ���Ŀ
     * @param string $dir �ļ���·��
     * @param resource $handle �򿪵��ļ�Ŀ¼���
     */
    public static function my_scandir($dir)
    {
        //����һ������
        $files = array();
        //����Ƿ�����ļ�
        if (is_dir($dir)) {
            //��Ŀ¼
            if ($handle = opendir($dir)) {
                //���ص�ǰ�ļ�����Ŀ
                while (($file = readdir($handle)) !== false) {
                    //ȥ������Ŀ¼
                    if ($file != "." && $file != "..") {
                        //�ж���Ŀ¼�Ƿ񻹴�����Ŀ¼
                        if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                            //�ݹ���ñ��������ٴλ�ȡĿ¼
                            $files = array_merge($files,Autoloader::my_scandir($dir .DIRECTORY_SEPARATOR . $file));
                        } else {
                            //��ȡĿ¼����
                            $files[] = $dir . DIRECTORY_SEPARATOR . $file;
                        }
                    }
                }
                //�ر��ļ���
                closedir($handle);
                //�����ļ�������
                return $files;
            }
        }
    }
}



spl_autoload_register( '\phplibrary\Autoloader::loadByNamespace' );

Autoloader::initCommon();