<?php

namespace app\controllers;

use app\models\forms\InvoiceForm;
use app\models\forms\LoginForm;
use app\models\forms\TransferForm;
use app\models\Invoice;
use app\models\Transfer;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'defaultPageSize' => 20,
                'totalCount'      => $query->count(),
            ],
        ]);

        return $this->render('index',
            ['dataProvider' => $dataProvider]
        );
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('account');
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAccount()
    {
        $query = Invoice::find()
            ->where(['and',
                ['user_to_id' => Yii::$app->user->id],
                ['status' => Invoice::STATUS_NEW]])->count();
        if (0 != $query) {
            Yii::$app->session->setFlash('info', 'You have some NEW incoming invoices.');
            return $this->redirect('invoice');
        }

        return $this->redirect('transfer');
    }

    public function actionTransfer()
    {
        $model = new TransferForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->transfer()) {
                    Yii::$app->session->setFlash('success', 'Transfer success.');
                } else {
                    Yii::$app->session->setFlash('danger', 'Something go wrong!!!');
                }

                return $this->redirect(['transfer']);
            }
        }

        $query = Transfer::find()
            ->where(['or',
                ['user_from_id' => Yii::$app->user->id],
                ['user_to_id' => Yii::$app->user->id]]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'defaultOrder' => ['send_time' => SORT_DESC],
                'attributes'   => ['send_time', 'amount', 'user_to_id', 'user_from_id'],
            ],
            'pagination' => [
                'defaultPageSize' => 5,
                'totalCount'      => $query->count(),
            ],
        ]);

        return $this->render('transfer', [
            'model'        => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionInvoice()
    {
        Invoice::updateAll(['status' => Invoice::STATUS_WAIT],
            ['and',
                ['user_to_id' => Yii::$app->user->id],
                ['status' => Invoice::STATUS_NEW],
            ]);

        $model = new InvoiceForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->create()) {
                    Yii::$app->session->setFlash('success', 'Invoice created.');
                } else {
                    Yii::$app->session->setFlash('danger', 'Something go wrong!!!');
                }

                return $this->redirect(['invoice']);
            }
        }

        $query = Invoice::find()
            ->where(['user_to_id' => Yii::$app->user->id]);

        $getingInvoiceDataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes'   => ['created_at', 'updated_at', 'amount', 'user_from_id', 'user_from_id'],
            ],
            'pagination' => [
                'defaultPageSize' => 5,
                'totalCount'      => $query->count(),
            ],
        ]);

        $query = Invoice::find()
            ->where(['user_from_id' => Yii::$app->user->id]);

        $sendingInvoiseDataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes'   => ['created_at', 'updated_at', 'amount', 'user_to_id', 'user_from_id'],
            ],
            'pagination' => [
                'defaultPageSize' => 5,
                'totalCount'      => $query->count(),
            ],
        ]);

        return $this->render('invoice', [
            'model'                      => $model,
            'getingInvoiceDataProvider'  => $getingInvoiceDataProvider,
            'sendingInvoiseDataProvider' => $sendingInvoiseDataProvider,
        ]);
    }
}
