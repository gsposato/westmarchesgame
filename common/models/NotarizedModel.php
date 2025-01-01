<?php

namespace common\models;

use common\models\User;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class that provides notarize capabilities.
 */
class NotarizedModel extends \yii\db\ActiveRecord
{
    /**
     * Before Validate
     */
    public function beforeValidate()
    {
        $this->notarize();
        return parent::beforeValidate();
    }

    /**
     * Before Save
     */
    public function beforeSave($insert)
    {
        if ($this->canModify()) {
            return parent::beforeSave($insert);
        }
    }

    /**
     * Notarize
     */
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
        $canHave = array_key_exists("campaignId", $attr);
        $doesNotHave = empty($this->campaignId);
        if ($canHave && $doesNotHave) {
            $this->campaignId = $_GET['campaignId'];
        }
    }

    /**
     * Can Modify
     */
    public function canModify()
    {
        $attr = $this->attributes;
        $userId = Yii::$app->user->identity->id ?? 1;
        $canHave["creator"] = array_key_exists("creator", $attr);
        $canHave["owner"] = array_key_exists("owner", $attr);
        $doesHave["creator"] = !empty($this->creator);
        $doesHave["owner"] = !empty($this->owner);
        foreach ($canHave as $key => $bool) {
            if (!$bool) {
                continue;
            }
            if ($this->{$key} == $userId) {
                return true;
            }
        }
        foreach ($doesHave as $key => $bool) {
            if ($bool) {
                return false;
            }
        }
        return true;
    }

    /**
     * Select
     */
    public function select()
    {
        $campaignId = $_GET['campaignId'];
        $records = $this::find()
            ->where(["campaignId" => $campaignId])
            ->all();
        return ArrayHelper::map($records, 'id', 'name');
    }

    /**
     * View
     */
    public function view()
    {
        $attributes = [
            [
                'attribute' => 'owner',
                'value' => function ($model) {
                    $user = User::findOne($model->owner);
                    if (!empty($user->username)) {
                        return $user->username;
                    }
                    return $model->owner;
                },
            ],
            [
                'attribute' => 'creator',
                'value' => function ($model) {
                    $user = User::findOne($model->creator);
                    if (!empty($user->username)) {
                        return $user->username;
                    }
                    return $model->creator;
                },
            ],
            'created:datetime',
            'updated:datetime',
        ];
        return $attributes;
    }
}
