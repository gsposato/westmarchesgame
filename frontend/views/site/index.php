<?php

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h3 class="display-4 gothic">Campaigns</h3>
        </div>
    </div>

    <div class="body-content">

        <div class="row">
            <?php foreach ($campaigns as $campaign):?>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <?php endforeach; ?>
            <div class="col-lg-4">
                <div class="card">
                <div class="card-header">
                    Create New Campaign
                </div>
                <div class="card-body">
                    <h2>New Campaign</h2>
                    <p>Create a new campaign!  It all starts here.</p>
                    <p><a class="btn btn-primary" href="#">Create New Campaign</a></p>
                </div>
                </div>
            </div>
        </div>

    </div>
</div>
