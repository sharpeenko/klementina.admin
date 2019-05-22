<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace app\commands;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;
        
        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...
        
        // Создадим роли админа и обычного пользователя
        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');
        
        // запишем их в БД
        $auth->add($admin);
        $auth->add($user);
        
        // Создаем разрешения. 
        $accessAdminPanel = $auth->createPermission('accessAdminPanel');
        $accessAdminPanel->description = 'Доступ в панель администратора';
        
        $accessUserProfile = $auth->createPermission('accessUserProfile');
        $accessUserProfile->description = 'Тестовая привелегия зарегистрированного пользователя';
        
        // Запишем эти разрешения в БД
        $auth->add($accessAdminPanel);
        $auth->add($accessUserProfile);
        
        // Теперь добавим наследования. Для роли user мы добавим разрешение accessUserProfile,
        // а для админа добавим наследование от роли user и еще добавим собственное разрешение accessAdminPanel
        
        
        $auth->addChild($admin,$accessAdminPanel);

        
        $auth->addChild($admin, $accessUserProfile);
        
        
        $auth->addChild($user, $accessUserProfile);

        
        $auth->assign($admin, 1); 
        
        
        $auth->assign($user, 2);
    }
}




