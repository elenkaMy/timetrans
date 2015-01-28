<?php


/**
 * @property AdminModule $module
 * @method AdminModule getModule()
 * 
 * @property-read ActiveRecord $model
 * @property-read string $attribute
 */
class FileController extends AdminController
{
    /**
     * @var CActiveRecord|null
     */
    private $_model;

    /**
     * @var string|null
     */
    private $_attribute;

    public function filters()
    {
        return array_merge(parent::filters(), array(
            array(
                'application.components.web.ContextFilter + '.implode(', ', array(
                    'upload',
                    'deleteFiles',
                )),
                'context'       =>  Response::CONTEXT_JSON,
            ),
            'ajaxOnly + '.implode(', ', array(
                'upload',
                'deleteFiles',
            )),
            'postOnly + '.implode(', ', array(
                'upload',
                'deleteFiles',
            )),
            'checkModelAttribute + '.implode(', ', array(
                'upload',
                'deleteFiles',
            )),
            'checkHash + '.implode(', ', array(
                'upload',
                'deleteFiles',
            )),
        ));
    }

    public function filterCheckModelAttribute(CFilterChain $filterChain)
    {
        $model = $this->request->getQuery('model');
        if (!file_exists(Yii::getPathOfAlias('application.models').'/'.$model.'.php')) {
            throw new CHttException(400, 'Incorrect model.');
        }
        try {
            $model = ActiveRecord::model($model);
        } catch (Exception $e) {
            throw new CHttpException(400, 'Cannot create model. ', $e);
        }

        Yii::import('application.components.db.behaviors.DbFileBehavior');
        $founded = false;
        foreach (array_keys($model->behaviors()) as $behaviorName) {
            if ($model->asa($behaviorName) instanceof DbFileBehavior) {
                $founded = true;
                break;
            }
        }
        if (!$founded) {
            throw new CHttpException(500, 'Model '.get_class($model).' does not have file behavior.');
        }

        $attribute = $this->request->getQuery('attribute');
        if (!$model->hasAttribute($attribute) || !$model->hasFileColumn($attribute)) {
            throw new CHttpException(400, 'Incorrect attribute.');
        }

        $this->_model = $model;
        $this->_attribute = $attribute;
        $filterChain->run();
    }

    public function filterCheckHash(CFilterChain $filterChain)
    {
        $hash = $this->request->getQuery('hash');
        if (!$this->model->existsSessionData($this->attribute, $hash)) {
            throw new CHttpException(400, 'Incorrect hash.');
        }

        $this->model->setFileColumnHash($this->attribute, $hash);
        $filterChain->run();
    }

    public function getModel()
    {
        if (is_null($this->_model)) {
            throw new CException('Model is not defined.');
        }
        return $this->_model;
    }

    public function getAttribute()
    {
        if (is_null($this->_attribute)) {
            throw new CException('Attribute is not defined.');
        }
        return $this->_attribute;
    }

    public function actionUpload()
    {
        $file = new File('upload');
        $file->file = CUploadedFile::getInstance($file, 'file');

        /* @var $context UploadFileContext */
        $context = $this->model->getFileContext($this->attribute);
        $hash = $this->request->getQuery('hash');

        $file->fileValidator = $context->validator;

        if ($file->validate()) {
            $path = $this->fileHelper->generateDir($file->file);
            $filePath = $this->fileHelper->cutPath($path).'/';

            $file->path = $filePath.$file->file;
            $file->mime_type = $file->file->getType();
            $file->size = $file->file->getSize();
            $file->ext = $file->file->getExtensionName();

            if (false === $context->beforeSave($path.$file->file, $file)) {
                throw new CHttpException(500, 'Cannot save file, context error.');
            }
            if ($file->file->saveAs($this->fileHelper->normalizeFSName($path.$file->file))) {
                $context->afterSave($path.$file->file, $file);
            } else {
                throw new CHttpException(500, 'Cannot save file.');
            }

            $this->model->writeFileToSession($file, $this->attribute);

            $this->data = array($this->model->getFileInfoForWidget($file, $this->attribute, array(
                'delete_url'    =>  $this->createUrl('deleteFiles', array(
                    'model'         =>  get_class($this->model),
                    'attribute'     =>  $this->attribute,
                    'hash'          =>  $this->model->getFileColumnHash($this->attribute),
                    'path'          =>  $file->path,
                )),
                'delete_type'   =>  'POST',
            )));
        } else {
            $this->data = array(array(
                'error' =>  $this->implodeRecursive(' ', $file->getErrors()),
            ));
        }
    }

    /**
     * @param string $separator
     * @param array $array
     * @return string
     */
    protected function implodeRecursive($separator, array $array)
    {
        $result = null;
        foreach ($array as $value) {
            if ($result === null) {
                $result = '';
            } else {
                $result .= $separator;
            }
            if (is_array($value)) {
                $result .= $this->implodeRecursive($separator, $value);
            } else {
                $result .= (string) $value;
            }
        }
        return is_null($result) ? '' : $result;
    }

    public function actionDeleteFiles()
    {
        if (false === $path = $this->request->getQuery('path', false)) {
            throw new CHttpException(400, 'File path required.');
        }

        /* @var $context UploadFileContext */
        $context = $this->model->getFileContext($this->attribute);
        $fileId = $this->model->getFileIdByPath($path, $this->attribute) ?: null;

        if ($context->beforeDelete($fullPath = Yii::app()->basePath.'/..'.$path, $fileId) === false) {
            throw new CHttpException(500, 'Cannot delete file, context error.');
        }
        $this->model->deleteFileFromSession($path, $this->attribute);
        if (is_null($fileId) && file_exists($this->fileHelper->normalizeFSName($fullPath))) {
            @unlink($this->fileHelper->normalizeFSName($fullPath));
        }
        $context->afterDelete($fullPath, $fileId);

        $this->data = array(
            'success'   =>  true,
        );
    }

}
