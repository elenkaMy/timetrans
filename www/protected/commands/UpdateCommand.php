<?php

/**
 * @property-read DbConnection $db
 * @property-read string $dump
 */
class UpdateCommand extends AbstractCommand
{
    public $gitCommand = 'git pull';

    public $dumpPath = 'application.data';
    public $dumpFile = 'dump.sql';

    public $uploadPath = 'application.data.upload';

    public $afterUpdate;

    /**
     * @return DbConnection
     */
    public function getDb()
    {
        return Yii::app()->db;
    }

    public function actionGit($branch = 'master', $remote = 'origin')
    {
        if ($branch && $remote && $this->confirm('Pull from git?')) {
            $this->printf('Pull all changes from git.');
            exec($this->gitCommand.' '.escapeshellarg($remote).' '.escapeshellarg($branch));
        }

        if (!is_null($this->dumpPath) && !is_null($this->dumpFile) && $this->confirm('Update database?')) {
            $this->db->createCommand('SET foreign_key_checks = 0;')->execute();
            try {
                /* @var $table CDbTableSchema */
                foreach ($this->db->schema->tables as $table) {
                    $this->printf("DROP TABLE '$table->name'");
                    $this->db->createCommand('DROP TABLE '.$table->rawName)->execute();
                }
            } catch (Exception $e) {
                $this->printf('Error: '.$e->getMessage());
            }
            $this->db->createCommand('SET foreign_key_checks = 1;')->execute();

            $this->printf('Import dump...');
            $begin = time();
            $this->db->pdoInstance->exec($this->dump);
            $this->printf('Imported ('.(time() - $begin).' sec)');
        }

        if (!is_null($this->uploadPath) && $this->confirm('Copy upload dir?')) {
            $dir = rtrim(realpath(Yii::getPathOfAlias($this->uploadPath)), '\/');
            $wwwUpload = rtrim(realpath(rtrim(Yii::getPathOfAlias('application'), '\/').'/../upload'), '\/');
            $copyFiles = array();
            foreach (CFileHelper::findFiles($dir) as $file) {
                $file = realpath($file);
                for ($pos = 0; $pos < min(strlen($dir), strlen($file)); ++$pos) {
                    if ($dir[$pos] !== $file[$pos]) {
                        break;
                    }
                }
                $newFilePath = $wwwUpload.'/'.substr($file, $pos);
                $copyFiles[substr($file, $pos)] = array('source' => $file, 'target' => $newFilePath);
            }
            $this->copyFiles($copyFiles);
        }

        if ($this->confirm('Apply migrations?')) {
            $this->commandRunner->run(array(
                'yiic',
                'migrate',
                '--interactive=0',
            ));
        }

        if ($this->confirm('Clear cache and assets?')) {
            $this->commandRunner->run(array(
                'yiic',
                'clearcache',
                'all',
                '--interactive=0',
            ));
        }

        if ($this->confirm('Generate translates?')) {
            $this->commandRunner->run(array(
                'yiic',
                'translates',
            ));
        }

        if ($this->confirm('Dump assets?')) {
            $this->commandRunner->run(array(
                'yiic',
                'assets',
                'dump',
            ));
        }

        if (!is_null($this->afterUpdate)) {
            foreach ((array) $this->afterUpdate as $command) {
                $cmdArr = is_array($command) ? $command : explode(' ', $command);
                $cmdStr = is_array($command) ? implode(' ', $command) : $command;
                if ($this->confirm("Exec command '$cmdStr'?")) {
                    $this->commandRunner->run($cmdArr);
                }
            }
        }
    }

    /**
     * @return string
     * @throws CException
     */
    public function getDump()
    {
        $dir = rtrim(Yii::getPathOfAlias($this->dumpPath), '\/');
        $file = "$dir/$this->dumpFile";
        if (!($result = file_get_contents($file))) {
            throw new CException("Cannot read file $file!");
        }
        return $result;
    }

    public function actionExport()
    {
        if (!is_null($this->dumpPath) && !is_null($this->dumpFile) && $this->confirm('Dump database?')) {
            $db = Yii::app()->db;
            /* @var $db DbConnection */

            Yii::import('ext.dump-db.dumpDB');
            $dumpPath = rtrim(Yii::getPathOfAlias($this->dumpPath), '\/').'/'.$this->dumpFile;
            $dumper = new dumpDB();
            $sql = $dumper->getDump(false);

            if (file_exists($dumpPath)) {
                $this->printf('Delete '.$dumpPath.'.');
                if (!@unlink($dumpPath)) {
                    throw new CException('Cannot delete old dump file.');
                }
            }
            if (!@file_put_contents($dumpPath, $sql)) {
                $this->printf("Warning: cannot write dump file $dumpPath!");
            } else {
                $this->printf("Dump has been exported successfully.\nSee: $dumpPath");
            }
        }

        if (!is_null($this->uploadPath) && $this->confirm('Copy upload dir?')) {
            if (!is_dir($dir = Yii::getPathOfAlias($this->uploadPath)) && !@mkdir($dir, 0755, true)) {
                throw new CException("Cannot create directory: $dir");
            }
            $dir = rtrim(realpath(rtrim($dir, '\/').'/../'), '\/');
            $wwwUpload = rtrim(realpath(rtrim(Yii::getPathOfAlias('application'), '\/').'/../upload'), '\/');
            $copyFiles = array();
            $options = array(
                'exclude'   =>  array(
                    '.gitignore',
                    '.htaccess',
                    '.gitkeep',
                ),
            );
            foreach (CFileHelper::findFiles($wwwUpload, $options) as $file) {
                $file = realpath($file);
                for ($pos = 0; $pos < min(strlen($dir), strlen($file)); ++$pos) {
                    if ($dir[$pos] !== $file[$pos]) {
                        break;
                    }
                }
                $newFilePath = $dir.'/'.substr($file, $pos);
                $copyFiles[substr($file, $pos)] = array('source' => $file, 'target' => $newFilePath);
            }
            $this->copyFiles($copyFiles);
        }
    }
}
