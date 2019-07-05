<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\domains\User */

$this->title = $model->surname . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Список контактов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот контакт?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'surname',
            'name',
            'patronymic',
            'date_birth',
            [
                'attribute' => 'create_at',
                'format'=>'raw',
                'value'=> function ($model) {
                    return date('d.m.Y H:i', $model->create_at);
                },
            ],
            [
                'attribute' => 'update_at',
                'format'=>'raw',
                'value'=> function ($model) {
                    return date('d.m.Y H:i', $model->update_at);
                },
            ],
        ],
    ]) ?>

</div>
