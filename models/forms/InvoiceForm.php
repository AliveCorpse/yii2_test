<?php

namespace app\models\forms;

use app\models\Invoice;
use app\models\User;
use Yii;
use yii\base\Model;

class InvoiceForm extends Model
{

    public $name;
    public $amount;

    public function rules()
    {
        return [
            [['name', 'amount'], 'required'],
            ['name', 'compare',
                'operator'     => '!=',
                'compareValue' => \Yii::$app->user->identity->name,
                'message'      => 'You can`t invoice to yourself!',
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
            'name'   => 'Invoice To',
            'amount' => 'Amount',
        ];
    }

    public function create()
    {
        $user_send_to = User::findByUsername($this->name);

        $invoice = new Invoice([
            'user_from_id' => Yii::$app->user->id,
            'user_to_id'   => $user_send_to->id,
            'amount'       => $this->amount,
        ]);

        return $invoice->save();
    }
}
