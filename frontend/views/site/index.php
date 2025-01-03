<?php

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h3 class="display-4 gothic">Campaigns</h3>
            <h1><i class="fa fa-chevron-down"></i></h1>
        </div>
    </div>

    <div class="body-content">

        <div class="row">
            <?php foreach ($campaigns as $campaign):?>
            <div class="col-lg-4" style="padding:10px">
                <div class="card">
                <div class="card-header">
                    <?= $campaign->name; ?>
                </div>
                <div class="card-body">
                    <h2><?= $campaign->name; ?></h2>
                    <p>Manage this campaign.</p>
                    <?php $id = $campaign->id; ?>
                    <?php $href = "/frontend/web/campaign-announcement?campaignId=$id"; ?>
                    <p><a class="btn btn-primary" href="<?= $href; ?>">Manage</a></p>
                </div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="col-lg-4" style="padding: 10px">
                <div class="card">
                <div class="card-header">
                    Create New Campaign
                </div>
                <div class="card-body">
                    <h2>New Campaign</h2>
                    <p>Create a new campaign!  It all starts here.</p>
                    <p><a class="btn btn-success" href="/frontend/web/campaign/create">Create New Campaign</a></p>
                </div>
                </div>
            </div>
        </div>

    </div>
</div>
