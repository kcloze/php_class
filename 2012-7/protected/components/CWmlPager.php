<?php
class CWmlPager extends CBasePager{
    /**
     * @var string the text label for the next page button. Defaults to 'Next &gt;'.
     */
    public $nextPageLabel;
    /**
     * @var string the text label for the previous page button. Defaults to '&lt; Previous'.
     */
    public $prevPageLabel;
    /**
     * @var string the text shown before page buttons. Defaults to 'Go to page: '.
     */
    public $header;
    /**
     * @var string the text shown after page buttons.
     */
    public $footer='';
    /**
     * @var array HTML attributes for the pager container tag.
     */
    public $htmlOptions=array();

    /**
     * Initializes the pager by setting some default property values.
     */
    public function init()
    {
        if($this->nextPageLabel===null)
            $this->nextPageLabel=Yii::t('yii','下一页');
        if($this->prevPageLabel===null)
            $this->prevPageLabel=Yii::t('yii','上一页');

        if(!isset($this->htmlOptions['id']))
            $this->htmlOptions['id']=$this->getId();
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        $buttons=$this->createPageButtons();
        if(empty($buttons))
            return;
        echo $this->header;
        echo implode("\n",$buttons);
        echo '<br/>';
        echo '第' . CWml::textField($this->getPages()->pageVar, $this->getCurrentPage()+1, array('size'=>'2')) . '页';
        echo CWml::submitButton('Go', array($this->getPages()->pageVar=>'$('.$this->getPages()->pageVar.')'), array(), 'get');
        echo '&nbsp;共' . ($this->getCurrentPage()+1) . '/' . $this->getPageCount() . '页';
        echo $this->footer;
    }

    /**
     * Creates the page buttons.
     * @return array a list of page buttons (in HTML code).
     */
    protected function createPageButtons()
    {
        if(($pageCount=$this->getPageCount())<=1)
            return array();

        $currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $buttons=array();

        // prev page
        if(($page=$currentPage-1)<0)
            $page=0;
        $buttons[]=$this->createPageButton($this->prevPageLabel,$page,'',$currentPage<=0,false);

        // next page
        if(($page=$currentPage+1)>=$pageCount-1)
            $page=$pageCount-1;
        $buttons[]=$this->createPageButton($this->nextPageLabel,$page,'',$currentPage>=$pageCount-1,false);

        return $buttons;
    }

    /**
     * Creates a page button.
     * You may override this method to customize the page buttons.
     * @param string the text label for the button
     * @param integer the page number
     * @param string the CSS class for the page button. This could be 'page', 'first', 'last', 'next' or 'previous'.
     * @param boolean whether this page button is visible
     * @param boolean whether this page button is selected
     * @return string the generated button
     */
    protected function createPageButton($label,$page,$class,$hidden,$selected)
    {
        if($hidden || $selected){
            return $label;
        }
        
        return CWml::link($label,$this->createPageUrl($page));
    }
    
    
}  
?>
