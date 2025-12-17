<?php

use common\models\Film;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/** @var yii\web\View $this */
/** @var common\models\Session $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="session-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'film_id')->dropDownList(
        ArrayHelper::map(Film::find()->all(), 'id', 'title'),
        ['prompt' => 'Выберите фильм...']
    ) ?>

    <?= $form->field($model, 'session_datetime')->widget(DateTimePicker::class, [
        'options' => ['placeholder' => 'Выберите время сеанса...'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii'
        ]
    ]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
