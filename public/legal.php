<?php
include "../private/includes.php";
$is_editing = false;
if(isset($_GET['id'])){
    $legal = pdo_get("legals", "id", $_GET["id"]);
    $is_editing = true;
}
else {
    $legal = ["name" => "", "text" => "", "title" => "", "c2a" => ""];
}
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
    <link href="assets/trumbowyg/ui/trumbowyg.min.css" rel="stylesheet">

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
            <div class="card-header"><?= $is_editing ? 'Edit' : 'Create' ?> legal disclaimer</div>
            <div class="card-body">
                <form action="save.php" method="post">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control required" name="name" id="name" value="<?= $legal['name'] ?>">
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="name">Title:</label>
                                <input type="text" class="form-control required" name="title" id="title" value="<?= $legal['title'] ?>">
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="legaltext">Legal text</label>
                                <textarea id="trumbo-textarea" class="form-control" name="legaltext" id="legaltext"><?= $legal["text"] ?></textarea>
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="c2a">Consent button text:</label>
                                <input type="text" class="form-control required" name="c2a" id="c2a" value="<?= $legal['c2a'] ?>">
                                <div class="invalid-feedback">
                                    Field cannot be empty
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>

                    <input type="hidden" class="d-none" name="id" id="id" value="<?= $is_editing ? $_GET["id"] : '' ?>">
                    <input type="hidden" class="d-none" name="action" id="action" value="<?= $is_editing ? 'update' : 'create_new' ?>">

                    <div class="d-flex justify-content-between w-100">
                        <a class="btn btn-secondary" href="legals.php">Go back</a>
                        <button class="btn btn-success" id="submit-button"><?= $is_editing ? 'Save' : 'Create' ?></button>
                    </div>

                </form>
            </div>
        </div>
    </div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="assets/trumbowyg/trumbowyg.js"></script>
<script>
    $(document).ready(function () {
        $('textarea').trumbowyg();
    });
</script>
<script src="assets/js/trumbo.js"></script>

</body>
</html>