<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "article_views".
 *
 * @property integer $id
 * @property integer $article_id
 * @property string $user_ip
 *
 */
class ArticleViews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_views';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id'], 'integer'],
            [['user_ip'], 'string'],
        ];
    }

    /**
     * @param $user_ip
     * @param $article_id
     * @return \yii\db\ActiveQuery
     */

    public static function getUser($user_ip, $article_id)
    {
        return ArticleViews::find()->where(['user_ip' => $user_ip, 'article_id' => $article_id])->all();
    }

    public static function getUserIp()
    {
        return Yii::$app->getRequest()->getUserIP();
    }

    public function SaveNewUserIp()
    {
        return $this->save(false);
    }
}
