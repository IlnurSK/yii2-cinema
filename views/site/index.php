<?php
/** @var yii\web\View $this */
/** @var app\models\Session[] $sessions */

$this->title = 'Кинотеатр - Расписание';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Афиша Кинотеатра</h1>
        <p class="lead">Актуальное расписание сеансов</p>
    </div>

    <div class="body-content">
        <div class="row">
            <?php foreach ($sessions as $session): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-img-top-wrapper" style="height: 300px; overflow: hidden; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                            <?php if ($session->film->getImageUrl()): ?>
                                <img src="<?= $session->film->getImageUrl() ?>" class="card-img-top" alt="<?= $session->film->title ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted">Нет постера</span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= $session->film->title ?></h5>

                            <p class="card-text">
                                <strong>Время:</strong> <?= Yii::$app->formatter->asDatetime($session->session_datetime, 'php:d.m.Y H:i') ?>
                            </p>
                            <p class="card-text">
                                <strong>Цена:</strong> <span class="badge bg-success"><?= $session->price ?> ₽</span>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Длительность: <?= $session->film->duration ?> мин.</small>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($sessions)): ?>
                <div class="col-12 text-center">
                    <p>Сеансов пока нет :( Заходите позже!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>