<?php

class ImageValidator extends FileValidator
{
    public $mimeTypes = array(
        'image/jpg',
        'image/jpeg',
        'image/gif',
        'image/png',
    );

    public $types = array(
        'jpg',
        'jpeg',
        'gif',
        'png',
    );


    /**
     * @var int width of the image
     */
    public $width;

    /**
     * @var int height of the image
     */
    public $height;

    /**
     * @var string image calculate dimension error message
     */
    public $calcDimensionError;

    /**
     * @var string image dimension error message
     */
    public $dimensionError;
    /**
     * @var string incorrect image width error message
     */
    public $incorrectWidthError;
    /**
     * @var string incorrect image height error message
     */
    public $incorrectHeightError;

    /**
     * Internally validates a file object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * @param CUploadedFile $file uploaded file passed to check against a set of rules
     * @throws CException if failed to upload the file
     */
    protected function validateFile($object, $attribute, $file)
    {
        parent::validateFile($object, $attribute, $file);

        if (!$object->hasErrors($attribute) && (!empty($this->width) || !empty($this->height))) {
            if (!function_exists('getimagesize')) {
                throw new CException('Function getimagesize required for ImageValidator');
            }

            $data = file_exists($file->tempName) ? @getimagesize($file->tempName) : false;
            if (empty($data)) {
                $message = $this->calcDimensionError ? $this->calcDimensionError : Yii::t('yii', 'An error occured on calculating image dimension');
                $this->addError($object, $attribute, $message);
                return;
            }

            $isIncorrectWidth = !empty($this->width) && $data[0] != $this->width;
            $isIncorrectHeight = !empty($this->height) && $data[1] != $this->height;

            if ($isIncorrectWidth && $isIncorrectHeight) {
                $message = $this->dimensionError ? $this->dimensionError : Yii::t('yii', 'Image dimension should be {width}x{height}.');
                $this->addError($object, $attribute, $message, array(
                    '{width}'   =>  $this->width,
                    '{height}'  =>  $this->height,
                ));
            } elseif ($isIncorrectWidth) {
                $message = $this->incorrectWidthError ? $this->incorrectWidthError : Yii::t('yii', 'Image width should be {width}.');
                $this->addError($object, $attribute, $message, array(
                    '{width}'   =>  $this->width,
                ));
            } elseif ($isIncorrectHeight) {
                $message = $this->incorrectHeightError ? $this->incorrectHeightError : Yii::t('yii', 'Image height should be {height}.');
                $this->addError($object, $attribute, $message, array(
                    '{height}'  =>  $this->height,
                ));
            }
        }
    }
}
