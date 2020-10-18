<?php if ($message != '') :?>
    <h3><?= $message;?></h3>
<?php endif;?>
<?php if (isWriter()) :?>
    <form action="/posts/add/" method="post">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="iat">
                    <label for="title">Заголовок:</label>
                    <input id="title" size="50" name="title" value="<?= $postTitle;?>">
                </td>
            </tr>
            <tr>
                <td class="iat">
                    <label for="content">Текст сообщения:</label>
                    <textarea id="content" name="content" cols="40" rows="3"><?= $postContent;?></textarea>
                </td>
            </tr>
            <tr>
                <td class="iat">
                    <label for="recipient">Кому:</label>
                    <select id="recipient" name="recipient" size="1">
                        <?php foreach ($usersCollection as $key => $value) :?>
                                <?php if ($value['login'] != $_SESSION['login']) :?>
                                    <option value="<?= $value['login'];?>"
                                        <?= $value['login'] == $postRecipient ? 'selected="selected"' : '';?>>
                                        <?= $value['login'];?>
                                    </option>
                                <?php endif;?>
                        <?php endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="iat">
                    <label for="topic">Раздел:</label>
                    <select id="topic" name="topic" size="1">
                        <?php foreach ($topicsCollection as $key => $value) :?>
                                <option style="background: <?= $value['hex_code'];?>"
                                    value="<?= $value['id'];?>"
                                    <?= $value['id'] == $postTopic ? 'selected="selected"' : '';?>>
                                    <?= $value['topic'];?>
                                </option>
                        <?php endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name="submit" value="Отправить">
                </td>
            </tr>
        </table>
    </form>
<?php endif;?>
