<?php
class PagerHelper{
    public static function Pager($count, $pageurl, $pagesize = 10, $currentPage = 0,$anchor=''){
        $pages = new CPagination($count);
        $pages->setPageSize($pagesize);
        $pages->setCurrentPage($currentPage);
        
        $linkPage = new MLinkPager();
        if($anchor) $linkPage->anchor=$anchor;
        $linkPage->htmlOptions = array('class'=>'link_pager');
        $linkPage->firstPageLabel = '第一页';
        $linkPage->lastPageLabel = '最后一页';
        $linkPage->prevPageLabel = '上一页';
        $linkPage->nextPageLabel = '下一页';
        $linkPage->setPages($pages);
        $linkPage->url = $pageurl;
        $linkPage->run();
    } 
}