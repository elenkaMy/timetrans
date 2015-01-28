<?php
Yii::import('application.extensions.xupload.XUpload');

/**
 * Class FileUploader
 * 
 * @property-read CModel $updatingModel
 * @property-read string $updatingAttribute
 */
class FileUploader extends XUpload
{
    /**
     * @var bool by default try to detect by model behaviors
     */
    public $multiple = null;

    public $renderLabel = true;
    public $renderErrors = true;

    public $uploadRoute = 'admin/file/upload';
    public $deleteRoute = 'admin/file/deleteFiles';

    public $formView = 'file-uploader/form';
    public $uploadView = 'xupload.views.upload';
    public $downloadView = 'xupload.views.download';
    public $fileUploaderView = 'file-uploader/file-uploader';
    public $labelView = 'file-uploader/_label';

    public $showForm = false;

    /**
     * @var CModel
     */
    private $_updatingModel;
    /**
     * @var string
     */
    private $_updatingAttribute;

    /**
     * @return File
     */
    protected function createFile()
    {
        $file = new File();
        return $file;
    }

    public function init()
    {
        if (!$this->hasModel()) {
            throw new CException('You must specify model and attribute for '.__CLASS__.'.');
        }
        $this->_updatingModel = $this->model;
        $this->_updatingAttribute = $this->attribute;
        $this->model = $this->createFile();
        $this->attribute = 'file';

        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = '__file_uploader_'.sha1(uniqid('', true)).'__';
        }
        if (!isset($this->options['uploadTemplateId'])) {
            $this->options['uploadTemplateId'] = 'template-upload'.$this->htmlOptions['id'];
        }
        if (!isset($this->options['downloadTemplateId'])) {
            $this->options['downloadTemplateId'] = 'template-download'.$this->htmlOptions['id'];
        }

        if (is_null($this->multiple)) {
            $this->multiple = $this->updatingModel->isMultipleFileColumn($this->updatingAttribute);
        }

        $this->updatingModel->beforeRenderWidget($this->updatingAttribute, $this);

        if (is_null($this->url)) {
            $this->url = Yii::app()->createUrl($this->uploadRoute, array(
                'model'     =>  get_class($this->updatingModel),
                'attribute' =>  $this->updatingAttribute,
                'hash'      =>  $this->updatingModel->getFileColumnHash($this->updatingAttribute),
            ));
        }
        return parent::init();
    }

    public function run()
    {
        if ($this->renderLabel) {
            $this->render($this->labelView, array(
                'model'     =>  $this->updatingModel,
                'attribute' =>  $this->updatingAttribute,
            ));
        }

        echo CHtml::openTag('div', $this->htmlOptions);

        if ($this->uploadTemplate === null) {
            $this->uploadTemplate = 'template-upload'.$this->htmlOptions['id'];
        }
        if ($this->downloadTemplate === null) {
            $this->downloadTemplate = 'template-download'.$this->htmlOptions['id'];
        }
        parent::run();

        $files = $this->updatingModel->getFilesInfoForWidget($this->updatingAttribute);
        foreach ($files as &$file) {
            $file = array_merge($file, array(
                'delete_url'    =>  Yii::app()->createUrl($this->deleteRoute, array(
                    'model'         =>  get_class($this->updatingModel),
                    'attribute'     =>  $this->updatingAttribute,
                    'hash'          =>  $this->updatingModel->getFileColumnHash($this->updatingAttribute),
                    'path'          =>  $file['path'],
                )),
                'delete_type'   =>  'POST',
            ));
        }

        $this->render($this->fileUploaderView, array(
            'model'     =>  $this->updatingModel,
            'attribute' =>  $this->updatingAttribute,
            'hash'      =>  $this->updatingModel->getFileColumnHash($this->updatingAttribute),
            'files'     =>  $files,
        ));

        echo CHtml::closeTag('div');

    }

    /**
     * @return CModel
     */
    public function getUpdatingModel()
    {
        if (is_null($this->_updatingModel) || !($this->_updatingModel instanceof CModel)) {
            throw new CException('Incorrect updating model in '.__CLASS__.'.');
        }
        return $this->_updatingModel;
    }

    /**
     * @return CModel
     */
    public function getUpdatingAttribute()
    {
        if (!is_string($this->_updatingAttribute)) {
            throw new CException('Incorrect updating attribute in '.__CLASS__.'.');
        }
        return $this->_updatingAttribute;
    }
}
