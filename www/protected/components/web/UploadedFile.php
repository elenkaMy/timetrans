<?php

/**
 * @property-read CActiveRecord|null $model
 * @property-read string|null $attributeName
 * @property-read string|null $oldFilename
 */
class UploadedFile extends CUploadedFile
{
    /**
     * @var string directory for using in {@link saveTo}
     */
    public $dir = null;

    /**
     * @var whether save file on model saving. Used if model attached.
     */
    public $autoSave = true;

    /**
     * @var CActiveRecord|null
     */
    protected $_model = null;

    /**
     * @var string|null used if model attached
     */
    protected $_attributeName = null;

    /**
     * @var string|null old file name. Used if model attached.
     */
    public $_oldFilename = null;

    /**
     * @return CActiveRecord|null
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @return string|null
     */
    public function getAttributeName()
    {
        return $this->_attributeName;
    }

    /**
     * @return string|null
     */
    public function getOldFilename()
    {
        return $this->_oldFilename;
    }

    /**
     * @param CActiveRecord $model
     * @param string $attributeName
     * @param string $dir
     */
    public function attachToModelAttribute(CActiveRecord $model, $attributeName, $dir)
    {
        if ($this->_model instanceof CActiveRecord) {
            if ($this->_model === $model) {
                return;
            }
            throw new CException('UploadedFile object already attached to any model.');
        }

        if (is_null($dir)) {
            throw new CException('You must define directory for attaching UploadedFile on model.');
        }

        $this->_model = $model;
        $this->_attributeName = $attributeName;
        $this->dir = $dir;

        if ($model->{$attributeName} !== $this) {
            if (!($model->{$attributeName} instanceof self)) {
                $this->_oldFilename = $model->{$attributeName};
            }
            $model->{$attributeName} = $this;
        }

        $model->attachEventHandler('onBeforeSave', array($this, 'onBeforeModelSave'));
    }

    public function detachModel()
    {
        if (!($this->_model instanceof CActiveRecord)) {
            return false;
        }

        if (!empty($this->_attributeName) && ($this->_model instanceof CActiveRecord)) {
            $this->detachEventHandler('onBeforeSave', array($this, 'onBeforeModelSave'));
        }

        $this->_model = null;
        $this->_attributeName = null;
        return true;
    }

    public function onBeforeModelSave(CModelEvent $event)
    {
        if (!$this->autoSave || empty($this->dir)) {
            return;
        }

        $this->saveTo();
    }

    /**
     * Saves the uploaded file.
     * Note: this method uses php's move_uploaded_file() method. As such, if the target file ($file) 
     * already exists it is overwritten.
     * @param string $file the file path used to save the uploaded file
     * @param boolean $deleteTempFile whether to delete the temporary file after saving.
     * If true, you will not be able to save the uploaded file again in the current request.
     * @param boolean $replaceNameAttribute whether to change {@link name} property of this object.
     * @return boolean true whether the file is saved successfully
     */
    public function saveAs($file, $deleteTempFile = true, $replaceNameAttribute = true)
    {
        $result = parent::saveAs($file, $deleteTempFile);

        if ($replaceNameAttribute && $result) {
            $this->_name = basename($file);
        }

        return $result;
    }

    /**
     * Generates and returns new random string. Used in {@link saveTo}.
     * @return string
     */
    protected function generateRandomString()
    {
        return sha1(uniqid('', true));
    }

    /**
     * Saves the uploaded file into specific dir with generating new filename.
     * Note: this method uses php's move_uploaded_file() method. As such, if the target file ($file) 
     * already exists it is overwritten.
     * @param string $dir the directory path used to save the uploaded file.
     * If null {@link dir} property will use.
     * @param boolean $deleteOldFile whether to delete old file {@link $oldFilename}
     * @param boolean $deleteTempFile whether to delete the temporary file after saving.
     * If true, you will not be able to save the uploaded file again in the current request.
     * @param boolean $replaceNameAttribute whether to change {@link name} property of this object.
     * @return boolean true whether the file is saved successfully
     */
    public function saveTo($dir = null, $deleteOldFile = true, $deleteTempFile = true, $replaceNameAttribute = true)
    {
        $dir = rtrim(is_null($dir) ? $this->dir : $dir, '/\\').DIRECTORY_SEPARATOR;
        if ($deleteOldFile && !empty($this->_oldFilename) && file_exists($dir.$this->_oldFilename)) {
            @unlink($dir.$this->_oldFilename);
            $this->_oldFilename = null;
        }

        $ext = $this->getExtensionName();
        $ext = empty($ext) ? '' : ('.'.$ext);

        // generating new filename
        while (file_exists($filename = $dir.$this->generateRandomString().$ext));

        return $this->saveAs($filename, $deleteTempFile, $replaceNameAttribute);
    }
}
