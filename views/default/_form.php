<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use xti\settings\Module;
use dosamigos\tinymce\TinyMceAsset;
use \xti\settings\models\Setting;

TinyMceAsset::register($this);
/**
 * @var yii\web\View $this
 * @var xti\settings\models\Setting $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="setting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'section')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'active')->checkbox(['value' => 1]) ?>

    <?=
    $form->field($model, 'type')->dropDownList(
        $model->getTypes()
    )->hint(Module::t('settings', 'Change at your own risk')) ?>

    <div class="form-group">
        <?=
        Html::submitButton(
            $model->isNewRecord ? Module::t('settings', 'Create') :
                Module::t('settings', 'Update'),
            [
                'class' => $model->isNewRecord ?
                    'btn btn-success' : 'btn btn-primary'
            ]
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?
$type_field_id = Html::getInputId($model, 'type');
$value_field_id = Html::getInputId($model, 'value');
$js = <<<JS
    var tinymce_options = {
        "plugins": "code",
        "directionality": "rtl",
        "selector": "#$value_field_id"
    };
    if ($('#$type_field_id').val() === 'wysiwyg') {
        tinymce.init(tinymce_options);
    }
    $('#$type_field_id').on('change', function() {
      if( this.value === 'wysiwyg' ){
        tinymce.init(tinymce_options);
      } else {
        tinymce.execCommand('mceRemoveEditor', true, '$value_field_id');
      }
    })
JS;

$this->registerJs($js);

?>