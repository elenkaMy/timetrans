<?php

class TranslatesCommand extends AbstractCommand
{
    public $defaultAction = 'generate';

    public $shellCommand = 'msgfmt -cv -o <to> <from>';

    /**
     * This command generates mo from po files.
     * @param string $dir messages directories to search.
     */
    public function actionGenerate($dir = null)
    {
        if (is_null($dir)) {
            $dir = __DIR__.'/../messages';
        }

        $files = CFileHelper::findFiles($dir, array(
            'fileTypes' =>  array(
                'po',
            ),
        ));

        foreach ($files as $file) {
            echo "Generating translates for $file".PHP_EOL;
            echo $this->regenerateMoFile($file);
        }
    }

    public function regenerateMoFile($file)
    {
        $moFilePath = pathinfo($file, PATHINFO_DIRNAME) .
            DIRECTORY_SEPARATOR . pathinfo($file, PATHINFO_FILENAME) . '.mo';


        $output = array();
        $command = str_replace(array('<from>', '<to>'), array($file, $moFilePath), $this->shellCommand);
        exec($command, $output);
        return implode(PHP_EOL, $output);
    }
}
