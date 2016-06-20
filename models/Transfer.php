<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
/**
 * This is the model class for table "transfer".
 *
 * @property integer $id
 * @property integer $user_from_id
 * @property integer $user_to_id
 * @property integer $amount
 * @property string $send_time
 *
 * @property User $userTo
 * @property User $userFrom
 */
class Transfer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_from_id', 'user_to_id'], 'required'],
            [['user_from_id', 'user_to_id', 'amount'], 'integer'],
            [['send_time'], 'safe'],
            [['user_to_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_to_id' => 'id']],
            [['user_from_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_from_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'send_time',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'Transfer ID',
            'user_from_id' => 'Send From',
            'user_to_id'   => 'Send To',
            'amount'       => 'Amount',
            'send_time'    => 'Sending Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTo()
    {
        return $this->hasOne(User::className(), ['id' => 'user_to_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'user_from_id']);
    }
}
