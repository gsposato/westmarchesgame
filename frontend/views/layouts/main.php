<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
$showNav = false;
$uri = $_SERVER['REQUEST_URI'];
$uris = Yii::$app->params['showNav'];
if (in_array($uri, $uris)) {
    $showNav = true;
}
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
}
</style>
</head>
<body class="sb-nav-fixed">
<?php $this->beginBody() ?>

        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="/"><?= Yii::$app->name; ?></a>
            <?php if ($showNav): ?>
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <?php endif; ?>
            <?php if (!Yii::$app->user->isGuest): ?>
                <form class="d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" action="/frontend/web/site/logout" method="post">
                    <?php $csrf = Yii::$app->request->getCsrfToken(); ?>
                    <input type="hidden" name="_csrf-frontend" value="<?= $csrf; ?>">
                    <button class="dropdown-item" type="submit" style="color:white;">
                        <i class="fas fa-sign-out"></i>&nbsp;Logout&nbsp;&nbsp;
                    </button>
                </form>
            <?php endif; ?>
        </nav>
        <?php if ($showNav): ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Campaign</div>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>
                                Announcements
                            </a>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                                Documents
                            </a>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Players
                            </a>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-star"></i></div>
                                Games
                            </a>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                                Rules
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?= Yii::$app->user->identity->username ?? ""; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main role="main" class="flex-shrink-0">
                    <div class="container">
                        <?= Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                </main>
                <footer class="footer mt-auto py-3 text-muted">
                    <div class="container">
                        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
                    </div>
                </footer>
            </div>
            <?php else: ?>
                <main role="main" class="flex-shrink-0">
                    <div class="container">
                        <?= Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                </main>
                <footer class="footer mt-auto py-3 text-muted">
                    <div class="container">
                        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
                    </div>
                </footer>
        <?php endif; ?>





<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
