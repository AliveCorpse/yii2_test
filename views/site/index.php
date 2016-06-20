<?php

use yii\grid\GridView;
$this->title = 'All Users';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>All Users</h1>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                    <?=GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            'name',
                            'balance',
                        ],
                    ])
                    ?>
            </div>
        </div>

    </div>
</div>
