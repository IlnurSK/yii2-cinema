<?php

use common\models\Film;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\FilmSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Films';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="film-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Film', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // Отображение постера
            [
                'label' => 'Постер',
                'format' => 'html',
                'value' => function ($model) {
                    $url = $model->getImageUrl();
                    // Если картинка есть — показываем, иначе текст
                    return $url
                        ? Html::img($url, ['width' => '50'])
                        : '<span class="text-muted">Нет фото</span>';
                },
            ],
            'title',
            'description:ntext',
            'duration',
            [
                'attribute' => 'age_rating',
                'label' => 'Возрастное ограничение',
                'filter' => Film::getAgeRatings(),
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Film $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
