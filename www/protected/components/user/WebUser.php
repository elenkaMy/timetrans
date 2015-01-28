<?php

/**
 * @property User|null $authorized
 */
class WebUser extends CWebUser
{
    public $userModel = 'User';
    public $passwordField = 'password';

    /* @var $_authorized User|null */
    private $_authorized = null;

    /**
     * Initializes the application component.
     * This method overrides the parent implementation by starting session,
     * performing cookie-based authentication if enabled, and updating the flash variables.
     */
    public function init()
    {
        parent::init();

        if (!$this->isGuest) {
            /* @var $user User */
            $userModel = call_user_func(array($this->userModel, 'model'));
            $user = $userModel->findByPk($this->getId());
            if (empty($user) || !CPasswordHelper::same($this->getState('pass'), $user->{$this->passwordField})) {
                $this->logout();
            } else {
                $this->_authorized = $user;
            }
        }
    }

    public function login($identity, $duration = 0)
    {
        $result = parent::login($identity, $duration);
        if ($result) {
            $this->_authorized = $identity->authorized;
        }
        return $result;
    }

    /**
     * @return User|null
     */
    public function getAuthorized()
    {
        if ($this->isGuest) {
            return null;
        }
        return $this->_authorized;
    }
}
