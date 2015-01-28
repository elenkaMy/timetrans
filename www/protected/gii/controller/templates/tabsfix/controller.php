<?php
/**
 * This is the template for generating a controller class file.
 * The following variables are available in this template:
 * - $this: the ControllerCode object
 */
?>
<?php echo "<?php\n"; ?>

/**
 * @property <?echo ucfirst($this->getModule()->id).'Module' ?> $module
 * @method <?echo ucfirst($this->getModule()->id).'Module' ?> getModule()
 */
class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
    // remove filters or uncomment if need
    /*
    public function filters()
    {
        return array(
            array( // filter for ajax actions (responses will returned in json format)
                'application.components.web.ContextFilter + '.implode(', ', array(
<?php foreach ($this->getActionIDs() as $action): ?>
                    '<?php echo $action ?>',
<?php endforeach; ?>
                )),
                'context'       =>  Response::CONTEXT_JSON,
            ),
            'accessControl', // see accessRules() to configure
            'postOnly + '.implode(', ', array( // example of using post only filter
<?php foreach ($this->getActionIDs() as $action): ?>
                '<?php echo $action ?>',
<?php endforeach; ?>
            )),
            'userExists + '.implode(', ', array( // example of using own filters, see filterUserExists() method
<?php foreach ($this->getActionIDs() as $action): ?>
                '<?php echo $action ?>',
<?php endforeach; ?>
            )),
        );
    }

    public function filterUserExists(CFilterChain $filterChain)
    {
        if (!$this->userHelper->contextual) {
            throw new CHttpException(404, 'User not found');
        }
        $filterChain->run();
    }

    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions'       =>  array(
<?php foreach ($this->getActionIDs() as $action): ?>
                    '<?php echo $action ?>',
<?php endforeach; ?>
                ),
                'roles'         =>  array('authenticated'),
            ),
            array(
                'allow',
                'actions'       =>  array(
<?php foreach ($this->getActionIDs() as $action): ?>
                    '<?php echo $action ?>',
<?php endforeach; ?>
                ),
                'roles'         =>  array('admin'),
            ),
            array('deny',
                'users'     =>  array('*'),
            ),
        );
    }
    */


<?php foreach($this->getActionIDs() as $action): ?>
    public function action<?php echo ucfirst($action); ?>()
    {
        $this->render('<?php echo $action; ?>');
    }

<?php endforeach; ?>
}
