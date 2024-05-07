<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $setting['title']; ?></title>
    <link rel="stylesheet" href="<?= base_url('/') ?>assets/css/main/app.css">
    <link rel="stylesheet" href="<?= base_url('/') ?>assets/css/pages/error.css">

    <link rel="shortcut icon" href="<?= base_url('/') ?>assets/images/logo/logo.png" type="image/png">
</head>

<body>
    <div id="error">


        <div class="error-page container">
            <div class="col-md-8 col-12 offset-md-2">
                <div class="text-center">
                    <img class="img-error" src="<?= base_url('/') ?>assets/images/samples/error-403.svg" alt="Not Found">
                    <h1 class="error-title"><?= $setting['response']; ?></h1>
                    <p class="fs-5 text-gray-600">You are unauthorized to see this page.</p>
                    <a href="<?= base_url('') ?>" class="btn btn-lg btn-outline-primary">Go Dashboard</a>
                </div>
            </div>
        </div>


    </div>
</body>

</html>