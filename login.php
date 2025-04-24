<?php

namespace YaleREDCap\ShibbolethAuthenticator;

/** @var ShibbolethAuthenticator $module */

$settings = $module->getSettings();
echo '<pre>';
var_dump($_COOKIE);
var_dump($_SERVER);
echo '</pre>';
?>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="row my-5">
        <div class="col-md-12">
            <h1 class="text-center"><?php echo $settings['login-message']?></h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6">
            <?php foreach ($settings['idps'] as $idp) { ?>
                <div class="card mb-3 bg-light">
                    <a class="text-decoration-none text-body" href="<?php echo $idp['idp-url'].'&target='.$_GET['return']?>">
                        <div class="card-body text-center">
                            <img src="<?php echo $module->framework->escape($idp['idp-link-image-url']); ?>" alt="Logo" class="my-3" style="width: 300px; height: auto;">
                            <p class="card-text"><?php echo $module->framework->escape($idp['idp-text']); ?></p>
                        </div>
                    </a>    
                </div>
            <?php } ?>
        </div>
</body>