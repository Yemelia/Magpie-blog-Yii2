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
    public function rules()
    {
        return [
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

    public function validatePassword($password)
    {
        return ($this->password == $password) ? true : false;
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
}
