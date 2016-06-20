<?php

use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $this->beginContent('@app/views/site/_account_tabs.php');?>
<div class="row">
<?php Modal::begin([
    'header' => '<h3>New Transfer</h3>',
    'id'     => 'modal',
]);
$form = ActiveForm::begin();?>

        <?=$form->field($model, 'name');?>
        <?=$form->field($model, 'amount');?>

        <div class="form-group">
            <?=Html::submitButton('Submit', ['class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#modal']);?>
        </div>
    <?php ActiveForm::end();?>
<?php Modal::end();?>

</div>

<div class="row">
        <h3>Transfers history</h3>
        <?=Html::button('Create new Transfer', [
                'class'       => 'btn btn-primary',
                'data-toggle' => 'modal',
                'data-target' => '#modal',
            ]);?>
            <hr>
        <?=GridView::widget([
                'dataProvider' => $dataProvider,
                'columns'      => [
                    'id',
                [
            'attribute' => 'user_from_id',
            'value'     => function ($model) {
                return $model->userFrom->name;
            },
        ],
        [
            'attribute' => 'user_to_id',
            'value'     => function ($model) {
                return $model->userTo->name;
            },
        ],
        'amount',
        'send_time:datetime',
    ],
]);?>
</div>
<?php $this->endContent();?>