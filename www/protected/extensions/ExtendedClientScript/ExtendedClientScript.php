<?php
/**
 * Compress and cache used JS and CSS files.
 * Needs jsmin and cssmin
 *
 * Ties into the 1.0.4 and up Yii CClientScript functions
 *
 * 0.9.0 Now using CssMin code.google.com/p/cssmin/ for PHP 5.3.x compatibility
 *
 * Now checking and excluding remote files automatically
 *
 * @author Maxximus <maxximus007@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @author Kir <>
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011
 * @license htp://www.yiiframework.com/license/
 * @version 0.9.0
 *
 */

class ExtendedClientScript extends CClientScript
{
	/**
	* Compress all Javascript files with JSMin. JSMin must be installed as an extension in $jssminPath.
	* github.com/rgrove/jsmin-php/
	*/
	public $compressJs = false;
	/**
	* Compress all CSS files with CssMin. CssMin must be installed as an extension in $cssMinPath.
	* Specific browserhacks will be removed, so don't add them in to be compressed CSS files.
	* code.google.com/p/cssmin/
	*/
	public $compressCss = false;
	/**
	* Replace relative paths in css
	*/
	public $replacePaths = false;
	/**
	* DEPRECATED/LEGACY
	* Combine all JS and CSS files into one. Be careful with relative paths in CSS.
	*/
	public $combineFiles = false;
	/**
	* Combine all non-remote JS files into one.
	*/
	public $combineJs = false;
	/**
	* Combine all non-remote CSS files into one. Be careful with relative paths in CSS.
	*/
	public $combineCss = false;
	/**
	* Exclude certain files from inclusion. array('/path/to/excluded/file') Useful for fixed base
	* and incidental additional JS.
	*/
	public $excludeFiles = array();
	/**
	* Path where the combined/compressed file will be stored. Will use coreScriptUrl if not defined
	*/
	public $filePath;
	/**
	* If true, all files to be included will be checked if they are modified.
	* To enhance speed (eg production) set to false.
	*/
	public $autoRefresh = true;
	/**
	* Relative Url where the combined/compressed file can be found
	*/
	public $fileUrl;
	/**
	* Path where files can be found
	*/
	public $basePath;
	/**
	* Used for garbage collection. If not accessed during that period: remove.
	*/
	public $ttlDays = 1;
	/**
	* prefix for the combined/compressed files
	*/
	public $prefix = 'c_';
	/**
	* path to JsMin
	*/
	public $jsMinPath = 'ext.ExtendedClientScript.jsmin.*';
	/**
	* path to CssMin
	*/
	public $cssMinPath = 'ext.ExtendedClientScript.cssmin.*';

    public $isAddFileHashes = true;

	/**
	* CssMin filter options. Default values according cssMin doc.
	*/
	public $cssMinFilters = array
	(
        'ImportImports'                 => false,
        'RemoveComments'                => true,
        'RemoveEmptyRulesets'           => true,
        'RemoveEmptyAtBlocks'           => true,
        'ConvertLevel3AtKeyframes'      => false,
        'ConvertLevel3Properties'       => false,
        'Variables'                     => true,
        'RemoveLastDelarationSemiColon' => true
	);
	/**
	* CssMin plugin options. Maximum compression and conversion.
	*/
	public $cssMinPlugins = array
	(
		'Variables'                => true,
		'ConvertFontWeight'        => true,
		'ConvertHslColors'         => true,
		'ConvertRgbColors'         => true,
		'ConvertNamedColors'       => true,
		'CompressColorValues'      => true,
		'CompressUnitValues'       => false,
		'CompressExpressionValues' => true,
	);

    public $ignoreFilesTemplates = array(
        '/\.min\.js$/',
        '/\.min\.css$/',
    );

	private $_changesHash = '';
    private $_renewFile;

    private $_oldScriptFiles = null;
    private $_oldCssFiles = null;

    public function addAndRegisterPackage($name, $definition)
    {
        $this->addPackage($name, $definition);
        $this->registerPackage($name);
        return $this;
    }

    protected function calcFileMD5($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }
        return md5_file($filename);
    }

    protected function addFileHashes(array $files)
    {
        $this->basePath or $this->basePath = realpath($_SERVER['DOCUMENT_ROOT']);
        $result = array();
        foreach ($files as $url => $value) {
            if (file_exists($this->basePath.'/'.$url)) {
                $url .= '?'.md5_file($this->basePath.'/'.$url);
            }
            $result[$url] = $value;
        }
        return $result;
    }

    protected function addScriptFilesHashes($pos)
    {
        if (!isset($this->scriptFiles[$pos])) {
            return;
        }
        $this->_oldScriptFiles = $this->scriptFiles[$pos];
        $this->scriptFiles[$pos] = $this->addFileHashes($this->scriptFiles[$pos]);
    }

    protected function rollbackScriptFiles($pos)
    {
        if (is_array($this->_oldScriptFiles)) {
            $this->scriptFiles[$pos] = $this->_oldScriptFiles;
        }
        $this->_oldScriptFiles = null;
    }

    protected function addCssFilesHashes()
    {
        $this->_oldCssFiles = $this->cssFiles;
        $this->cssFiles = $this->addFileHashes($this->cssFiles);
    }

    protected function rollbackCssFiles()
    {
        if (is_array($this->_oldCssFiles)) {
            $this->cssFiles = $this->_oldCssFiles;
        }
        $this->_oldCssFiles = null;
    }

    /**
	* Will combine/compress JS and CSS if wanted/needed, and will continue with original
	* renderHead afterwards
	*
	* @param <type> $output
	*/
	public function renderHead(&$output)
	{
		if ($this->combineFiles)
			$this->combineJs = $this->combineCss = true;

		$this->renderJs($output, parent::POS_HEAD);

		if ($this->combineCss)
		{
			if (count($this->cssFiles) !== 0)
			{
				$cssFiles = array();

				foreach ($this->cssFiles as $url => $media)
					if(! $this->isRemoteFile($url))
						$cssFiles[$media][$url] = $url; // Exclude remote files

				foreach ($cssFiles as $media => $url)
					$this->combineAndCompress('css', $url, $media);
			}
		}

        if ($this->isAddFileHashes) {
            $this->addScriptFilesHashes(parent::POS_HEAD);
            $this->addCssFilesHashes();
        }
		parent::renderHead($output);
        $this->rollbackScriptFiles(parent::POS_HEAD);
        $this->rollbackCssFiles();
	}

	/**
	* Will combine/compress JS if wanted/needed, and will continue with original
	* renderBodyEnd afterwards
	*
	* @param <type> $output
	*/
	public function renderBodyBegin(&$output)
	{
		$this->renderJs($output, parent::POS_BEGIN);

        if ($this->isAddFileHashes) {
            $this->addScriptFilesHashes(parent::POS_BEGIN);
        }
        parent::renderBodyBegin($output);
        $this->rollbackScriptFiles(parent::POS_BEGIN);
	}

	/**
	* Will combine/compress JS if wanted/needed, and will continue with original
	* renderBodyEnd afterwards
	*
	* @param <type> $output
	*/
	public function renderBodyEnd(&$output)
	{
		$this->renderJs($output, parent::POS_END);

        if ($this->isAddFileHashes) {
            $this->addScriptFilesHashes(parent::POS_END);
        }
        parent::renderBodyEnd($output);
        $this->rollbackScriptFiles(parent::POS_END);
	}

    public function isExcludeFile($filename)
    {
        if (empty($this->excludeFiles)) {
            return false;
        }

        foreach ($this->excludeFiles as $pattern) {
            if (preg_match($pattern, $filename)) {
                return true;
            }
        }

        return false;
    }

	/**
	 *
	 *
	 * @param <type> $output
	 * @param <type> $pos
	 */
	private function renderJs($output, $pos)
	{
		if ($this->combineJs)
		{
			if (isset($this->scriptFiles[$pos]) && count($this->scriptFiles[$pos]) !==  0)
			{
				$jsFiles = $this->scriptFiles[$pos];

				foreach ($jsFiles as &$fileName) {
                    if ($this->isExcludeFile($fileName) || $this->isRemoteFile($fileName)) {
                        $fileName = false;
                    }
                }

				$jsFiles = array_filter($jsFiles);
				$this->combineAndCompress('js', $jsFiles, $pos);
			}
		}
	}

	static public function replaceCssUrls($url, $oldFile, $newFile)
	{
		if (strpos($url, 'http') === 0 || strpos($url, '/') === 0) {
			return $url;
		}

		$oldFolders = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', dirname($oldFile)));
		$newFolders = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', dirname($newFile)));
		for ($i = 0; $i < count($oldFolders) && $i < count($newFolders); ++$i) {
			if ($oldFolders[$i] !== $newFolders[$i]) {
				break;
			}
		}

		return implode('/', array_fill(0, count($newFolders) - $i, '..')) .
			implode('/', array_slice($oldFolders, $i)) . '/' . $url;
	}

    public function isIgnoreFile($file)
    {
        if (is_array($this->ignoreFilesTemplates)) {
            foreach ($this->ignoreFilesTemplates as $template) {
                if (preg_match($template, $file)) {
                    return true;
                }
            }
        }
        return false;
    }

	/**
	* Performs the actual combining and compressing
	*
	* @param <type> $type
	* @param <type> $urls
	* @param <type> $pos
	*/
	private function combineAndCompress($type, $urls, $pos)
	{
		$this->fileUrl or $this->fileUrl = $this->getCoreScriptUrl();
		$this->basePath or $this->basePath = realpath($_SERVER['DOCUMENT_ROOT']);
		$this->filePath or $this->filePath = $this->basePath.$this->fileUrl;

		$optionsHash = ($type == 'js') ? md5($this->basePath . $this->compressJs . $this->ttlDays . $this->prefix)
												 : md5($this->basePath . $this->compressCss . $this->ttlDays . $this->prefix . serialize($this->cssMinFilters) . serialize($this->cssMinPlugins));

		if ($this->autoRefresh)
		{
			$mtimes = array();

			foreach ($urls as $file)
			{
				$fileName = $this->basePath.'/'.trim($file,'/');

				if(file_exists($fileName))
				{
					$mtimes[] = filemtime($fileName);
				}
			}
			$this->_changesHash = md5(serialize($mtimes));
		}

		$combineHash = md5(implode('',$urls));

		$fileName = $this->prefix.md5($combineHash.$optionsHash.$this->_changesHash).".$type";

		$this->_renewFile = (file_exists($this->filePath.'/'.$fileName)) ? false : true;

		if ($this->_renewFile)
		{
			$this->garbageCollect($type);
			$combinedFile = '';

			foreach ($urls as $key => $file) {
				$content = file_get_contents(rtrim($this->basePath, '\/').'/'.ltrim($file, '\/'));
				if ($type == 'css' && $this->replacePaths) {
					$oldFileName = $this->basePath.'/'.$file;
					$newFileName = $this->filePath.'/'.$fileName;
					$content = preg_replace_callback('/(url\s*\(\s*[\'"]?)([^\'"\)]+)([\'"]?\s*\))/', function ($matches) use ($oldFileName, $newFileName) {
						return $matches[1] . ExtendedClientScript::replaceCssUrls($matches[2], $oldFileName, $newFileName) . $matches[3];
					}, $content);
				}

                if ($type == 'js' && $this->compressJs && !$this->isIgnoreFile($this->basePath.'/'.$file)) {
                    $content = $this->minifyJs($content);
                }

                if ($type == 'css' && $this->compressCss && !$this->isIgnoreFile($this->basePath.'/'.$file)) {
                    $content = $this->minifyCss($content);
                }

				$combinedFile .= $content . ($type == 'js' ? ';'.PHP_EOL : '');
			}

			file_put_contents($this->filePath.'/'.$fileName, $combinedFile);
		}

		foreach ($urls as $url)
			$this->scriptMap[basename($url)] = $this->fileUrl.'/'.$fileName.
                ($this->isAddFileHashes ? '?'.$this->calcFileMD5($this->filePath.'/'.$fileName) : '');

		$this->remapScripts();
	}

	private function garbageCollect($type)
	{
		$files = CFileHelper::findFiles($this->filePath, array('fileTypes' => array($type), 'level'=> 0));

		foreach($files as $file)
		{
			if (strpos($file, $this->prefix) !== false && $this->fileTTL($file))
				@unlink($file);
		}
	}

	/**
	* See if file is ready for deletion
	*
	* @param <type> $file
	*/
	private function fileTTL($file)
	{
		if(!file_exists($file)) return false;
		$ttl = $this->ttlDays * 60 * 60 * 24;
		return ((fileatime($file) + $ttl) < time()) ? true : false;
	}

	protected function importLibs()
	{
		Yii::import($this->jsMinPath, true);
		Yii::import($this->cssMinPath, true);
		return $this;
	}

	/**
	* Minify javascript with JSMin
	*
	* @param <type> $js
	*/
	private function minifyJs($js)
	{
		$this->importLibs();
		return JSMin::minify($js);
	}

	/**
	* Minify css with cssmin
	*
	* @param <type> $css
	*/
	private function minifyCss($css)
	{
		$this->importLibs();
		return cssmin::minify($css, $this->cssMinFilters, $this->cssMinPlugins);
	}

	/**
	* See if file is on remote server
	*
	* @param <type> $file
	*/
	private function isRemoteFile($file) {
		return (strpos($file, 'http://') === 0 || strpos($file, 'https://') === 0 || strpos($file, '//') === 0) ? true : false;
	}
}
