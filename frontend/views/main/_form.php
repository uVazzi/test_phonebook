<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\domains\User */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (Yii::$app->session->hasFlash('error')) : ?>
    <?= '<div class="alert alert-danger">' . Yii::$app->session->getFlash('error') . '</div>'; ?>
<?php endif; ?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_birth')->widget(DatePicker::className(), [
        'attribute' => 'date_birth',
        'dateFormat' => 'dd.MM.yyyy',
        'clientOptions' => [
            'yearRange' => '1930:2019',
            'changeMonth' => 'true',
            'changeYear' => 'true',
        ],
        'options' => ['class' => 'form-control']]) ?>

    <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Добавить номер', [
        'class' => 'btn btn-default',
        'id' => 'add-phone'
    ]) ?>
    <br><br>

    <div class="row" id="phone_list">
        <?php // Выводит массив (отображая в шаблоне)
        foreach ($model->phones as $key => $model) : ?>
            <?= $this->render('template/_phone_item', [
                'form' => $form,
                'model' => $model,
                'key' => $key,
            ]) ?>
        <?php endforeach; ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php // Скрытый шаблон ?>
<div style="display:none;" id="template_phone_field">
    <?= $this->render('template/_phone_item', [
        'form' => $form,
        'model' => new \common\models\domains\Phone(),
        'key' => '???',
    ]) ?>
</div>



<?php
$this->registerJs('
// Действие на добавить номер
$("#add-phone").click(function (){
    // Формирует случайную строку для идентифиции полей
    var id = "new_"+ randomString();
    var itemView = $("#template_phone_field").children().clone();
    // Заменяет ??? на полученный идентификатор
    itemView.find("input").each(function(i, el) {
        $(el).attr("name", $(el).attr("name").replace("???", id));
        $(el).attr("id", $(el).attr("id").replace("???", id));
    });
    itemView.find("select").each(function(i, el) {
        $(el).attr("name", $(el).attr("name").replace("???", id));
        $(el).attr("id", $(el).attr("id").replace("???", id));
    });
    itemView.find("div").each(function(i, el) {
    $(el).attr("class", $(el).attr("class").replace("???", id));
     });
     itemView.find("button").each(function(i, el) {
    $(el).attr("onclick", $(el).attr("onclick").replace("???", id));
     });
     // Вставляет полученный элемент
    $("#phone_list").append(itemView);  
});

// Генерирует рандомную строку
function randomString()
{
    var randString = "";
    var allowed = "abcdefghijklmnopqrstuvwxyz0123456789";
    for( var i=0; i < 5; i++ )
    {
        var n = 4 - 0.5 + Math.random() * (12 - 4 + 1);
        n = Math.round(n);
        randString += randomCycle(allowed, n) + "-";
    }
    randString = randString.substring(0, randString.length - 1);
    return randString;
}
function randomCycle(allowed, n)
{
    var rand = "";
    for( var i=0; i < n; i++ )
    {
        rand += allowed.charAt(Math.floor(Math.random() * allowed.length));
    }
    return rand;  
}
'); ?>
<script>
    // Удаляет поле
    function remove_phone(field_id) {
        var divclass = '.field-phone-' + field_id + '-id';
        $(divclass).parent(".col-md-12").remove();
    }
</script>