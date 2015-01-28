<?php

/**
 * @property-read string $filepath
 */
class UploadedFileController extends Controller
{
    public function filters()
    {
        return array_merge(parent::actions(), array(
            'fileExists',
        ));
    }

    public function filterFileExists(CFilterChain $filterChain)
    {
        if (!file_exists($this->filepath)) {
            throw new CHttpException(404, Yii::t('yii', 'Unable to resolve the request "{route}".', array(
                '{route}'   =>  $this->request->requestUri,
            )));
        }
        $filterChain->run();
    }

    /**
     * @return string
     */
    public function getFilepath()
    {
        $pathinfo = $this->fileHelper->normalizeFSName($this->request->pathInfo);
        return rtrim(Yii::app()->basePath, '\/').'/../'.ltrim($pathinfo, '\/');
    }

    public function actionSend()
    {
        if ($this->request->getQuery('download')) {
            return $this->forward('download');
        }
        $this->request->sendFile($this->filepath, file_get_contents($this->filepath), null, true, false);
    }

    public function actionDownload()
    {
        $this->request->sendFile($this->filepath, file_get_contents($this->filepath));
    }
}
