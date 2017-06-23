<?php

namespace app\controllers;

use app\models\Article;
use app\models\Category;
use app\models\Tag;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $data = Article::getAll(1);
        $popularArticles = Article::getPopular();
        $recentArticles = Article::getRecent();
        $categories = Category::getAll();

        return $this->render('index',[
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'populars' => $popularArticles,
            'recent' => $recentArticles,
            'categories' => $categories
        ]);
    }

    public function actionView($id)
    {
        $article = Article::findOne($id);
        $tags = $article->tags;
        $popularArticles = Article::getPopular();
        $recentArticles = Article::getRecent();
        $categories = Category::getAll();

        return $this->render('single',[
            'article' => $article,
            'tags' => $tags,
            'populars' => $popularArticles,
            'recent' => $recentArticles,
            'categories' => $categories
        ]);
    }

    public function actionCategory($id)
    {
        $data = Article::getArticlesByCategory($id);
        $popularArticles = Article::getPopular();
        $recentArticles = Article::getRecent();
        $categories = Category::getAll();

        return $this->render('category',[
            'articles' => $data['articles'],
            'pagination' => $data['pagination'],
            'populars' => $popularArticles,
            'recent' => $recentArticles,
            'categories' => $categories
            ]);
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
