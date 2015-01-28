<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 * 
 * @property User|null $authorized
 * @property integer|null $id
 * @property string|null $name
 */
class UserIdentity extends CUserIdentity
{
    /* @var User|null */
    private $_authorized = null;

    /**
     * @return User|null;
     */
    protected function findUserModel()
    {
        // Customize find user logic if need
        $attribute = strpos($this->username, '@') ? 'email' : 'username';
        $user = User::model()->findByAttributes(array(
            $attribute  =>  $this->username,
        ));
        return $user;
    }

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $this->_authorized = null;

        $user = $this->findUserModel();

        if (empty($user)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!CPasswordHelper::verifyPassword($this->password, $user->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_authorized = $user;
            $this->setState('pass', $user->password);
            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    /**
     * @return User|null
     */
    public function getAuthorized()
    {
        return $this->_authorized;
    }

    /**
     * @return integer|null
     */
    public function getId()
    {
        // Customize id field if need
        return empty($this->_authorized) ? null : $this->_authorized->id;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        // Customize username field if need
        return empty($this->_authorized) ? null : $this->_authorized->username;
    }
}