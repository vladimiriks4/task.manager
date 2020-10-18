<?php echo "<p>Личные данные:</p>";?>
<ul>
<?php foreach ($_SESSION['user'] as $key => $value) :?>
    <li class="profile_user">
        <p><?= $key;?> : <?= $value;?></p>
    </li>
<?php endforeach;?>
</ul>

<?php echo "<p>Группы пользователя:</p>";?>
<ul>
<?php foreach ($_SESSION['groups'] as $value) :?>
    <li class="profile_user">
    <?php foreach ($value as $subKey => $subValue) :?>
        <p><?= $subKey;?> : <?= $subValue;?></p>
    <?php endforeach;?>
    </li>
<?php endforeach;?>
</ul>
