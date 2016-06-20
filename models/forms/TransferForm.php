<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Transfer form
 */
class TransferForm extends Model
{
    public $name;
    public $amount;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'amount'], 'required'],
            ['name', 'compare',
                'operator'     => '!=',
                'compareValue' => \Yii::$app->user->identity->name,
                'message'      => 'You can`t transfer to yourself!',
            ],
            ['amount', 'integer', 'min' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'   => 'Send To',
            'amount' => 'Amount',
        ];
    }

    public function transfer()
    {
        $user_send_to = User::findByUsername($this->name);
        return \Yii::$app->user->identity
            ->transfer($user_send_to, $this->amount);
    }

}