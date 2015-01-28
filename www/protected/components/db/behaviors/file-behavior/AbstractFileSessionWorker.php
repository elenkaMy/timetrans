<?php

Yii::import('application.components.file-upload.file-contexts.UploadFileContext');

abstract class AbstractFileSessionWorker extends CComponent
{
    /**
     * @param CModel $model
     * @param string $columnName
     * @param mixed $sessionData
     * @return bool false if need to stop save process.
     */
    abstract public function beforeSaveColumn(CModel $model, $columnName, $sessionData);

    /**
     * @param mixed $sessionData
     * @return bool
     */
    abstract public function isEmptySessionData($sessionData);

    /**
     * @param CModel $model
     * @param string $columnName
     * @return mixed
     */
    abstract public function getDbFilesForSession(CModel $model, $columnName);

    /**
     * @param mixed $sessionData
     * @param UploadFileContext $context
     * @param array $params
     * @return mixed
     */
    abstract public function getFilesInfoForWidget($sessionData, UploadFileContext $context, array $params = array());

    /**
     * @param File $file
     * @param mixed $sessionData
     * @return mixed
     */
    abstract public function addFileToSessionData(File $file, $sessionData);

    /**
     * @param string $path
     * @param mixed $sessionData
     * @return mixed
     */
    abstract public function removeFileFromSessionData($path, $sessionData);

    /**
     * @param string $path
     * @param mixed $sessionData
     * @return mixed
     */
    abstract public function getFileIdByPathFromSessionData($path, $sessionData);

    /**
     * @param mixed $sessionData
     * @return integer
     */
    abstract public function getFilesCountFromSessionData($sessionData);

    /**
     * @param File $file
     * @return array|null
     */
    protected function getFileInfoForSession(File $file = null)
    {
        return is_null($file) ? null : $file->getAttributes(array(
            'id',
            'file',
            'path',
            'mime_type',
            'size',
            'ext',
        ));
    }

    /**
     * @param array $fileInfo
     * @param UploadFileContext $context
     * @param array $params
     * @return array
     */
    protected function getFileInfoForWidget(array $fileInfo, UploadFileContext $context, array $params = array())
    {
        return $this->getFileInfo(
            $fileInfo['file'],
            $fileInfo['path'],
            $fileInfo['mime_type'],
            $fileInfo['size'],
            $context,
            $params
        );
    }

    /**
     * @param string $name
     * @param string $path
     * @param string $mimeType
     * @param int $size
     * @param UploadFileContext $context
     * @param array $params
     * @return array
     */
    public function getFileInfo($name, $path, $mimeType, $size, UploadFileContext $context, array $params = array())
    {
        return array_merge(array(
            'name'          =>  $name,
            'path'          =>  $path,
            'type'          =>  $mimeType,
            'size'          =>  $size,
            'url'           =>  Yii::app()->baseUrl.$path,
            'thumbnail_url' =>  null,
        ), $context->getFileParamsForWidget(
            $name,
            $path,
            $mimeType,
            $size
        ), $params);
    }
}
