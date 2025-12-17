<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Панель управления';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Панель администратора</h1>
        <p class="lead">Система управления расписанием кинотеатра</p>

        <hr class="my-4">

        <p>Выберите раздел для работы:</p>

        <div class="row justify-content-center">
            <div class="col-md-3">
                <a class="btn btn-lg btn-primary w-100" href="<?= Url::to(['/film/index']) ?>">
                    Управление фильмами
                </a>
            </div>
            <div class="col-md-3">
                <a class="btn btn-lg btn-success w-100" href="<?= Url::to(['/session/index']) ?>">
                    Управление сеансами
                </a>
            </div>
        </div>
    </div>
</div>