<?php

class AdminUserIdentity extends UserIdentity
{
    /**
     * @return User|null;
     */
    protected function findUserModel()
    {
        $user = parent::findUserModel();

        if (!empty($user) && !Yii::app()->authManager->checkAccess('admin', $user->id, array('user' => $user))) {
            return null;
        }

        return $user;
    }
}
