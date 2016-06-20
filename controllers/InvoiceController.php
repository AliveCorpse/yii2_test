<?php

namespace app\controllers;

use app\models\Invoice;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class InvoiceController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['@'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'accept' => ['post'],
                    'reject' => ['post'],
                ],
            ],
        ];
    }

    public function actionAccept($id)
    {
        $model = Invoice::findOne($id);
        if ($model->hasAccess(Yii::$app->user->id) && $model->isStatusWait()) {
            if ($model->accept()) {
                Yii::$app->session->setFlash('success', 'Invoice accepted.');
            } else {
                Yii::$app->session->setFlash('danger', 'Something go wrong!!!');
            }
        }

        return $this->redirect(['site/invoice']);
    }

    public function actionReject($id)
    {
        $model = Invoice::findOne($id);
        if ($model->hasAccess(Yii::$app->user->id) && $model->isStatusWait()) {
            if ($model->reject()) {
                Yii::$app->session->setFlash('warning', 'Invoice rejected.');
            } else {
                Yii::$app->session->setFlash('danger', 'Something go wrong!!!');
            }
        }
        return $this->redirect(['site/invoice']);
    }

}
