<?php if (isWriter()) :?>
    <p>Непрочитанные сообщения:</p>
    <ul>
    <?php if (!empty($unreadMessages)) :?>
        <?php foreach ($unreadMessages as $value) :?>
            <li class="profile_user">
                <?php $messageId = array_shift($value);?>
                <p><a href="/posts/view_message/?mes_id=<?= $messageId;?>">
            <?php foreach ($value as $subKey => $subValue) :?>
                <?= $subValue . '. ';?>
            <?php endforeach;?>
                </a></p>
            </li>
        <?php endforeach;?>
    <?php else :?>
        <p>Нет сообщений для отображения</p>
    <?php endif;?>
    </ul>
    <p>Прочитанные сообщения:</p>
    <ul>
    <?php if (!empty($readMessages)) :?>
        <?php foreach ($readMessages as $value) :?>
            <li class="profile_user">
                <?php $messageId = array_shift($value);?>
                <p><a href="/posts/view_message/?mes_id=<?= $messageId;?>">
            <?php foreach ($value as $subKey => $subValue) :?>
                <?= $subValue . '. ';?>
            <?php endforeach;?>
                </a></p>
            </li>
        <?php endforeach;?>
    <?php else :?>
        <p>Нет сообщений для отображения</p>
    <?php endif;?>
    </ul>
    <div class="clearfix"></div>
    <div class = "button">
        <a href="/posts/add/">Написать сообщение</a>
    </div>
<?php else :?>
    <p>Вы сможете отправлять сообщения после прохождения модерации</p>
<?php endif;?>