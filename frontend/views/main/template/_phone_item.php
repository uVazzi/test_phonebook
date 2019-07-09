<?php

use common\models\enums\NumberType;
use yii\helpers\Html;

/* @var $model  \common\models\domains\Phone */
/* @var $key string */
/* @var $form \yii\widgets\ActiveForm */
?>

<div class="col-md-12">
    <div class="col-md-2">
        <?= $form->field($model, "[$key]type")->dropDownList(NumberType::listData())->label(false) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, "[$key]number")->textInput(['maxlength' => 20])->label(false);
        ?>
    </div>
    <div class="col-md-2">
        <?= Html::button('<span class="glyphicon glyphicon-remove"></span>', [
            'class' => 'btn btn-danger',
            'onclick' => "remove_phone('$key')",
        ]) ?>
    </div>
    <?= $form->field($model, "[$key]id")->hiddenInput()->label(false); ?>
</div>