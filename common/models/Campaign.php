<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "campaign".
 *
 * @property int $id
 * @property string $name
 * @property string|null $rules
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class Campaign extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['rules'], 'string'],
            [['owner', 'creator', 'created', 'updated'], 'integer'],
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
            'name' => 'Name',
            'rules' => 'Rules',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Get Name
     * @param integer $campaignId
     */
    public static function getName($campaignId)
    {
        $campaign = Campaign::findOne($campaignId);
        if (empty($campaign->name)) {
            return "";
        }
        return $campaign->name;
    }

    /**
     * Get My Campaigns
     */
    public static function getMyCampaigns()
    {
        $user = User::findOne(Yii::$app->user->identity->id);
        if (empty($user)) {
            return;
        }
        $campaigns = Campaign::find()
            ->where(["deleted" => 0])
            ->all();
        $myCampaigns = array();
        foreach ($campaigns as $campaign) {
            if ($user->id == $campaign->owner) {
                array_push($myCampaigns, $campaign);
                continue;
            }
            if ($user->id == $campaign->creator) {
                array_push($myCampaigns, $campaign);
                continue;
            }
            $player = CampaignPlayer::find()
                ->where(["campaignId" => $campaign->id])
                ->andWhere(["userId" => $user->id])
                ->one();
            if ($player) {
                array_push($myCampaigns, $campaign);
                continue;
            }
        }
        return $myCampaigns;
    }

    /**
     * Show Purchases
     * @param object $campaignRules
     */
    public static function showPurchases($campaignRules)
    {
        if (!empty($campaignRules->Navigation->purchases)) {
            return true;
        }
        $isNotArray = !is_array($campaignRules->Navigation);
        $isNotObject = !is_object($campaignRules->Navigation);
        if ($isNotArray && $isNotObject) {
            return false;
        }
        foreach ($campaignRules->Navigation as $rank) {
            foreach ($rank as $key => $value) {
                if (str_contains($value, "purchase")) {
                    return true;
                }
            }
        }
        return false;
    }
}
