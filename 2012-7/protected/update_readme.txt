 升级注意事项：
 ///////////////////////////////////////////////////////////////////////////////////////////////
 1.yii框架在含有图片的表单中，修改的时候会丢失图片的bug：
 
       需要修改位置ource Code: framework/web/helpers/CHtml.php#1267 (show)：
      用下面的代码覆盖activeFileField函数。
    public static function activeFileField($model,$attribute,$htmlOptions=array(),$hiddenvalue='')
        {
                self::resolveNameID($model,$attribute,$htmlOptions);
                // add a hidden field so that if a model only has a file field, we can
                // still use isset($_POST[$modelClass]) to detect if the input is submitted
                $hiddenOptions=isset($htmlOptions['id']) ? array('id'=>self::ID_PREFIX.$htmlOptions['id']) : array('id'=>false);
                return self::hiddenField($htmlOptions['name'],$hiddenvalue,$hiddenOptions)
                        . self::activeInputField('file',$model,$attribute,$htmlOptions);
     }
 //////////////////////////////////////////////////////////////////////////////////////////////// 