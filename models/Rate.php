<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $article_id
 * @property integer $rate
 *
 */
class Rate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate'], 'integer'],
        ];
    }

    public function create(){
        $this->rate = Yii::$app->request->post('rate');
        $this->user_id = Yii::$app->user->getId();
        $this->article_id = Yii::$app->request->post('article_id');

        $this->save();
    }

    public function getCount(){
        return Rate::find()->where(['article_id' => $this->article_id])->count();
    }
}
