<?php

namespace common\models;

use Yii;

/**
 * This is the model class that provides notarize capabilities.
 */
class NotarizedModel extends \yii\db\ActiveRecord
{
    public function beforeValidate()
    {
        $this->notarize();
        return parent::beforeValidate();
    }

    public function notarize()
    {
        $now = time();
        $userId = Yii::$app->user->identity->id ?? 1;
        $this->updated = $now;
        if (empty($this->created)) {
            $this->created = $now;
        }
        $attr = $this->attributes;
        $canHave = array_key_exists("creator", $attr);
        $doesNotHave = empty($this->creator);
        if ($canHave && $doesNotHave) {
            $this->creator = $userId;
        }
        $canHave = array_key_exists("owner", $attr);
        $doesNotHave = empty($this->owner);
        if ($canHave) {
            $this->owner = $userId;
        }
    }
}
