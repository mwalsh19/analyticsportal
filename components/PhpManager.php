<?php

namespace app\components;

use Yii;

class PhpManager extends \yii\rbac\PhpManager {

    public function init() {
        if ($this->authFile === NULL)
            $this->authFile = Yii::getAlias('@app/data/rbac') . '.php'; // HERE GOES YOUR RBAC TREE FILE

        parent::init();

        if (!Yii::$app->user->isGuest) {
            $this->assign(Yii::$app->user->identity->id, Yii::$app->user->identity->role); // we suppose that user's role is stored in identity
        }
    }

}
