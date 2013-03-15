<?php
/**
* 重新封装一些组件给WML页面用
*/
class CWml extends CHtml{
    
    /**
    * 输出的url里面，符合wml1.2标准，& = &amp;
    * 
    * @param mixed $url
    * @return mixed
    */
    public static function normalizeUrl($url)
    {
        $tmp = parent::normalizeUrl($url);
        $tmp = str_replace('&amp;', '&', $tmp);
        $tmp = str_replace('&', '&amp;', $tmp);
        return $tmp;
    }
    
    /**
    * 把选择项移到第一个
    * 
    * @param mixed $name
    * @param mixed $select
    * @param array $data
    * @param array $htmlOptions
    * @return mixed
    */
    public static function dropDownList($name,$select,$data,$htmlOptions=array())
    {
        foreach($data as $key=>$value)
        {
            if($select == $key)
            {
                unset($data[$key]);
                $rdata = array_reverse($data, true);
                $rdata[$key]=$value;
                $data = array_reverse($rdata, true);
                break;
            }
        }
        
        return parent::dropDownList($name,$select,$data,$htmlOptions);
    }
    
    /**
    * 提交按钮
    * 
    * @param mixed $label 
    * @param array $postfields 提交的字段array('name'=>'$name')
    * @param mixed $url 提交的地址
    * @param mixed $method 提交的Post 或 get
    * @param mixed $htmlOptions
    * @return string
    */
    public static function submitButton($label='submit', $postfields=array(), $url=array(), $method='get',$htmlOptions=array())
    {
        $html = '';
        foreach($postfields as $name => $value)
        {
            $postfields_options = array();
            $val = $value;
            if(is_array($value))
            {
                $val = isset($value['value'])?$value['value']:'';
                if(isset($value['htmlOptions']))
                {
                    $postfields_options = $value['htmlOptions'];
                    unset($value['htmlOptions']);
                }
            }
            
            $html .= self::postField($name, $val, $postfields_options);
        }
        
        $html = self::go($url, $html, $method, $htmlOptions);
        $html = self::anchor($label, $html);
        return $html;
    }
    
    public static function postField($name, $value, $htmlOptions=array())
    {
        $htmlOptions['name'] = $name;
        $htmlOptions['value'] = $value;
        return self::tag('postfield', $htmlOptions, false, ture);
    }
    
    public static function anchor($title, $content, $htmlOptions=array())
    {
        $htmlOptions['title']=$title;
        $content = $title . $content;
        return self::tag('anchor', $htmlOptions, $content, true);
    }
    
    public static function go($url, $content, $method='get', $htmlOptions=array())
    {
        if(is_array($url))
        {
            $htmlOptions['href'] = self::normalizeUrl($url);
        }else{
            $htmlOptions['href'] = $url;
        }
        $htmlOptions['method'] = $method;
        return self::tag('go', $htmlOptions, $content, true);
    }
}
?>
