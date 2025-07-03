<?php

namespace common\models;

use Yii;
use frontend\helpers\ControllerHelper;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property int $campaignId
 * @property string $name
 * @property int $status
 * @property string $note
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class Ticket extends NotarizedModel
{
    public const STATUS_NEW = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_CLOSED = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'name', 'status', 'note', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'status', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['note'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campaignId' => 'Campaign ID',
            'name' => 'Name',
            'status' => 'Status',
            'note' => 'Note',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    public static function status()
    {
        return [
            self::STATUS_NEW => "New",
            self::STATUS_ACTIVE => "Active",
            self::STATUS_CLOSED => "Closed"
        ];
    }

    public static function style()
    {
        return [
            self::STATUS_NEW => "success",
            self::STATUS_ACTIVE => "info",
            self::STATUS_CLOSED => "danger"
        ];
    }

    /**
     * Can Modify
     */
    public function canModify()
    {
        $campaignId = $_GET['campaignId'] ?? 1;
        $isSupportRole = ControllerHelper::isSupportRole($campaignId);
        if ($isSupportRole) {
            return true;
        }
        return parent::canModify();
    }

    /**
     * Can Notarize
     */
    public function canNotarize()
    {
        $campaignId = $_GET['campaignId'] ?? 1;
        $isSupportRole = ControllerHelper::isSupportRole($campaignId);
        if ($isSupportRole) {
            return true;
        }
        return parent::canNotarize();
    }
}
