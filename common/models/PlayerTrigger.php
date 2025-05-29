<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_trigger".
 *
 * @property int $id
 * @property int $campaignId
 * @property int $playerId
 * @property string $name
 * @property int $category
 * @property string $description
 * @property int $owner
 * @property int $creator
 * @property int $created
 * @property int $updated
 */
class PlayerTrigger extends NotarizedModel
{
    public const CATEGORIES = [
        "Yes" => [
            "alert" => "success",
            "icon" => "fa-thumbs-up"
        ],
        "Maybe" => [
            "alert" => "warning",
            "icon" => "fa-hand"
        ],
        "No" => [
            "alert" => "danger",
            "icon" => "fa-thumbs-down"
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player_trigger';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaignId', 'playerId', 'name', 'category', 'description', 'owner', 'creator', 'created', 'updated'], 'required'],
            [['campaignId', 'playerId', 'category', 'owner', 'creator', 'created', 'updated'], 'integer'],
            [['description'], 'string'],
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
            'playerId' => 'Player ID',
            'name' => 'Name',
            'category' => 'Category',
            'description' => 'Description',
            'owner' => 'Owner',
            'creator' => 'Creator',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * Get Category Things
     */
    public function getCategoryThings()
    {
        $campaign = Campaign::findOne($_GET['campaignId']);
        if (!$campaign) {
            return self::CATEGORIES;
        }
        $rules = json_decode($campaign->rules, $arr = true);
        if (empty($rules)) {
            return self::CATEGORIES;
        }
        if (empty($rules["Triggers"])) {
            return self::CATEGORIES;
        }
        return $rules["Triggers"];
    }

    /**
     * Get Category Names
     */
    public function getCategoryNames()
    {
        $names = array();
        $categories = $this->getCategoryThings();
        foreach ($categories as $name => $value) {
            array_push($names, $name);
        }
        return $names;
    }

    /**
     * Get Category Name
     */
    public function getCategoryName()
    {
        $counter = 0;
        $names = $this->getCategoryNames();
        foreach ($names as $name) {
            if ($counter == $this->category) {
                return $name;
            }
            $counter++;
        }
        return $this->category;
    }

    /**
     * Get Catgegory Thing
     */
    public function getCategoryThing($thing = "alert")
    {
        $counter = 0;
        $categories = $this->getCategoryThings();
        foreach ($categories as $name => $data) {
            if ($counter == $this->category) {
                return $data[$thing];
            }
            $counter++;
        }
        return;
    }
}
