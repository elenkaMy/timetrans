<?php

Yii::import('application.components.db.behaviors.file-behavior.AbstractFileSessionWorker', true);

class MultiFileSessionWorker extends AbstractFileSessionWorker
{
    /**
     * @param CModel $model
     * @param string $columnName
     * @param mixed $sessionData
     * @return bool false if need to stop save process.
     */
    public function beforeSaveColumn(CModel $model, $columnName, $sessionData)
    {
        if (!is_array($sessionData)) {
            throw new CException("Incorrect session data for column $columnName.");
        }

        if (!count($sessionData)) {
            return $model->{$columnName} = null;
        }

        $packFile = new PackFile();
        if (!$packFile->save(false)) {
            throw new CException("Cannot create new pack file for column $columnName.");
        }
        $model->{$columnName} = $packFile->id;

        foreach ($sessionData as $fileInfo) {
            if (!is_array($fileInfo)) {
                throw new CException('Incorrect file info in session for column '.$columnName.'.');
            }

            $filePackFile = $this->createFilePackFileFromInfo($fileInfo, $packFile);
            if (!$filePackFile->save(false)) {
                throw new CException("Cannot save file - package record for column $columnName.");
            }
        }
    }

    /**
     * @param array $fileInfo
     * @param PackFile $packFile
     * @return FilePackFile
     */
    protected function createFilePackFileFromInfo(array $fileInfo, PackFile $packFile)
    {
        $filePackFile = new FilePackFile();

        $filePackFile->position = FilePackFile::model()->getNextPosition($packFile);
        $filePackFile->pack_file_id = $packFile->id;
        $filePackFile->file_id = $this->getFileIdFromInfo($fileInfo);

        return $filePackFile;
    }

    /**
     * @param array $fileInfo
     * @return integer
     * @throws CException
     */
    protected function getFileIdFromInfo(array $fileInfo)
    {
        if (!isset($fileInfo['id'])) {
            $file = new File('insertFileInDbFileBehavior');
            $file->attributes = $fileInfo;
            if (!$file->save()) {
                throw new CException("Cannot save file.");
            }
            return $file->id;
        } else {
            return $fileInfo['id'];
        }
    }

    /**
     * @param CModel $model
     * @param string $columnName
     * @return mixed
     */
    public function getDbFilesForSession(CModel $model, $columnName)
    {
        $packFile = !$model->{$columnName} ? null : PackFile::model()->findByPk($model->{$columnName});
        if (!$packFile) {
            return array();
        }

        /* @var $packFile PackFile */
        $result = array();
        foreach ($packFile->files as $file) {
            $result[$file->path] = $this->getFileInfoForSession($file);
        }
        return $result;
    }

    /**
     * @param File $file
     * @param mixed $sessionData
     * @return mixed
     */
    public function addFileToSessionData(File $file, $sessionData)
    {
        if (!is_array($sessionData)) {
            throw new CException('Session data must be an array.');
        }
        $sessionData[(string) $file->path] = $this->getFileInfoForSession($file);
        return $sessionData;
    }

    /**
     * @param string $path
     * @param mixed $sessionData
     * @return mixed
     */
    public function removeFileFromSessionData($path, $sessionData)
    {
        if (!is_array($sessionData)) {
            throw new CException('Session data must be an array.');
        }
        if (isset($sessionData[$path])) {
            unset($sessionData[$path]);
        }
        return $sessionData;
    }

    /**
     * @param string $path
     * @param mixed $sessionData
     * @return mixed
     */
    public function getFileIdByPathFromSessionData($path, $sessionData)
    {
        if (!is_array($sessionData)) {
            throw new CException('Session data must be an array.');
        }
        if (isset($sessionData[$path], $sessionData[$path]['id'])) {
            return $sessionData[$path]['id'];
        }
        return null;
    }

    /**
     * @param mixed $sessionData
     * @return integer
     */
    public function getFilesCountFromSessionData($sessionData)
    {
        if (!is_array($sessionData)) {
            throw new CException('Session data must be an array.');
        }
        return count($sessionData);
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
        $result = array();
        foreach ($sessionData as $fileInfo) {
            $result[] = $this->getFileInfoForWidget($fileInfo, $context, $params);
        }
        return $result;
    }
}
