<?php

Yii::import('application.widgets.FileUploader');

/**
 * Class UploadFileContext
 * @property-read array $validator config for validator rule
 * @property-read FileHelper $owner
 * @property-read string $name
 */
class UploadFileContext extends CComponent
{
    /**
     * @var string alias to validator class.
     */
    public $validatorClass = 'application.components.validators.FileValidator';

    /**
     * @var array
     */
    public $validatorParams = array(
        'allowEmpty'        =>  false,
    );

    /**
     * @var FileHelper|null
     */
    private $_owner;

    /**
     * @var string|null
     */
    private $_name;

    /**
     * @param FileHelper $owner
     * @param string $name
     */
    public function __construct(FileHelper $owner, $name)
    {
        $this->_owner = $owner;
        $this->_name = $name;
    }

    /**
     * @return array config for validator rule
     */
    public function getValidator()
    {
        return array_merge(array($this->validatorClass), $this->validatorParams);
    }

    /**
     * @param string $filePath
     * @param File $file
     * @return bool false if need to stop save process.
     */
    public function beforeSave($filePath, File $file)
    {
        return true;
    }

    /**
     * @param string $filePath
     * @param File $file
     */
    public function afterSave($filePath, File $file)
    {
        // nothing to do
    }

    /**
     * @param string $filePath
     * @param int|null $fileId
     * @return bool false if need to stop save process.
     */
    public function beforeDelete($filePath, $fileId = null)
    {
        return true;
    }

    /**
     * @param string $filePath
     * @param int|null $fileId
     */
    public function afterDelete($filePath, $fileId = null)
    {
        // nothing to do
    }

    /**
     * @return FileHelper
     */
    public function getOwner()
    {
        return $this->_owner;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
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
        return array();
    }

    /**
     * @param FileUploader $widget
     */
    public function beforeRenderWidget(FileUploader $widget)
    {
        // nothing to do
    }
}
