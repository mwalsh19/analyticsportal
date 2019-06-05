<?php

namespace app\components;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class CustomController extends Controller {

    public function behaviors() {
        $actions = [
            'index',
            'create',
            'update',
            'delete',
            'detail',
            'share',
            'sort-filter',
            'grouped-by-publisher',
            'grouped-by-code-type'
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => $actions,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function ($rule, $action) {

                $action = \Yii::$app->controller->action->id;
                $controller = \Yii::$app->controller->id;
                $route = "$controller/$action";

                if (\Yii::$app->user->can($route)) {
                    return true;
                }
            }
                ],
            ]
        ];

        return $behaviors;
    }

}
