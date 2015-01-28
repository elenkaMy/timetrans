<?php

Yii::import('application.components.db.behaviors.file-behavior.AbstractFileSessionWorker', true);

class SingleFileSessionWorker extends AbstractFileSessionWorker
{
    /**
     * @param CModel $model
     * @param string $columnName
     * @param mixed $sessionData
     * @return bool false if need to stop save process.
     */
    public function beforeSaveColumn(CModel $model, $columnName, $sessionData)
    {
        if (empty($sessionData)) {
            $model->{$columnName} = null;
        } elseif (!isset($sessionData['id'])) {
            $file = new File('insertFileInDbFileBehavior');
            $file->attributes = $sessionData;
            if (!$file->save()) {
                throw new CException('Could not save file for column '.$columnName.'.');
            }

            $model->{$columnName} = $file->id;
        } else {
            $model->{$columnName} = $sessionData['id'];
        }
    }

    /**
     * @param CModel $model
     * @param string $columnName
     * @return mixed
     */
    public function getDbFilesForSession(CModel $model, $columnName)
    {
        $file = !$model->{$columnName} ? null : File::model()->findByPk($model->{$columnName});
        return $this->getFileInfoForSession($file);
    }

    /**
     * @param File $file
     * @param mixed $sessionData
     * @return mixed
     */
    public function addFileToSessionData(File $file, $sessionData)
    {
        return $this->getFileInfoForSession($file);
    }

    /**
     * @param string $path
     * @param mixed $sessionData
     * @return mixed
     */
    public function removeFileFromSessionData($path, $sessionData)
    {
        return (@$sessionData['path'] === $path) ? null : $sessionData;
    }

    /**
     * @param string $path
     * @param mixed $sessionData
     * @return mixed
     */
    public function getFileIdByPathFromSessionData($path, $sessionData)
    {
        return (@$sessionData['path'] === $path)
            ? (isset($sessionData['id']) ? $sessionData['id'] : null)
            : $sessionData;
    }

    /**
     * @param mixed $sessionData
     * @return integer
     */
    public function getFilesCountFromSessionData($sessionData)
    {
        return empty($sessionData) ? 0 : 1;
    }

    /**
     * @param mixed $sessionData
     * @return bool
     */
    public function isEmptySessionData($sessionData)
    {
        return empty($sessionData);
    }

    /**
     * @param mixed $sessionData
     * @param UploadFileContext $context
     * @param array $params
     * @return mixed
     */
    public function getFilesInfoForWidget($sessionData, UploadFileContext $context, array $params = array())
    {
        return $sessionData ? array(
            $this->getFileInfoForWidget($sessionData, $context, $params),
        ) : array();
    }
}
