<?php
/**
 * This is the template for generating the action script for the form.
 * - $this: the CrudCode object
 */
?>
<?php
$viewName = basename($this->viewName);
?>
public function action<?php echo ucfirst(trim($viewName,'_')); ?>()
{
    $model = new <?php echo $this->modelClass; ?><?php echo empty($this->scenario) ? '' : "('{$this->scenario}')"; ?>;

    // uncomment the following code to enable ajax-based validation
    /*
    $ajax = $this->request->getPost('ajax', false);
    if ($ajax === '<?php echo $this->class2id($this->modelClass); ?>-<?php echo $viewName; ?>-form') {
        echo CActiveForm::validate($model);
        Yii::app()->end();
    }
    */

    if ($this->request->isPostRequest) {
        $model->attributes = $this->request->getPost('<?php echo $this->modelClass; ?>', array());
        if ($model->validate()) {
            // form inputs are valid, do something here
            return;
        }
    }
    $this->render('<?php echo $viewName; ?>', array(
        'model' =>  $model,
    ));
}
