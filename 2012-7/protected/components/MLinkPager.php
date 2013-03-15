<?php
class MLinkPager extends CLinkPager{
    public $url = '';
    public $anchor='';
    protected function createPageUrl($page)
    {
        $urls = parse_url($this->url);
        //var_dump($urls);
        $url = $urls['scheme']?($urls['scheme']. '://'):'';
        $url .= $urls['host']?$urls['host']:'';
        $url .= $urls['path']?$urls['path']:'';
        
        $params = array();
        if($urls['query']){
            parse_str($urls['query'], $params);
        }
        //var_dump($params);die;
        $params = $params + array('p'=>$page);
        $url .= '?' . http_build_query($params);
        if($this->anchor) $url .='#'.$this->anchor;
        return $url;
    }    
}
?>
