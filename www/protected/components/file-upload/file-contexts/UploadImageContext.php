<?php

Yii::import('application.components.file-upload.file-contexts.UploadFileContext', true);

/**
 * Class UploadImageContext
 */
class UploadImageContext extends UploadFileContext
{
    /**
     * @var string alias to validator class.
     */
    public $validatorClass = 'application.components.validators.ImageValidator';

    /**
     * @var array params for resizing.
     */
    public $resizeParams;

    /**
     * @var integer|null
     */
    public $thumbnailWidth = 100;
    /**
     * @var integer|null
     */
    public $thumbnailHeight = null;

    public $fileInputAccept = 'image/*';

    /**
     * @param string $filePath
     * @param File $file
     */
    public function afterSave($filePath, File $file)
    {
        parent::afterSave($filePath, $file);

        if (is_null($this->resizeParams)) {
            return;
        }

        $this->generateResizeImage($filePath, $this->resizeParams);
    }

    public function afterDelete($filePath, $fileId = null)
    {
        parent::afterDelete($filePath, $fileId);

        if (is_null($fileId)) {
            $this->unlinkAllSqueezeFiles($filePath);
        }
    }

    /**
     * @param File|string $fileOrCutPath File model or cut path to file
     * @param mixed $widthOrParams You can pass either array size params
     * (like in resizeParams format) here or string key of resizeParams 
     * sized of current context, or concrete width and|or height.
     * @param integer|null $height
     * @param bool $absolute
     * @throws CException
     * @return string
     */
    public function getWebPath($fileOrCutPath, $widthOrParams = null, $height = null, $absolute = false)
    {
        $grayscale = false;

        if (is_object($fileOrCutPath) && $fileOrCutPath instanceof File) {
            $fileOrCutPath = $fileOrCutPath->path;
        }
        $fileOrCutPath = '/'.ltrim(str_replace('\\', '/', $fileOrCutPath), '/');

        if (is_string($widthOrParams) && isset($this->resizeParams[$widthOrParams])) {
            $widthOrParams = $this->resizeParams[$widthOrParams];
        }
        if (is_array($widthOrParams)) {
            if (!is_null($height)) {
                throw new CException('You must pass either array with resize params or string key from resize params, or concrete width and/or height. Resize params founded & concrete height.');
            }
            $height = isset($widthOrParams['height']) ? $widthOrParams['height'] : null;
            $grayscale = isset($widthOrParams['grayscale']) ? $widthOrParams['grayscale'] : $grayscale;
            $widthOrParams = isset($widthOrParams['width']) ? $widthOrParams['width'] : null;
        }

        if (!is_null($widthOrParams) || !is_null($height) || $grayscale !== false) {
            $fileOrCutPath = $this->getResizeImage($fileOrCutPath, $widthOrParams, $height, $grayscale);
        }

        return ($absolute ? Yii::app()->request->hostInfo : '') .
            Yii::app()->baseUrl.$fileOrCutPath;
    }

    /**
     * @param $cutPath
     * @param null $width
     * @param null $height
     * @param bool $grayscale
     * @throws CException if could not create directory
     * @return string path to resized image
     */
    public function getResizeImage($cutPath, $width = null, $height = null, $grayscale = false)
    {
        if ($width === null && $height === null && $grayscale === false) {
            return $cutPath;
        }

        $file = Yii::app()->basePath.'/..'.$cutPath;
        $fileDir = rtrim(pathinfo($file, PATHINFO_DIRNAME), '\/').'/';
        $fileName = pathinfo($file, PATHINFO_BASENAME);
        $dir = $grayscale === false ? $width.'x'.$height : $width.'x'.$height.'_bw';

        if (file_exists($this->owner->normalizeFSName($fileDir.$dir.'/'.$fileName))){
            return rtrim(dirname($cutPath), '\/').'/'.$dir.'/'.$fileName;
        }

        return $this->owner->cutPath($this->generateResizeImage($file, array(
            array(
                'width'     =>  $width ?: null,
                'height'    =>  $height ?: null,
                'grayscale' =>  $grayscale
            ),
        )));
    }

    /**
     * @param $filePath
     * @param $squeezeSizes array
     * @throws CException if could not create a directory
     * @return string path to resized image
     */
    protected function generateResizeImage($filePath, $squeezeSizes)
    {
        $resizedImage = '';
        $resizedDir = '';
        $fileDir = rtrim(dirname($filePath), '\/').'/';
        $fileName = basename($filePath);

        Yii::import('ext.EWideImage.EWideImage');
        $image = EWideImage::load($this->owner->normalizeFSName($filePath));

        foreach ($squeezeSizes as $resizeParams) {
            if (isset($resizeParams['width']) && !isset($resizeParams['height'])) {
                $resizedImage = $image->resize($resizeParams['width'], null);
                $resizedDir = $resizeParams['width'].'x';
            } elseif (!isset($resizeParams['width']) && isset($resizeParams['height'])) {
                $resizedImage = $image->resize(null, $resizeParams['height']);
                $resizedDir = 'x'.$resizeParams['height'];
            } elseif (isset($resizeParams['width']) && isset($resizeParams['height'])) {
                $rW = $resizeParams['width'];
                $rH = $resizeParams['height'];
                $w = $image->getWidth();
                $h = $image->getHeight();

                $i = ($h/$w*$rW > $rH) ? $image->resize($rW) : $image->resize(null, $rH);

                $resizedImage = $i->crop('center', 'middle', $rW, $rH);
                $resizedDir = $rW.'x'.$rH;
            } elseif (isset($resizeParams['grayscale']) && $resizeParams['grayscale']) {
                $resizedImage = $image;
                $resizedDir = 'x';
            } else {
                continue;
            }

            if (isset($resizeParams['grayscale']) && $resizeParams['grayscale']) {
                $resizedImage = $resizedImage->asGrayscale();
                $resizedDir = $resizedDir.'_bw';
            }

            if (!is_dir($dir = $fileDir.$resizedDir)){
                if (!(@mkdir($dir, $this->owner->mkdirMod, true))) {
                    throw new CException("Couldn't create directory '$dir'.");
                }
            }
            $resizedImage->saveToFile($this->owner->normalizeFSName($dir.'/'.$fileName));

        }
        return $dir.'/'.$fileName;
    }

    /**
     * unlink file, all it's squeeze versions and dirs if empty
     * @param $file full path to file.
     */
    protected function unlinkAllSqueezeFiles($file)
    {
        $fileDir = rtrim(pathinfo($file, PATHINFO_DIRNAME), '\/').'/';
        $fileName = pathinfo($file, PATHINFO_BASENAME);
        $dirs = @scandir($fileDir) ?: array();

        foreach ($dirs as $dir){
            if ($dir !== '.' && $dir !== '..' && is_dir($fileDir.$dir)){
                if (file_exists($this->owner->normalizeFSName($fileDir.$dir.'/'.$fileName))){
                    @unlink($this->owner->normalizeFSName($fileDir.$dir.'/'.$fileName));
                }
                if (count((array)glob("$fileDir.$dir/*")) === 0) {
                    @rmdir($fileDir.$dir);
                }
            }
        }

        if (file_exists($this->owner->normalizeFSName($file))) {
            @unlink($this->owner->normalizeFSName($file));
        }

        if (is_dir($fileDir) && (count((array)glob("$fileDir/*")) === 0)) {
            @rmdir($fileDir);
        }
    }

    /**
     * @param string $filename
     * @param string $path
     * @param string $mimeType
     * @param int $size
     * @return array
     */
    public function getFileParamsForWidget($filename, $path, $mimeType, $size)
    {
        return array_merge(parent::getFileParamsForWidget($filename, $path, $mimeType, $size), array(
            'thumbnail_url' =>  Yii::app()->baseUrl.$this->getResizeImage($path, $this->thumbnailWidth, $this->thumbnailHeight),
        ));
    }

    /**
     * @param FileUploader $widget
     */
    public function beforeRenderWidget(FileUploader $widget)
    {
        if ($this->fileInputAccept && !array_key_exists('accept', (array) $widget->fileInputHtmlOptions)) {
            $widget->fileInputHtmlOptions['accept'] = $this->fileInputAccept;
        }
    }
}
