<?php

use common\models\Session;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\SessionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sessions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="session-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Session', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Постер',
                'format' => 'html',
                'value' => function ($model) {
                    // Проверяем, есть ли фильм и картинка у него
                    if ($model->film && $url = $model->film->getImageUrl()) {
                        return Html::img($url, ['width' => '50']);
                    }
                    return '<span class="text-muted">Нет фото</span>';
                },
            ],
            [
                'attribute' => 'film_id',
                'label' => 'Фильм',
                'value' => 'film.title',
            ],
            'session_datetime',
            'price',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Session $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
