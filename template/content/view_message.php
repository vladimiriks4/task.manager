<?php if ($oneMessage != null && $oneMessage != false && $oneMessage != '') :?>
	<p><?= $oneMessage['title'] . '. ' . $oneMessage['sent'];?></p>
	<p>
	    <?= $oneMessage['topic'] != $oneMessage['parent_topic'] ? $oneMessage['parent_topic']
	            . '. ' . $oneMessage['topic'] : $oneMessage['topic'];
	    ?>
	</p>
	<p><?= $oneMessage['login'] . '. ' . $oneMessage['email'];?></p>
	<p><?= $oneMessage['content'];?></p>
<?php else :?>
	<h3>Сообщение не существует</h3>
<?php endif;?>
