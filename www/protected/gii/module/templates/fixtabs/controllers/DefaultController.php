<?php echo "<?php\n"; ?>

/**
 * @property <?echo $this->moduleClass ?> $module
 * @method <?echo $this->moduleClass ?> getModule()
 */
class DefaultController extends Controller
{
    // remove filters or uncomment if need
    /*
    public function filters()
    {
        return array(
            array( // filter for ajax actions (responses will returned in json format)
                'application.components.web.ContextFilter + '.implode(', ', array(
                    'ajaxAction1',
                    'ajaxAction2',
                )),
                'context'       =>  Response::CONTEXT_JSON,
            ),
            'accessControl', // see accessRules() to configure
            'postOnly + '.implode(', ', array( // example of using post only filter
                'create',
                'update',
                'delete',
            )),
            'userExists + '.implode(', ', array( // example of using own filters, see filterUserExists() method
                'index',
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
                    'index',
                ),
                'roles'         =>  array('authenticated'),
            ),
            array(
                'allow',
                'actions'       =>  array(
                    'create',
                    'update',
                    'delete',
                ),
                'roles'         =>  array('admin'),
            ),
            array('deny',
                'users'     =>  array('*'),
            ),
        );
    }
    */

    public function actionIndex()
    {
        $this->render('index');
    }
}
