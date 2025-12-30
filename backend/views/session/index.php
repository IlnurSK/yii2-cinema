<?php

use common\models\Film;
use common\models\Session;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\SessionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title                   = 'Сеансы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="session-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать сеанс', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label'  => 'Постер',
                'format' => 'html',
                'value'  => function ($model) {
                    // Проверяем, есть ли фильм и картинка у него
                    if ($model->film && $url = $model->film->getImageUrl()) {
                        return Html::img($url, ['width' => '50']);
                    }
                    return '<span class="text-muted">Нет фото</span>';
                },
            ],
            [
                'attribute' => 'film_id',
                'label'     => 'Фильм',
                'value'     => 'film.title',
                'filter'    => Select2::widget([
                    'model'         => $searchModel,
                    'attribute'     => 'film_id',
                    'data'          => ArrayHelper::map(Film::find()->all(), 'id', 'title'),
                    'options'       => ['placeholder' => 'Выберите фильм ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'attribute' => 'session_datetime',
                'filter' => DateTimePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'session_datetime',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii',
                        'weekStart' => 1,
                        'todayBtn' => true,
                    ]
                ]),
            ],
            'price',
            [
                'class'      => ActionColumn::class,
                'urlCreator' => function ($action, Session $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
