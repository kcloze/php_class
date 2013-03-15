<div class="pages"><a href="?<?php echo $pages->pageVar. "=". ($pages->getCurrentPage()) ?>">上一页</a>
<?php $i = 0; while( $i < $pages->getPageCount() ) : ?>
	<?php if( $i == $pages->getCurrentPage() ) : ?>
		<a class="cur" href="javascript:void(0);"><?php echo $i+1 ?></a>
	<?php else : ?>	
		<a href="?<?php echo $pages->pageVar. "=". ($i+1) ?>"><?php echo $i+1 ?></a>
	<?php endif; ?>
<?php $i++; endwhile; ?>
<a href="?<?php echo $pages->pageVar. "=". ($pages->getCurrentPage()+2) ?>">下一页</a></div>