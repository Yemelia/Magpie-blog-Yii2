<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use \yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $vk_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property integer $isAdmin
 * @property string $photo
 * *
 * @property Comment[] $comments
 */

class User extends ActiveRecord implements IdentityInterface
{

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    public $rememberMe = true;
    public $_user = false;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = ['email', 'password', 'rememberMe'];
        $scenarios[self::SCENARIO_REGISTER] = ['name', 'email', 'password'];

        return $scenarios;
    }

    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required', 'on' => self::SCENARIO_REGISTER],
            [['email', 'password'], 'required', 'on' => self::SCENARIO_LOGIN],
            [['name', 'email', 'password', 'photo'], 'string', 'max' => 255],
        ];
    }

     /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function findByEmail($email)
    {
        return User::find()->where(['email'=>$email])->one();
    }

    public function create()
    {
        return $this->save(false);
    }

    public function saveFromVk($uid, $first_name, $photo)
    {

        $user = User::find()->where(['vk_id' => $uid])->one();

        if ($user)
        {
            return Yii::$app->user->login($user);
        }

        $this->vk_id = $uid;
        $this->name = $first_name;
        $this->photo = $photo;

        $this->create();

        return Yii::$app->user->login($this);

    }

    public function getImage()
    {
        return $this->photo;
    }

    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUserByEmail();

            if (!$user || $this->password != $user->password) {
                $this->addError('password', 'Incorrect email or password.');

                return false;
            }

            return true;
        }
    }

    public function login()
    {
        if ($this->validatePassword()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }

        return false;
    }

    public function getUserByEmail()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
