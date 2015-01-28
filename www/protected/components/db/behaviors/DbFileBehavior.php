<?php

Yii::import('application.widgets.FileUploader');

/**
 * @property UploadFileContext[] $columns column names as keys
 * @property-read bool $isMultiple
 * @property string $sessionParamName by default '__files__'.
 * @property string $fileHashPrefix by default '__file_hash__'.
 */
class DbFileBehavior extends CActiveRecordBehavior
{
    /**
     * @var UploadFileContext[] column names as keys
     */
    private $_columns = array();

    /**
     * @var array of multiple column names
     */
    public $multipleColumns = array();

    /**
     * @var string|null
     */
    private $_fileHashPrefix;

    /**
     * @var string|null
     */
    private $_sessionParamName;

    /**
     * @var array in column name => hash format
     */
    private $_hashes;

    private $_workers = array();

    /**
     * @param string $columnName
     * @return bool
     */
    public function isEmptyColumnSessionData($columnName)
    {
        return $this->getColumnWorker($columnName)->isEmptySessionData($this->getSessionDataByColumn($columnName));
    }

    /**
     * @param string $columnName
     * @param string $hash
     * @return boolean
     */
    public function existsSessionData($columnName, $hash)
    {
        return isset(Yii::app()->session[$this->sessionParamName][$hash])
            && is_array(Yii::app()->session[$this->sessionParamName][$hash])
            && array_key_exists($columnName, Yii::app()->session[$this->sessionParamName][$hash]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasFileColumn($name)
    {
        return isset($this->_columns[$name]);
    }

    /**
     * @return UploadFileContext[]
     */
    public function getColumns()
    {
        return $this->_columns;
    }

    /**
     * @param UploadFileContext[] $columns
     * @return CActiveRecord
     */
    public function setColumns(array $columns)
    {
        foreach ($columns as $context) {
            if (!($context instanceof UploadFileContext)) {
                throw new CException('Context must be an instance of UploadFileContext.');
            }
        }
        $this->_columns = $columns;
        return $this->owner;
    }

    /**
     * @param string $columnName
     * @return UploadFileContext
     * @throws CException
     */
    public function getFileContext($columnName)
    {
        if (!$this->hasFileColumn($columnName)) {
            throw new CException("Column $columnName did not found.");
        }
        return $this->columns[$columnName];
    }

    /**
     * @param string $columnName
     * @return bool
     */
    public function isMultipleFileColumn($columnName)
    {
        if (!$this->hasFileColumn($columnName)) {
            throw new CException("Cannot find column $columnName");
        }
        return in_array($columnName, $this->multipleColumns, true);
    }

    /**
     * @param string $columnName
     * @return AbstractFileSessionWorker
     */
    protected function getColumnWorker($columnName)
    {
        if (isset($this->_workers[$columnName])) {
            return $this->_workers[$columnName];
        }

        $class = 'application.components.db.behaviors.file-behavior.'.($this->isMultipleFileColumn($columnName)
            ? 'MultiFileSessionWorker'
            : 'SingleFileSessionWorker'
        );
        return $this->_workers[$columnName] = Yii::createComponent(array(
            'class'         =>  $class,
        ));
    }

    /**
     * @param string $columnName
     * @param bool $generateNewIfNotExists
     * @return string|boolean false if not found
     */
    public function getFileColumnHash($columnName, $generateNewIfNotExists = true)
    {
        if (isset($this->_hashes[$columnName])) {
            return $this->_hashes[$columnName];
        }

        if (Yii::app()->request->requestType === 'GET') {
            if ($generateNewIfNotExists) {
                $this->_hashes[$columnName] = $this->fileHashPrefix.Yii::app()->securityManager->generatePassword(12, 6, 6);
            } else {
                $this->_hashes[$columnName] = false;
            }
        } else {
            switch (false) {
                case $postData = (array) Yii::app()->request->getPost(get_class($this->owner)):
                case @$postData[$columnName]:
                    $this->_hashes[$columnName] = false;
                    break;
                default:
                    $this->_hashes[$columnName] = $postData[$columnName];
            }
        }
        return $this->_hashes[$columnName];
    }

    /**
     * @param string $columnName
     * @param string $hash
     * @return CActiveRecord
     */
    public function setFileColumnHash($columnName, $hash)
    {
        $this->_hashes[$columnName] = $hash;
        return $this->owner;
    }

    public function beforeSave($event)
    {
        foreach (array_keys($this->columns) as $columnName) {
            if ($this->getFileColumnHash($columnName, false) !== false) {
                $sessionData = $this->getSessionDataByColumn($columnName);
                if ($this->getColumnWorker($columnName)->beforeSaveColumn($this->owner, $columnName, $sessionData) === false) {
                    return false;
                }
            }
        }

        return parent::beforeSave($event);
    }

    public function afterSave($event)
    {
        foreach (array_keys($this->columns) as $columnName) {
            $this->clearSession($columnName);
        }

        return parent::afterSave($event);
    }

    /**
     * @param string $columnName
     * @return mixed
     * @throws CException
     */
    protected function getSessionDataByColumn($columnName)
    {
        if (false === $hash = $this->getFileColumnHash($columnName, false)) {
            throw new CException('Cannot find session file by hash.');
        }
        if (!isset(Yii::app()->session[$this->sessionParamName][$hash])) {
            throw new CException('You don\'t have access to download this files.');
        }
        $sessionFiles = Yii::app()->session[$this->sessionParamName][$hash];

        if (!is_array($sessionFiles) || !array_key_exists($columnName, $sessionFiles)) {
            throw new CException('Session files hasn\'t files for column '.$columnName.'.');
        }
        return $sessionFiles[$columnName];
    }

    /**
     * @param string $columnName
     * @param mixed $sessionData
     * @return CActiveRecord
     */
    protected function setSessionDataByColumn($columnName, $sessionData)
    {
        $hash = $this->getFileColumnHash($columnName);

        $sessionFiles = Yii::app()->session[$this->sessionParamName] ?: array();
        if (!isset($sessionFiles[$hash])) {
            $sessionFiles[$hash] = array();
        }

        $sessionFiles[$hash][$columnName] = $sessionData;

        Yii::app()->session[$this->sessionParamName] = $sessionFiles;
        return $this->owner;
    }

    /**
     * @param string $columnName
     * @return CActiveRecord
     */
    protected function clearSession($columnName)
    {
        if (false === $hash = $this->getFileColumnHash($columnName, false)) {
            return $this->owner;
        }

        if (isset(Yii::app()->session[$this->sessionParamName][$hash])) {
            $sessionFiles = Yii::app()->session[$this->sessionParamName];
            unset($sessionFiles[$hash]);
            Yii::app()->session[$this->sessionParamName] = $sessionFiles;
        }

        return $this->owner;
    }

    /**
     * @return string '__files__' by default.
     */
    public function getSessionParamName()
    {
        if (is_null($this->_sessionParamName)) {
            $this->_sessionParamName = '__files__';
        }
        return $this->_sessionParamName;
    }

    /**
     * @param string $paramName
     * @return CActiveRecord
     * @throws CException if param name already defined
     */
    public function setSessionParamName($paramName)
    {
        if (!is_null($this->_sessionParamName) && $this->_sessionParamName !== $paramName) {
            throw new CException('Session param name already defined.');
        }
        $this->_sessionParamName = $paramName;
        return $this->owner;
    }

    /**
     * @param string $columnName
     * @param FileUploader $widget
     * @return CActiveRecord
     */
    public function beforeRenderWidget($columnName, FileUploader $widget)
    {
        $this->prepareSession($columnName);
        $this->getFileContext($columnName)->beforeRenderWidget($widget);
        return $this->owner;
    }

    /**
     * @param string $columnName
     * @return CActiveRecord
     */
    protected function prepareSession($columnName)
    {
        if (Yii::app()->request->requestType !== 'GET') {
            return $this->owner;
        }

        $hash = $this->getFileColumnHash($columnName);

        $sessionFiles = Yii::app()->session[$this->sessionParamName] ?: array();
        if (!isset($sessionFiles[$hash])) {
            $sessionFiles[$hash] = array();
        }

        $sessionFiles[$hash][$columnName] = $this->getColumnWorker($columnName)->getDbFilesForSession($this->owner, $columnName);

        Yii::app()->session[$this->sessionParamName] = $sessionFiles;
        return $this->owner;
    }

    /**
     * @param File $file
     * @param string $columnName
     * @return CActiveRecord
     */
    public function writeFileToSession(File $file, $columnName)
    {
        $sessionData = $this->getSessionDataByColumn($columnName);
        $sessionData = $this->getColumnWorker($columnName)->addFileToSessionData($file, $sessionData);
        $this->setSessionDataByColumn($columnName, $sessionData);
        return $this->owner;
    }

    /**
     * @param string $path
     * @param string $columnName
     * @return CActiveRecord
     */
    public function deleteFileFromSession($path, $columnName)
    {
        $sessionData = $this->getSessionDataByColumn($columnName);
        $sessionData = $this->getColumnWorker($columnName)->removeFileFromSessionData($path, $sessionData);
        $this->setSessionDataByColumn($columnName, $sessionData);
        return $this->owner;
    }

    /**
     * @param string $path
     * @param string $columnName
     * @return int|null
     */
    public function getFileIdByPath($path, $columnName)
    {
        $sessionData = $this->getSessionDataByColumn($columnName);
        return $this->getColumnWorker($columnName)->getFileIdByPathFromSessionData($path, $sessionData);
    }

    /**
     * @param string $path
     * @param string $columnName
     * @return int
     */
    public function getSessionFilesCount($columnName)
    {
        $sessionData = $this->getSessionDataByColumn($columnName);
        return $this->getColumnWorker($columnName)->getFilesCountFromSessionData($sessionData);
    }

    /**
     * @return string '__file_hash__' by default.
     */
    public function getFileHashPrefix()
    {
        if (is_null($this->_fileHashPrefix)) {
            $this->_fileHashPrefix = '__file_hash__';
        }
        return $this->_fileHashPrefix;
    }

    /**
     * @param string $paramName
     * @return CActiveRecord
     * @throws CException if param name already defined
     */
    public function setFileHashPrefix($paramName)
    {
        if (!is_null($this->_fileHashPrefix) && $this->_fileHashPrefix !== $paramName) {
            throw new CException('File hash param name already defined.');
        }
        $this->_fileHashPrefix = $paramName;
        return $this->owner;
    }

    /**
     * @param string $columnName
     * @param array $params
     * @return array
     * @throws CException
     */
    public function getFilesInfoForWidget($columnName, array $params = array())
    {
        $sessionData = $this->getSessionDataByColumn($columnName);
        $context = $this->getFileContext($columnName);
        return $this->getColumnWorker($columnName)->getFilesInfoForWidget($sessionData, $context, $params);
    }

    /**
     * @param File $file
     * @param string $columnName
     * @param array $params
     * @return array
     */
    public function getFileInfoForWidget(File $file, $columnName, array $params = array())
    {
        $context = $this->getFileContext($columnName);
        return $this->getColumnWorker($columnName)->getFileInfo(
            (string) $file->file,
            $file->path,
            $file->mime_type,
            $file->size,
            $context,
            $params
        );
    }
}
