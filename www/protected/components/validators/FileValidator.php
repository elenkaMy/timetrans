<?php

class FileValidator extends CFileValidator
{
    /**
     * @var boolean whether need or not initialize CUploadedFile objects on validate.
     */
    public $autoInit = true;

    /**
     *
     * @var string directory for saving.
     */
    public $dir = null;

    /**
     * Set the attribute and then validates using {@link validateFile}.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute)
    {
        if ($this->autoInit) {
            if ($this->maxFiles > 1) {
                // may be it will be implemented in future
                throw new CException('FileValidator not allowed auto initializing for multiple files');
            } else {
                $file = $object->$attribute;
                if (!($file instanceof CUploadedFile)) {
                    $file = UploadedFile::getInstance($object, $attribute);
                    if (!empty($file)) {
                        $file->attachToModelAttribute($object, $attribute, $this->dir);
                    }
                }
            }
        }
        return parent::validateAttribute($object, $attribute);
    }
}
