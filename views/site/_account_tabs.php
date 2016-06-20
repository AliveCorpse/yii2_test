<?php
use yii\bootstrap\Alert;
use yii\bootstrap\Nav;

?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
<?= Nav::widget([
    'options' => ['class' => 'nav-tabs'],
    'items' => [
        [
            'label' => 'Transfers',
            'url' => ['site/transfer'],
        ],
        [
            'label' => 'Infoices',
            'url' => ['site/invoice'],
        ]
    ]
]) ?>
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-' . $key,
                ],
                'body' => $message,
            ]);
        }
        ?>
    </div>
</div>   

<div class="row">
    <div class="col-md-8 col-md-offset-2">
     <?= $content ?>
    </div>
</div>