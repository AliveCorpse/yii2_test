<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property integer $user_from_id
 * @property integer $user_to_id
 * @property integer $amount
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $userTo
 * @property User $userFrom
 */
class Invoice extends \yii\db\ActiveRecord
{

    const STATUS_NEW    = 'New';
    const STATUS_WAIT   = 'Wait';
    const STATUS_REJECT = 'Reject';
    const STATUS_ACCEPT = 'Accept';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_from_id', 'user_to_id'], 'required'],
            [['user_from_id', 'user_to_id', 'amount'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 25],
            ['status', 'default', 'value' => self::STATUS_NEW],
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
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'user_from_id' => 'From User',
            'user_to_id'   => 'To User',
            'amount'       => 'Amount',
            'status'       => 'Status',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
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

    public function hasAccess($user_id)
    {
        return $user_id === $this->user_to_id;
    }

    public function isStatusWait()
    {
        return self::STATUS_WAIT === $this->status;
    }

    public function accept()
    {
        Yii::$app->user->identity->transfer($this->userFrom, $this->amount);

        $this->status = self::STATUS_ACCEPT;
        return $this->save();
    }

    public function reject()
    {
        $this->status = self::STATUS_REJECT;
        return $this->save();
    }
}
