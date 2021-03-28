<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/css/style.css">

        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->name,
                'brandImage' => '/images/cob-logo3.png',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-cob navbar-fixed-top',
                ],
            ]);
            $navItems = array();
            $navItems[] = ['label' => 'Home', 'url' => ['/site/index']];
            $navItems[] = ['label' => 'About', 'url' => ['/site/about']];
            $navItems[] = ['label' => 'Contact', 'url' => ['/site/contact']];
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->user->identity->type == "Admin") {
                    $navItems[] = ['label' => 'Organizations', 'url' => ['/organization/index']];
                    $navItems[] = ['label' => 'Insurance Companies', 'url' => ['/insurance/index']];
                    $navItems[] = ['label' => 'Pharmacies', 'url' => ['/pharmacy/index']];
                    $navItems[] = ['label' => 'ICD10 & CPT Sync', 'url' => ['/icd10/sync']];
                } else if (Yii::$app->user->identity->type == "Organization") {
                    $navItems[] = ['label' => 'Locations Management', 'url' => ['/organization/locations']];
                    $navItems[] = ['label' => 'Doctors', 'url' => ['/doctor/index']];
                    $navItems[] = ['label' => 'Frontdesk', 'url' => ['/frontdesk/index']];
                    $navItems[] = ['label' => 'Insurance', 'url' => ['/organization-insurance/index']];
                } else if (Yii::$app->user->identity->type == "Insurance Company") {
                    $navItems[] = ['label' => 'Company Profiles', 'url' => ['/insurance-profile/index']];
                    $navItems[] = ['label' => 'Plans', 'url' => ['/insurance-plan/index']];
                } else if (Yii::$app->user->identity->type == "Insurance Profile") {
                    $navItems[] = ['label' => 'Inbox', 'url' => ['/inbox/index']];
                    $navItems[] = ['label' => 'Visits', 'url' => ['/visit/index']];
                    $navItems[] = ['label' => 'Patients', 'url' => ['/patient/index']];
                    $navItems[] = ['label' => 'Organizations', 'url' => ['/organization-insurance/index']];
                } else if (Yii::$app->user->identity->type == "Doctor") {
                    $navItems[] = ['label' => 'Visits', 'url' => ['/visit/index']];
                    $navItems[] = ['label' => 'Patients', 'url' => ['/patient/index']];
                    $navItems[] = ['label' => 'Templates', 'url' => ['/template/index']];
                    $navItems[] = ['label' => 'ICD10', 'url' => ['/custom-icd10/index']];
                    $navItems[] = ['label' => 'CPT', 'url' => ['/cpt/index']];
                    $navItems[] = ['label' => 'Inbox', 'url' => ['/inbox/index']];
                    $navItems[] = ['label' => 'Reports', 'url' => ['/report/index']];
                    $navItems[] = ['label' => 'Billing Post', 'url' => ['/billing-post/index']];
                } else if (Yii::$app->user->identity->type == "Pharmacy") {
                    $navItems[] = ['label' => 'Locations Management', 'url' => ['/pharmacy/locations']];
                    $navItems[] = ['label' => 'Pharmaciests', 'url' => ['/pharmaciest/index']];
                    $navItems[] = ['label' => 'Insurance', 'url' => ['/pharmacy-insurance/index']];
                } else {
                    $navItems[] = ['label' => 'Visits', 'url' => ['/visit/index']];
                    $navItems[] = ['label' => 'Patients', 'url' => ['/patient/index']];
                    $navItems[] = ['label' => 'Inbox', 'url' => ['/inbox/index']];
                }

                $navItems[]=['label' => Yii::$app->user->identity->username,
                    'url' => ['#'],
                    'template' => '<a href="{url}" >{label}<i class="fa fa-angle-left pull-right"></i></a>',
                    'items' => [
                        ['label' => 'Logout', 'url' => '/site/logout'],
                        ['label' => 'Change Password', 'url' => '/account/change-password'],
                        ['label' => 'Account Management', 'url' => '/account/management'],
                    ],
                ];
            } else {
                $navItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $navItems,
                    //[
                    //    
                    //	
                    //	Yii::$app->user->isGuest ? (
                    //        ['label' => 'Visits', 'url' => ['/visit/index']]
                    //    ) : (
                    //		['label' => 'Visits', 'url' => ['/visit/index']],
                    //		['label' => 'Patients', 'url' => ['/patient/index']],
                    //		['label' => 'Doctor', 'url' => ['/doctor/index']],
                    //	)
                    //    Yii::$app->user->isGuest ? (
                    //        ['label' => 'Login', 'url' => ['/site/login']]
                    //    ) : (
                    //     '<li>'
                    //     . Html::beginForm(['/site/logout'], 'post')
                    //     . Html::submitButton(
                    //         'Logout (' . Yii::$app->user->identity->username . ')',
                    //         ['class' => 'btn btn-link logout']
                    //     )
                    //     . Html::endForm()
                    //     . '</li>'
                    //		
                    //    )
                    //	
                    //],
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
<?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; COB <?= date('Y') ?> <br />
                    aaa
                </p>


                <div class="pull-right" onclick="location.href='http://ad-on.us'" style="cursor: pointer; width: 100px;position: relative;height: 54px;background-image: url('/images/adon-small.png'); bottom: 15px;"></div>
            </div>
        </footer>

<?php $this->endBody() ?>


    </body>
</html>
<?php $this->endPage() ?>