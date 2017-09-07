<?php

namespace app\controllers;

use app\models\Article;
use app\models\Rate;
use Yii;
use yii\web\Controller;

class AjaxController extends Controller
{
    public function actionAddRate(){
        if(Yii::$app->user->isGuest){
            echo json_encode(['rate' => 'guest']);
        }else{
            if(Yii::$app->request->isAjax){
                if(!Rate::find()->where(['user_id' => Yii::$app->user->getId(), 'article_id' => Yii::$app->request->post('article_id')])->one()){
                    $model = new Rate();
                    $model->create();
                    $number_of_rate = $model->getCount();
                    $rate = Article::RateCalculation($model, $number_of_rate);
                    echo json_encode(['rate' => $rate]);
                }
            }
        }

    }
}
