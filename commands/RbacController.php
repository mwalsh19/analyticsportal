<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller {

    public function actionInit() {
        $auth = \Yii::$app->authManager;

        /**
         * ROLES
         */
        // add "Viewer" role
        $viewerRole = $auth->createRole('viewer');
        $auth->add($viewerRole);

        // add "Editor" role
        $editorRole = $auth->createRole('editor');
        $auth->add($editorRole);

        // add "Admin Level 1" role
        $adminLevel1Role = $auth->createRole('adminLevel1');
        $auth->add($adminLevel1Role);

        // add "Super Admin" role
        $superAdminRole = $auth->createRole('superAdmin');
        $auth->add($superAdminRole);

        /**
         * PERMISSIONS
         */
        // add "view" permission
        $viewPermission = $auth->createPermission('view');
        $viewPermission->description = 'View Permission';
        $auth->add($viewPermission);

        // add "edit" permission
        $editPermission = $auth->createPermission('edit');
        $editPermission->description = 'Edit Permission';
        $auth->add($editPermission);

        // add "tools" permission
        $toolsPermission = $auth->createPermission('tools');
        $toolsPermission->description = 'Tools Permission';
        $auth->add($toolsPermission);


        /**
         * ASSING PERMISSIONS
         */
        //assign "view permision" to "viewer" role
        $auth->addChild($viewerRole, $viewPermission);

        //assign "edit permision" to "editor" role
        //as well as the permissions of the "viewer" role
        $auth->addChild($editorRole, $editPermission);
        $auth->addChild($editorRole, $viewerRole);

        //assign "tools permision" to "Admin Level 1" role
        //as well as the permissions of the "editor" role
        $auth->addChild($adminLevel1Role, $toolsPermission);
        $auth->addChild($adminLevel1Role, $editorRole);

        //assign "all permisions to "Super Admin" role
        $auth->addChild($superAdminRole, $adminLevel1Role);
    }

    public function actionTest() {
        echo "this is a test \n\r";
    }

}
