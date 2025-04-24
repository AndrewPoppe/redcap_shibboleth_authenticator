<?php

namespace YaleREDCap\ShibbolethAuthenticator;

/** @var ShibbolethAuthenticator $module */

$idps = $module->getSettings();

?>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="row my-5">
        <div class="col-md-12">
            <h1 class="text-center">Please log in by clicking one of the images below</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-6">
            <?php foreach ($idps as $idp) { ?>
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <a href="<?php echo $idp['idp-url']?>">
                            <img src="<?php echo $module->framework->escape($idp['idp-link-image-url']); ?>" alt="Logo" class="card-img-top" style="width: 300px; height: auto;">
                        </a>
                        <p class="card-text"><?php echo $module->framework->escape($idp['idp-text']); ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
</body>