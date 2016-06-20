<?php

use yii\bootstrap\ActiveForm;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

$this->title = 'Счета к оплате';
?>

<?php $this->beginContent('@app/views/site/_account_tabs.php');?>
<div class="row">
<?php Modal::begin([
    'header' => '<h3>Send Invoice</h3>',
    'id'     => 'modal',
]);
$form = ActiveForm::begin();?>

        <?=$form->field($model, 'name');?>
        <?=$form->field($model, 'amount');?>

        <div class="form-group">
            <?=Html::submitButton('Submit', [
                'class' => 'btn btn-primary',
                'data-toggle' => 'modal',
                'data-target' => '#modal'
                ]);?>
        </div>
    <?php ActiveForm::end();?>
<?php Modal::end();?>

</div>

<div class="row">   
        <h3>Create Invoice</h3>
        <?=Html::button('Send new Invoice', [
                'class'       => 'btn btn-primary',
                'data-toggle' => 'modal',
                'data-target' => '#modal',
            ]);?>
</div>
<hr>

<div class="row">

        <h3>Incoming invoices</h3>
        <?php Pjax::begin();?>
        <?=GridView::widget([
    'id'           => 'invoices-in',
    'dataProvider' => $getingInvoiceDataProvider,
    'columns'      => [
        'id',
        'user_from_id',
        'amount',
        'created_at',
        'updated_at',
        [
            'attribute' => 'status',
            'value'     => function ($model) {
                return $model->status;
            },
        ],
        [
            'header'         => 'Actions',
            'class'          => ActionColumn::className(),
            'template'       => '{accept} {reject}',
            'controller'     => 'invoice',
            'buttons'        => [
                'accept' => function ($url) {
                    return Html::a('Accept', $url, ['class' => 'btn btn-primary btn-xs', 'data-method' => 'post']);
                },
                'reject' => function ($url) {
                    return Html::a('Reject', $url, ['class' => 'btn btn-danger btn-xs', 'data-method' => 'post']);
                },
            ],
            'visibleButtons' => [
                'accept' => function ($model) {
                    return $model->isStatusWait();
                },
                'reject' => function ($model) {
                    return $model->isStatusWait();
                },
            ],
        ],
    ],
]);?>
        <?php Pjax::end();?>

</div>
<div class="row">
        <h3>Sending invoices</h3>
        <?php Pjax::begin();?>
        <?=GridView::widget([
    'id'           => 'invoices-out',
    'dataProvider' => $sendingInvoiseDataProvider,
    'columns'      => [
        'created_at:datetime',
        [
            'attribute' => 'user_to_id',
            'value'     => function ($model) {
                return $model->userTo->name;
            },
        ],
        'amount',
        [
            'attribute' => 'status',
            'value'     => function ($model) {
                return $model->status;
            },
        ],
    ],
]);?>
        <?php Pjax::end();?>
</div>

<?php $this->endContent();?>
