<?php
return array (
  'guest' => 
  array (
    'type' => 2,
    'description' => 'Not authorized users',
    'bizRule' => '
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
                        $user = User::model()->findByPk($userId);
                        break;
                }
                $params[\'user\'] = $user;
            
            return empty($user);
        ',
    'data' => NULL,
  ),
  'authenticated' => 
  array (
    'type' => 2,
    'description' => 'All authenticated users',
    'bizRule' => '
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
                        $user = User::model()->findByPk($userId);
                        break;
                }
                $params[\'user\'] = $user;
            
            return !empty($user);
        ',
    'data' => NULL,
  ),
  'admin' => 
  array (
    'type' => 2,
    'description' => 'Backend administrators',
    'bizRule' => '
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
                        $user = User::model()->findByPk($userId);
                        break;
                }
                $params[\'user\'] = $user;
            
                return !empty($user) && (bool)$user->is_admin;
            ',
    'data' => NULL,
    'children' => 
    array (
      0 => 'authenticated',
    ),
  ),
);
