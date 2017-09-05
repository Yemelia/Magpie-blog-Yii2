<?php

namespace app\controllers;

use app\models\Article;
use app\models\ArticleViews;
use app\models\Category;
use app\models\Comment;
use app\models\CommentFrom;
use app\models\Rate;
use app\models\Tag;
use Aws\Common\Command\JsonCommand;
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
        $data = Article::getAll(3);
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
        $current_rate = $article->article_rate;
        $popularArticles = Article::getPopular();
        $recentArticles = Article::getRecent();
        $categories = Category::getAll();
        $comments = $article->getArticleComments();
        $commentForm = new CommentFrom();

        $user_rate = null;
        if(!Yii::$app->user->isGuest){
            $rate_model = Rate::find()->where(['user_id' => Yii::$app->user->getId(), 'article_id' => $id])->one();
            $user_rate = !$rate_model ? 0 : $rate_model->rate;
        }

        if (!ArticleViews::getUser(ArticleViews::getUserIp(), $article->id))
        {
            $model = new ArticleViews();
            $model->user_ip = ArticleViews::getUserIp();
            $model->article_id = $article->id;
            if($model->save())
            {
                $article->ViedCounter();
            }
       }

        return $this->render('single',[
            'article' => $article,
            'tags' => $tags,
            'populars' => $popularArticles,
            'recent' => $recentArticles,
            'categories' => $categories,
            'comments' => $comments,
            'commentForm'=>$commentForm,
            'user_rate' => $user_rate,
            'current_rate' => $current_rate
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

    public function actionComment($id)
    {
        $model = new CommentFrom();

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if($model->saveComment($id))
            {
                Yii::$app->getSession()->setFlash('comment', 'Your comment will be added soon!');
                return $this->redirect(['site/view', 'id' => $id]);
            }
        }
    }


}
