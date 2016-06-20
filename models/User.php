<?php

namespace app\models;

// use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $name
 * @property integer $balance
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['balance'], 'integer'],
            ['balance', 'default', 'value' => 0],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'name'    => 'Name',
            'balance' => 'Balance',
        ];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds user by name
     *
     * @param string $name
     * @return static|null
     */
    public static function findByUsername($name)
    {
        $user = static::findOne(['name' => $name]);

        if (null === $user) {
            $user = new User(['name' => $name]);
            $user->save();
        }
        return $user;
    }

    public function transfer(User $user_send_to, int $amount)
    {
        $transfer = new Transfer([
            'user_from_id' => $this->id,
            'user_to_id'   => $user_send_to->id,
            'amount'       => $amount,
        ]);

        if ($transfer->save()) {
            $this->updateCounters(['balance' => -$amount]);
            $user_send_to->updateCounters(['balance' => $amount]);

            return true;
        }

        return false;
    }
}
