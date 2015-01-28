<?php

Yii::import('application.components.file-upload.file-contexts.UploadFileContext');

/**
 * Class BaseFileHelper
 * @property-read UploadFileContext[] $contexts
 */
class BaseFileHelper extends CApplicationComponent
{
    /**
     * @var string
     */
    public $dir;

    /**
     * @var integer
     */
    public $maxDirFilesCount = 100;

    /**
     * @var integer
     */
    public $mkdirMod = 0755;

    /**
     * @var UploadFileContext[]
     */
    private $_contexts = array();

    /**
     * Trying return context by name
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $matches = array();
        if (preg_match('/^(.*)Context$/i', $name, $matches) && $this->hasContext($matches[1])) {
            return $this->getContext($matches[1]);
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws CException
     */
    public function __set($name, $value)
    {
        $matches = array();
        if (preg_match('/^(.*)Context$/i', $name, $matches) && $this->hasContext($matches[1])) {
            throw new CException('You cannot change contexts on fly.');
        }
        return parent::__set($name, $value);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        $matches = array();
        if (preg_match('/^(.*)Context$/i', $name, $matches) && $this->hasContext($matches[1])) {
            return true;
        }
        return parent::__isset($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __unset($name)
    {
        $matches = array();
        if (preg_match('/^(.*)Context$/i', $name, $matches) && $this->hasContext($matches[1])) {
            throw new CException('You cannot unset context on fly.');
        }
        return parent::__unset($name);
    }

    public function init()
    {
        if (!extension_loaded('mbstring')) {
            throw new CException('You must load mbstring extension.');
        }

        if (is_null($this->dir)) {
            throw new CException('Directory for files not defined.');
        } elseif (!is_dir($this->dir)) {
            if (!@mkdir($this->dir, $this->mkdirMod, true)) {
                throw new CException("Cannot create directory '$this->dir'.");
            }
            $this->dir = rtrim($this->realpath($this->dir), '\/').'/';
        }
        return parent::init();
    }

    /**
     * @param array $contexts
     * @return BaseFileHelper
     */
    public function setContexts(array $contexts)
    {
        $this->_contexts = array();
        foreach ($contexts as $configName => $config) {
            if (!is_object($config)) {
                $config = Yii::createComponent($config, $this, $configName);
            }
            if (!($config instanceof UploadFileContext)) {
                throw new CException('Context must be an instance of UploadFileContext.');
            }
            $this->_contexts[$configName] = $config;
        }
        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasContext($name)
    {
        return isset($this->_contexts[$name]);
    }

    /**
     * @param string $name
     * @return UploadFileContext
     * @throws CException
     */
    public function getContext($name)
    {
        if (!$this->hasContext($name)) {
            throw new CException("Context doesn't exist");
        }
        return $this->_contexts[$name];
    }

    /**
     * @return UploadFileContext[]
     */
    public function getContexts()
    {
        return $this->_contexts;
    }

    /**
     * @param $file
     * @return string path to uploaded file
     * @throws CException if couldn't create directory
     */
    public function generateDir($file)
    {
        $pDir = rtrim($this->dir, '\/').'/';
        foreach(scandir($pDir) as $dir){
            if($dir !== '.' && $dir !== '..' && is_dir($pDir.$dir)){
                if (($files = glob($pDir.$dir.'/*.*')) !== false && count($files) >= $this->maxDirFilesCount) {
                    continue;
                }
                if(!file_exists($this->normalizeFSName($pDir.$dir.'/'.$file))){
                    return $pDir.$dir.'/';
                }
            }
        }

        $generatedDir = $pDir.Yii::app()->securityManager->generatePassword(12, 6, 6).'/';
        if (!(@mkdir($generatedDir, $this->mkdirMod, true))) {
            throw new CException("Couldn't create directory '$generatedDir'.");
        }
        return $generatedDir;
    }

    /**
     * @param string $path
     * @return string cut path from full path
     * @throws CException
     */
    public function cutPath($path)
    {
        $path = rtrim($this->realpath($path), '\/');
        $pDir = rtrim($this->realpath(Yii::app()->basePath), '\/');

        $founded = false;
        for ($i = 0; $i < min($this->_strlen($path), $this->_strlen($pDir)); ++$i) {
            if ($this->_substr($path, $i, 1) !== $this->_substr($pDir, $i, 1)) {
                $founded = true;
                break;
            }
        }

        if (!$founded) {
            throw new CException('Cannot find own substring of paths.');
        }

        return '/'.str_replace('\\', '/', ltrim($this->_substr($path, $i), '/'));
    }


/**
 * Get string length
 * @link http://php.net/manual/en/function.mb-strlen.php
 * @param string $str The string being checked for length.
 * @return string the number of characters in string <i>str</i>.
 */
    private function _strlen($str)
    {
        return mb_strlen($str, Yii::app()->charset);
    }

    /**
     * Get part of string
     * @link http://php.net/manual/en/function.mb-substr.php
     * @param string $str The string to extract the substring from.
     * @param int $start Position of first character to use from <i>str</i>.
     * @param int $length [optional] Maximum number of characters to use from <i>str</i>. If
     * omitted or NULL is passed, extract all characters to the end of the string.
     * @return string <b>mb_substr</b> returns the portion of <i>str</i> specified by the
     * <i>start</i> and <i>length</i> parameters.
     */
    private function _substr($str, $start, $length = null)
    {
        $length = $length === null ? $this->_strlen($str) - $start : $length;
        return mb_substr($str, $start, $length, Yii::app()->charset);
    }

    /**
     * This method works like php's realpath, but it does not check file existing.
     * @param string $path
     * @return string
     */
    public function realpath($path)
    {
        $parts = explode('/', str_replace('\\', '/', $path));
        $results = array();
        $i = 0;

        foreach ($parts as $part) {
            if (($part === '' && $i !== 0) || $part === '.') {
                continue;
            } elseif ($part === '..' && count($results)) {
                array_pop($results);
            } else {
                array_push($results, $part);
            }
            $i++;
        }

        return implode('/', $results);
    }

    /**
     * Win not allowed utf8 file names.
     * This method applies urlencoding file path for win platforms.
     * @param string $fileName
     * @return string
     */
    public function normalizeFSName($fileName)
    {
        if (strcasecmp(substr(PHP_OS, 0, 3), 'WIN') !== 0) {
            return $fileName;
        }

        $fileName = str_replace('\\', '/', $fileName);
        if (false !== $pos = mb_strpos($fileName, ':/')) {
            $filePrefix = mb_substr($fileName, 0, $pos + 2);
            $fileName = mb_substr($fileName, $pos + 2);
        }
        return (isset($filePrefix) ? $filePrefix : '') .
            implode('/', array_map('urlencode', explode('/', $fileName)));
    }
}
