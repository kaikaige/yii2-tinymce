tinymce
=======
tinymce

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kaikaige/yii2-tinymce "*"
```

or add

```
"kaikaige/yii2-tinymce": "*"
```

to the require section of your `composer.json` file.


Usage
-----

基础用法

```php
<?= $form->field($model, 'attribute')->widget(\kaikaige\tinymce\TinyMce::class);?>
````
高级用法
```php
<?= $form->field($model, 'name')->widget(\kaikaige\tinymce\TinyMce::class, [
        'height'=>600, //高度，单位px
        'uploadUrl' => ['site/upload'], //图片上传地址
        'baseUrl' => "/", //
        'toolbar' => "",
        'clientOptions'=>[],
    ]); ?>
```
#### options
* `height` number 高度，单位px
* `uploadUrl` string 图片上传地址
