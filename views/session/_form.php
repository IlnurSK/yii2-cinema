<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Film;
use kartik\datetime\DateTimePicker;

/** @var yii\web\View $this */
/** @var app\models\Session $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="session-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    // Получаем все фильмы и делаем массив с помощью ArrayHelper: [id => title]
    $films = ArrayHelper::map(Film::find()->all(), 'id', 'title');

    // Рендерим список
    echo $form->field($model, 'film_id')->dropDownList($films, ['prompt' => 'Выберите фильм...']);
    ?>

    <?php
    // Подключаем виджет с календарем
    echo $form->field($model, 'session_datetime')->widget(DateTimePicker::class, [
            'options' => ['placeholder' => 'Выберите дату и время сеанса...'],
            'layout' => '{input}{picker}{remove}',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii:00',
                'todayHighLight' => true,
                'minuteStep' => 10,
                'pickerPosition' => 'bottom-left',
        ]
    ]);
    ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
