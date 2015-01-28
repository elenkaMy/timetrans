<?php

/**
 * @property string $initUserVarBizRule
 */
class UserCommand extends AbstractCommand
{
    public $userModel = 'User';
    public $usernameField = 'username';
    public $emailField = 'email';
    public $passwordField = 'password';
    public $isAdminField = 'is_admin'; // string or false

    private $_initUserVarBizRule = null;

    /**
     * @return string
     */
    public function getInitUserVarBizRule()
    {
        if (is_null($this->_initUserVarBizRule)) {
            $this->_initUserVarBizRule = '
                $params[\'userId\'] = $userId = isset($params[\'userId\']) ? $params[\'userId\'] : null;
                switch (true) {
                    case !empty($params[\'user\']):
                        $user = $params[\'user\'];
                        break;
                    case empty($userId):
                        $user = null;
                        break;
                    case (Yii::app()->user->getAuthorized() ? Yii::app()->user->getAuthorized()->getPrimaryKey() : null) == $userId:
                        $user = Yii::app()->user->getAuthorized();
                        break;
                    case ((Yii::app()->hasComponent(\'userHelper\') && Yii::app()->userHelper->getContextual())
                            ? Yii::app()->userHelper->getContextual()->getPrimaryKey()
                            : null
                    ) == $userId:
                        $user = Yii::app()->userHelper->getContextual();
                        break;
                    default:
                        $user = '.$this->userModel.'::model()->findByPk($userId);
                        break;
                }
                $params[\'user\'] = $user;
            ';
        }
        return $this->_initUserVarBizRule;
    }

    /**
     * @param string $rule
     * @return UserCommand
     */
    public function setInitUserVarBizRule($rule)
    {
        $this->_initUserVarBizRule = $rule;
        return $this;
    }

    public function beforeAction($action,$params)
    {
        Yii::import('application.components.db.ActiveRecord');
        Yii::import('application.components.user.*');
        Yii::import('application.models.*');

        return parent::beforeAction($action, $params);
    }

    public function actionCreate($name, $email, $password = null, $is_admin = false)
    {
        $user = new $this->userModel;
        $user->{$this->usernameField} = $name;
        $user->{$this->emailField} = $email;

        if (empty($password)) {
            $password = Yii::app()->securityManager->generatePassword(8, 2, 2);
        }
        $user->{$this->passwordField} = CPasswordHelper::hashPassword($password);
        if (!empty($this->isAdminField)) {
            $user->{$this->isAdminField} = (bool) $is_admin;
        }

        if ($user->save()) {
            echo "User $name was succesfully created." . PHP_EOL . "Password: $password" . PHP_EOL;
        } else {
            echo "Error: user was not created." . PHP_EOL . print_r($user->getErrors(), true) . PHP_EOL;
        }
    }

    public function actionChangePassword($newPassword, $nameOrEmail)
    {
        $attribute = strpos($nameOrEmail, '@') ? $this->emailField : $this->usernameField;
        $userModel = call_user_func(array($this->userModel, 'model'));
        $user = $userModel->findByAttributes(array(
            $attribute  =>  $nameOrEmail,
        ));

        if (empty($user)) {
            echo "User with $attribute $nameOrEmail not founded" . PHP_EOL;
        } else {
            /* @var $user User */
            $user->{$this->passwordField} = CPasswordHelper::hashPassword($newPassword);
            $user->save();
            echo "Password was succesfully changed.".PHP_EOL.
                "New password: '$newPassword'".PHP_EOL;
        }
    }

    public function actionInitRoles()
    {
        $auth = Yii::app()->authManager;
        $auth->clearAll();

        echo "Init guest role".PHP_EOL;
        $bizRule = $this->initUserVarBizRule.'
            return empty($user);
        ';
        $auth->createRole('guest', 'Not authorized users', $bizRule);

        echo "Init authenticated role".PHP_EOL;
        $bizRule = $this->initUserVarBizRule.'
            return !empty($user);
        ';
        $auth->createRole('authenticated', 'All authenticated users', $bizRule);

        // Example rule for admin role
        if (!empty($this->isAdminField)) {
            echo "Init admin role".PHP_EOL;
            $bizRule = $this->initUserVarBizRule.'
                return !empty($user) && (bool)$user->'.$this->isAdminField.';
            ';
            $role = $auth->createRole('admin', 'Backend administrators', $bizRule);
            $role->addChild('authenticated');
        }

        $auth->save();

        echo "Done".PHP_EOL;
    }
}