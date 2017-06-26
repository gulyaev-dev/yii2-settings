<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

use yii\helpers\Html;
use yii\grid\GridView;
use xti\settings\Module;
use xti\settings\models\Setting;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;
use backend\assets\EditableAsset;

/**
 * @var yii\web\View $this
 * @var xti\settings\models\SettingSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */
EditableAsset::register($this);
$this->title = Module::t('settings', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=
        Html::a(
            Module::t(
                'settings',
                'Create {modelClass}',
                [
                    'modelClass' => Module::t('settings', 'Setting'),
                ]
            ),
            ['create'],
            ['class' => 'btn btn-success']
        ) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                //'type',
                [
                    'attribute' => 'section',
                    'filter' => ArrayHelper::map(
                        Setting::find()->select('section')->distinct()->where(['<>', 'section', ''])->all(),
                        'section',
                        'section'
                    ),
                ],
                'key',
                [
                    'attribute' => 'value',
                    'value' => function($model){
                        return '
                        <pre class="editable" data-name="value" data-value="'.htmlentities($model->value).'" data-setting_id="'.$model->id.'">'
                        .htmlentities($model->value).
                        '</pre>';
                    },
                    'format' => 'raw'
                ],
                'description:ntext',
                [
                    'class' => '\pheme\grid\ToggleColumn',
                    'attribute' => 'active',
                    'filter' => [1 => Yii::t('yii', 'Yes'), 0 => Yii::t('yii', 'No')],
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]
    ); ?>
    <?php Pjax::end();
    $this->registerJs("
	$('.editable').each(function() {
        $(this).editable({
            type: 'textarea',
            url: '".Url::to('/settings/ajax/update-field')."',
            pk: Number($(this).data('setting_id')),
            params : {
                ".Yii::$app->request->csrfParam." : '". Yii::$app->request->getCsrfToken()."'
            },
            inputclass: 'input_500',
            success: function(response, newValue) {
                if(response.is_success === 1) {
                    return true;
                } else {
                    error_text = '';
                    if (typeof response.errors == 'object') {
                        for(key in response.errors) {
                            error_text += response.errors[key];
                        }
                    } else {
                        error_text = response.errors;
                    }
                    return error_text;
                }
            },
            ajaxOptions: {
                type: 'post',
                dataType: 'json'
            }
        })
});
");
    $this->registerCss(".editableform .form-control.input_500 {width:500px}");
    ?>
</div>
