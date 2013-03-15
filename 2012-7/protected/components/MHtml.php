<?php
class MHtml extends CHtml{
	public static function tinyMceTextarea($name,$value='',$htmlOptions=array())
	{

		$baseUrl = Yii::app()->request->baseUrl;
		$siteUrl = Yii::app()->params['siteUrl'];
		Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/tiny_mce/jquery.tinymce.js');
		Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php');
		
		$_id = isset($htmlOptions['id'])?$htmlOptions['id']:parent::getIdByName($name);
		$_width = isset($htmlOptions['width'])?$htmlOptions['width']:'1000px';
		$_height = isset($htmlOptions['height'])?$htmlOptions['height']:'600px';
		$_fullpage = $htmlOptions['fullPage']?', fullpage':'';
		
		
		$_script = <<<EOF
		$('#{$_id}').tinymce({
			// Location of TinyMCE script
			script_url : '{$baseUrl}/js/tiny_mce/tiny_mce_src.js',
			document_base_url : "{$siteUrl}",
			relative_urls : false,
			// General options
			theme : "advanced",
			
			valid_elements: "*[*]",
			
			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template{$_fullpage},pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
		
			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",
		
			// Drop lists for link/image/media/template dialogs
			//template_external_list_url : "lists/template_list.js",
			//external_link_list_url : "lists/link_list.js",
			//external_image_list_url : "lists/image_list.js",
			//media_external_list_url : "lists/media_list.js",
		
			// Replace values for the template plugin
			//template_replace_values : {
			//	username : "Some User",
			//	staffid : "991234"
			//},
			
	
			plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist{$_fullpage}",
			file_browser_callback : "tinyBrowser",
			content_css : "css/content.css",
			width : "{$_width}",
			height : "{$_height}"
		});
EOF;
		Yii::app()->clientScript->registerScript($_id.'_js', $_script);	
			
		return parent::textArea($name, $value, $htmlOptions);
		
	}
	
	public static function activeTinyMceTextarea($model,$attribute,$htmlOptions=array())
	{
		if(isset($htmlOptions['id'])) $htmlOptions['id'] = parent::activeId($model, $attribute);
		$_name = parent::activeName($model, $attribute);
		$_text=self::resolveValue($model,$attribute);
		return self::tinyMceTextarea($_name,$_text, $htmlOptions);
	}
	
	public static function dateField($name,$value='',$htmlOptions=array())
	{
		$_id = isset($htmlOptions['id'])?$htmlOptions['id']:parent::getIdByName($name);
		
		$_dateFormat = isset($htmlOptions['dateFormat'])?$htmlOptions['dateFormat']:'yy-mm-dd';
		
		$_script = <<<EOF
		$('#{$_id}').datepicker({ dateFormat: '{$_dateFormat}' });
EOF;
		Yii::app()->clientScript->registerScript($_id.'_js', $_script);	
		
		return parent::textField($name, $value, $htmlOptions);
	}
	
	public static function activeDateField($model, $attribute, $htmlOptions=array())
	{
		if(isset($htmlOptions['id'])) $htmlOptions['id'] = parent::activeId($model, $attribute);
		$_name = parent::activeName($model, $attribute);
		$_text=self::resolveValue($model,$attribute);
		return self::dateField($_name, $_text, $htmlOptions);
	}
	public static function activeCategories($model,$attribute,$category=array(),$Tree,$htmlOptions=array()){
		echo ' <select name="'.parent::activeName($model, $attribute).'" id="'.parent::activeId($model, $attribute).'">';
        $str_option='';
        $_name=parent::activeName($model, $attribute);
		foreach ($category as $key=>$cid){ 
				$selected='';
				if($model->$attribute==$cid) $selected='selected="selected"';
				$str_option.='<option '.$selected.' value="'.$cid.'">';
				$str_option.=$Tree->getLayer($cid, '┣').$Tree->getValue($cid );
	            $str_option.='</option>';
	    }
	    $selected=empty($model->$attribute)?'selected="selected"':'';
	   if(isset($htmlOptions['type']) && $htmlOptions['type']=='3'){
	     	echo "<option value=''" .$selected.">所有分类</option>";
	   }
	   elseif(isset($htmlOptions['type']) && $htmlOptions['type']=='2'){
	   	 echo "<option value=''" .$selected.">请选择分类</option>";
	   }elseif(isset($htmlOptions['type']) && $htmlOptions['type']=='4'){
	   	if($model->$attribute==='') {
	   	 $selected='selected="selected"';
	   	 echo "<option value=''" .$selected.">所有分类</option>";
	   	 echo "<option value='0'>根目录</option>";
	   	}elseif(intval($model->$attribute)===0){
	   		$selected='selected="selected"';
	   		echo "<option value=''>所有分类</option>";
	   	    echo "<option value='0'".$selected.">根目录</option>";
	   	}else{
	   		echo "<option value=''>所有分类</option>";
	   	    echo "<option value='0'>根目录</option>";
	   	}
	   }
	   else{
	   	 echo "<option value='0'" .$selected.">根目录</option>";
	   }
	   echo $str_option;
	   echo "</select>";
	   if(isset($htmlOptions['href'])){
			   $location="admin?Categories['ParentId']=";
			   $_id=parent::activeId($model, $attribute);
			   $_script = <<<EOF
				$('#{$_id}').change(function(){ 
				    
				   var pid=$('#{$_id}').val();
				   //if(pid=='') return false;
				   location.href=encodeURI('./admin?{$_name}='+ pid);
			    });
EOF;
     Yii::app()->clientScript->registerScript($_id.'_js', $_script);	
	   }
	   
	 }
}