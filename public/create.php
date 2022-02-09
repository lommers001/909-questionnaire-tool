<?php
include "../private/includes.php";
$is_copying = false;
if(isset($_GET['id'])){
    $landing = pdo_get("landings", "id", $_GET["id"]);
    $is_copying = true;
}
$countries = pdo_get_all("countries");
$legals = pdo_get_all("legals");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="csrf_token">

    <title>Test</title>

    <!-- Styles -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/index.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">

    <!-- Pre-load fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

</head>

<body>
    <div class="icon-pane">
        <button class="btn p-1 w-100" onClick="window.location.href='/';"><div class="go-back clickable"></div></button>
        <button class="btn" onClick="window.location.href='/';"><i class="fa fa-lg fa-list"></i></button>
        <button class="btn" onClick="window.location.href='legals.php';"><i class="fa fa-lg fa-tags"></i></button>
        <button class="btn"><i class="fa fa-lg fa-pencil"></i></button>
    </div>
    <div class="main-pane">
        <div class="card">
            <div class="card-header"><?= $is_copying ? 'Copy' : 'Create' ?> campaign</div>
            <div class="card-body">
                <form action="save.php" method="post">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control required" name="name" id="name">
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="slug">Slug:</label>
                                <input type="text" class="form-control required" name="slug" id="slug">
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="pageTitle">Page title:</label>
                                <input type="text" class="form-control required" name="page_title" id="page_title">
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Active:</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" value="1" class="custom-control-input required" name="active" id="active">
                                    <label class="custom-control-label" for="active"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="legal_id">Legal text:</label>
                                <div class="d-flex">
                                    <select id="legal-select" class="form-control w-90 form-select" name="legal_id" id="legal_id">
                                        <option disabled>Choose legal text</option>
                                        <?php foreach($legals as $legal) { ?>
                                            <option value="<?= $legal['id'] ?>"><?= $legal['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <i id="see-legal" class="w-10 fa fa-info-circle p-2"></i>
                                </div>
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="country_id">Country:</label>
                                <select class="form-control form-select" name="country_id" id="country_id">
                                    <option disabled>Choose country</option>
                                    <?php foreach($countries as $country) { ?>
                                        <option value="<?= $country['id'] ?>"><?= $country['iso_code'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php foreach($legals as $legal) { ?>
                        <div id="legal-<?= $legal['id'] ?>" class="d-none"><?= $legal['text'] ?></div>
                    <?php } ?>

                    <hr>

                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="tracking">Redirect URL:</label>
                                <input class="form-control required" name="tracking" maxlength="191" id="tracking">
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="databowl_id">Databowl Landing ID:</label>
                                <input class="form-control" name="databowl_id" maxlength="191" id="databowl_id">
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>

                    <input type="hidden" class="d-none" name="id" id="id" value="<?= $is_copying ? $_GET["id"] : '-1' ?>">
                    <input type="hidden" class="d-none" name="action" id="action" value="<?= $is_copying ? 'copy' : 'create_new' ?>">

                    <div class="d-flex justify-content-between w-100">
                        <a class="btn btn-secondary" href="../">Go back</a>
                        <button class="btn btn-success" id="submit-button">Create</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/create-edit.js"></script>

</body>
</html>