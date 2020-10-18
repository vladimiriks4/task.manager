<ul class="<?= $ulClass; ?>">
    <?php foreach ($items as $item) :?>
    	<?php if ($item['path'] != '/posts/view_message/') :?>
	        <li class="<?= checkItem($item['path']) ? 'current-item' : ''; ?>">
	        	<a href="<?= $item['path']; ?>"><?= shortString($item['title']); ?></a>
	        </li>
	    <?php endif;?>
    <?php endforeach;?>
</ul>
