<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $name;
    private $_user;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }
    /**
     * Logs in a user using the provided name
     * 
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        } else {
            return false;
        }
    }
    /**
     * Finds user by [[name]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if (null === $this->_user) {
            $this->_user = User::findByUsername($this->name);
        }
        return $this->_user;
    }
}