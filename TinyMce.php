<?php
/**
 * @copyright Copyright (c) 2013-2017 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace kaikaige\tinymce;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 *
 * TinyMCE renders a tinyMCE js plugin for WYSIWYG editing.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 */
class TinyMce extends InputWidget
{
    /**
     * @var string the language to use. Defaults to null (en).
     */
    public $language = "zh_CN";
    /**
     * @var array the options for the TinyMCE JS plugin.
     * Please refer to the TinyMCE JS plugin Web page for possible options.
     * @see http://www.tinymce.com/wiki.php/Configuration
     */
    public $clientOptions = [];

    public $uploadUrl = ['site/upload'];

    public $basePath = "/";

    public $height = 400;

    public $plugins = [
        "image",
        "advlist autolink lists link charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste ",
    ];

    public $toolbar = "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image";

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerClientScript();
    }

    /**
     * Registers tinyMCE js plugin
     */
    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();

        TinyMceAsset::register($view);

        $id = $this->options['id'];

        $this->clientOptions['selector'] = "#$id";
        // @codeCoverageIgnoreStart
        if ($this->language !== null && $this->language !== 'en') {
            $langFile = "langs/{$this->language}.js";
            $langAssetBundle = TinyMceLangAsset::register($view);
            $langAssetBundle->js[] = $langFile;
            $this->clientOptions['language_url'] = $langAssetBundle->baseUrl . "/{$langFile}";
            $this->clientOptions['language'] = "{$this->language}";//Language fix. Without it EN language when add some plugins like codemirror
        }

        $this->clientOptions['setup'] = new \yii\web\JsExpression("function(editor){editor.on('change',function(){ editor.save()})}");

        $this->clientOptions['height'] = $this->height . "px";
        $this->clientOptions['plugins'] = $this->plugins;
        $this->clientOptions['toolbar'] = $this->toolbar;
        $this->clientOptions['images_upload_url'] = Url::to($this->uploadUrl);
        $this->clientOptions['images_upload_base_path'] = $this->basePath;

        $options = Json::encode($this->clientOptions);
        $js[] = "tinymce.init($options);";
        $view->registerJs(implode("\n", $js));
    }
}
