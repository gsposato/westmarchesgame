<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "purchase".
 *
 * @property int $id
 * @property string $name
 * @property int $campaignId
 * @property int $characterId
 * @property int $gameId
 * @property int $currency
 * @property int $price
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class Purchase extends NotarizedModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'campaignId', 'characterId', 'currency', 'price', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'characterId', 'gameId', 'currency', 'price', 'owner', 'creator', 'created', 'updated'], 'integer'],
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
            'campaignId' => 'Campaign ID',
            'characterId' => 'Character',
            'gameId' => 'Game',
            'currency' => 'Currency',
            'price' => 'Price',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Get Currency
     */
    public static function currency()
    {
        $campaign = Campaign::findOne($_GET['campaignId']);
        $campaignRules = json_decode($campaign->rules);
        $gold = ucwords($campaignRules->Currency->gold ?? "gold");
        $bastion = ucwords($campaignRules->Currency->{"bastion points"} ?? "bastion points");
        $credit = ucwords($campaignRules->Currency->credit ?? "credit");
        $list =  [
            0 => $credit,
            1 => $gold,
            2 => $bastion,
        ];
        $state = count($list);
        $currencies = Currency::find()->where(["campaignId" => $_GET['campaignId']])->all();
        foreach ($currencies as $currency) {
            if ($currency->id > 0 && $currency->id < 3) {
                continue;
            }
            $list[$currency->id] = ucwords($currency->name);
        }
        return $list;
    }

    /**
     * Get Currency Color
     */
    public static function currencyColor()
    {
        $campaign = Campaign::findOne($_GET['campaignId']);
        $campaignRules = json_decode($campaign->rules);
        $gold = $campaignRules->CurrencyColor->gold ?? "#df8607";
        $bastion = $campaignRules->CurrencyColor->{"bastion points"} ?? "#000";
        $credit = $campaignRules->CurrencyColor->credit ?? "#000";
        $list =  [
            0 => $credit,
            1 => $gold,
            2 => $bastion,
        ];
        $state = count($list);
        $currencies = Currency::find()->where(["campaignId" => $_GET['campaignId']])->all();
        foreach ($currencies as $currency) {
            if ($currency->id > 0 && $currency->id < 3) {
                continue;
            }
            $list[$currency->id] = $currency->color;
        }
        return $list;
    }
}
