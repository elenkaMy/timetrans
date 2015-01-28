<?php

/**
 * UserHelper class
 * You can use as:
 *   Yii::app()->userHelper->authorized (authorized User or null)
 *   Yii::app()->userHelper->contextual (User by user_id from url params or null)
 *   Yii::app()->userHelper->current (User by user_id from url params or authorized if url param user_id not exists or null)
 * 
 * @property User|null $authorized
 * @property User|null $contextual
 * @property User|null $current
 */
class UserHelper extends CApplicationComponent
{
    public $userModel = 'User';

    protected $_authorized = false;
    protected $_contextual = false;
    protected $_current = false;

    /**
     * Authorized User object
     * @return User|null
     */
    public function getAuthorized()
    {
        if ($this->_authorized === false) {
            $this->_authorized = Yii::app()->user->authorized;
            $this->_authorized = empty($this->_authorized) ? null : $this->_authorized;
        }
        return $this->_authorized;
    }

    /**
     * User by user_id from url params (route url params or $_GET params)
     * @return User|null
     */
    public function getContextual()
    {
        if ($this->_contextual === false) {
            $userId = Yii::app()->request->getQuery('user_id', false);
            if (empty($userId) || !is_scalar($userId)) {
                $this->_contextual = null;
            } else {
                /* @var $userModel ActiveRecord */
                $userModel = call_user_func(array($this->userModel, 'model'));
                $user = $userModel->findByPk($userId);
                $this->_contextual = empty($user) ? null : $user;
            }
        }
        return $this->_contextual;
    }

    /**
     * If exists user_id in url params, than contextual User
     * else authorized (if exists) or null
     * return User|null
     */
    public function getCurrent()
    {
        if (!is_null($this->getContextual())) {
            return $this->getContextual();
        }
        return $this->getAuthorized();
    }
}
