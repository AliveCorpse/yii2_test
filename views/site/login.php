<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title                   = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?=Html::encode($this->title);?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="user-login">

    <?php $form = ActiveForm::begin([
    'id'          => 'login-form',
    'options'     => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template'     => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]);?>

        <?=$form->field($model, 'name');?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?=Html::submitButton('Submit', ['class' => 'btn btn-primary']);?>
            </div>
        </div>
    <?php ActiveForm::end();?>

</div>
    <div class="col-lg-offset-1" style="color:#999;">
        You may login with <strong>any</strong> <em>Name</em>.<br>
        If it`s not exist, it`ll be registered automaticly.
    </div>
</div>
