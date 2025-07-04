<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\models\Campaign;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
$showNav = false;
$showHeader = true;
$showFooter = true;
$sel = new stdclass();
$uri = $_SERVER['REQUEST_URI'];
$id = "";
if (!empty($_GET['campaignId'])) {
    $id = $_GET['campaignId'];
}
if (empty($id) && !empty($_GET['id'])) {
    $id = $_GET['id'];
}
if (!empty($id)) {
    $campaign = Campaign::findOne($id);
    $campaignRules = json_decode($campaign->rules);
}
$uris = $campaignRules->Navigation ?? Yii::$app->params['navigation'];
if (!empty($uris)) {
    foreach ($uris as $key => $value) {
        $sel->{$key} = "";
        if (str_contains($uri, $value)) {
            $showNav = true;
            $sel->{$key} = "nav-link-selected";
            $name = Campaign::getName($id);
        }
    }
}
if (str_contains($uri, "map/map")) {
    $showNav = false;
    $showHeader = false;
    $showFooter = false;
}
$date = date('Y');
$appName = Yii::$app->name;
$footer = <<<HTML
    <hr />
    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-start">Made with &#9829; by Gregory Sposato.</p>
            <p class="float-start">&nbsp;&nbsp;&nbsp;&copy; {$appName} 2024-{$date}</p>
            <p class="float-start">&nbsp;&nbsp;&nbsp;<a href="/frontend/web/site/about">About</a>
        </div>
    </footer>
HTML;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- ./bootstrap -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <script src="/frontend/web/js/scripts.js"></script>
    <link href="/frontend/web/css/styles.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Grenze+Gotisch:wght@100..900&display=swap" rel="stylesheet">
<style>
.navbar-brand {
  font-family: "Grenze Gotisch", serif;
  font-optical-sizing: auto;
  font-weight: 500;
  font-style: normal;
  <?php if (date('n') == 6): ?>
  background: linear-gradient(90deg, red, orange, yellow, green, cyan, indigo, violet);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: bold;
  <?php endif; ?>
}
pre {
    background-color:#333;
    color: #fff;
}
</style>
</head>
<body class="sb-nav-fixed">
<?php $this->beginBody() ?>

        <?php if ($showHeader): ?>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="/"><?= Yii::$app->name; ?></a>
            <?php if ($showNav): ?>
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <?php endif; ?>
            <?php if (!Yii::$app->user->isGuest && !$showNav): ?>
                <form class="d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" action="/frontend/web/site/logout" method="post">
                    <?php $csrf = Yii::$app->request->getCsrfToken(); ?>
                    <input type="hidden" name="_csrf-frontend" value="<?= $csrf; ?>">
                    <button class="dropdown-item" type="submit" style="color:white;">
                        <i class="fas fa-sign-out"></i>&nbsp;Logout&nbsp;&nbsp;
                    </button>
                </form>
            <?php endif; ?>
        </nav>
        <?php endif; ?>
        <?php if (!Yii::$app->user->isGuest && $showNav): ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading"><?= $name; ?></div>
                            <?php foreach ($uris as $label => $link): ?>
                            <?php $grey = $sel->{$label}; ?>
                            <a class="nav-link <?= $grey; ?>" href="<?= $link; ?>?campaignId=<?= $id; ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-hashtag"></i></div>
                                <?= ucfirst($label); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as</div>
                        <?= Yii::$app->user->identity->username ?? ""; ?>
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <form class="d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" action="/frontend/web/site/logout" method="post">
                                <?php $csrf = Yii::$app->request->getCsrfToken(); ?>
                                <input type="hidden" name="_csrf-frontend" value="<?= $csrf; ?>">
                                <button class="dropdown-item" type="submit" style="color:white;">
                                    <i class="fas fa-sign-out"></i>&nbsp;Logout&nbsp;&nbsp;
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main role="main" class="flex-shrink-0">
                    <div class="container">
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                    <?php if ($showFooter): ?>
                        <?= $footer; ?>
                    <?php endif; ?>
                </main>
            </div>
            <?php else: ?>
                <main role="main" class="flex-shrink-0">
                    <div class="container">
                        <?php if ($showHeader): ?>
                        <?= Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                        <?php endif; ?>
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                    <?php if ($showFooter): ?>
                        <?= $footer; ?>
                    <?php endif; ?>
                </main>
        <?php endif; ?>
<?php $this->endBody() ?>
    <script type="text/javascript">
        const timezone = encodeURIComponent(Intl.DateTimeFormat().resolvedOptions().timeZone);
        function postTimezoneToServer(timezone) {
            isGuest = <?= empty(Yii::$app->user->identity->id) ? 'true' : 'false'; ?>;
            if (isGuest) {
                return;
            }
            const xhr = new XMLHttpRequest();
            const url = '/frontend/web/site/timezone?tz='+timezone; // URL of the PHP backend
            xhr.open('GET', url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Timezone successfully sent to the server.');
                }
            };
            xhr.send(`timezone=${encodeURIComponent(timezone)}`);
        }
        postTimezoneToServer(timezone);
    </script>
</body>
</html>
<?php $this->endPage();
