<?php

namespace common\models;

use common\models\User;
use frontend\helpers\ControllerHelper;
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
        $asAdmin = ($this->canNotarize() && $this->hasNotarizeKey());
        $this->notarize($asAdmin);
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
    public function notarize($asAdmin = false)
    {
        $now = time();
        $creatorId = Yii::$app->user->identity->id ?? 1;
        $ownerId = Yii::$app->user->identity->id ?? 1;
        $this->updated = $now;
        if (empty($this->created)) {
            $this->created = $now;
        }
        $attr = $this->attributes;
        $canHave = array_key_exists("gameId", $attr);
        $doesHave = !empty($this->gameId);
        if ($canHave && $doesHave) {
            $game = Game::findOne($this->gameId);
            if (!empty($game->owner)) {
                $ownerId = $game->owner;
            }
        }
        $canHave = array_key_exists("creator", $attr);
        $doesNotHave = empty($this->creator);
        if ($canHave && $doesNotHave) {
            $this->creator = $creatorId;
        }
        $canHave = array_key_exists("campaignId", $attr);
        $doesNotHave = empty($this->campaignId);
        if ($canHave && $doesNotHave) {
            $this->campaignId = $_GET['campaignId'];
        }
        if ($asAdmin) {
            return;
        }
        $canHave = array_key_exists("owner", $attr);
        $doesNotHave = empty($this->owner);
        if ($canHave && $doesNotHave) {
            $this->owner = $ownerId;
        }
        $canHave = array_key_exists("timezone", $attr);
        $doesNotHave = empty($this->timezone);
        if ($canHave && $doesNotHave) {
            $this->timezone = date_default_timezone_get();
        }
    }

    /**
     * Can Modify
     */
    public function canModify()
    {
        $attr = $this->attributes;
        $userId = Yii::$app->user->identity->id ?? 1;
        if (!empty($_GET['campaignId'])) {
            $rank = ControllerHelper::getPlayerRank($_GET['campaignId']);
            if ($rank == 'isAdmin') {
                return true;
            }
        }
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

    /**
     * Owner
     */
    public function owner()
    {
        $user = User::findOne($this->owner);
        if (!empty($user->username)) {
            return $user->username;
        }
        return $this->owner;
    }

    /**
     * Can Notarize
     */
    public function canNotarize()
    {
        $rank = "";
        $userId = Yii::$app->user->identity->id ?? 1;
        if (!empty($_GET['campaignId'])) {
            $rank = ControllerHelper::getPlayerRank($_GET['campaignId']);
        }
        if ($rank != 'isAdmin') {
            return false;
        }
        if (empty(Yii::$app->params['notarizeKey'])) {
            return false;
        }
        return true;
    }

    /**
     * Has Notarize Key
     */
    public function hasNotarizeKey()
    {
        if (empty(Yii::$app->params['notarizeKey'])) {
            return false;
        }
        $notarizeKey = Yii::$app->params['notarizeKey'];
        if (!empty($_POST['notarizeKey'])) {
            if ($_POST['notarizeKey'] == $notarizeKey) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get Notarize Key
     */
    public function getNotarizeKey()
    {
        if (empty(Yii::$app->params['notarizeKey'])) {
            return "";
        }
        return Yii::$app->params['notarizeKey'];
    }
}
