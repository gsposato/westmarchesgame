<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

?>
<div class="new-ticket">
    <p>Hello,</p>
    <p>A new ticket has been created for campaign <b><?= $campaign; ?></b>.</p>
</div>
<hr />
<a href="<?= $unsubscribe; ?>">Unsubscribe</a>
