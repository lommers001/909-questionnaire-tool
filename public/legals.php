<?php
include "../private/includes.php";
$data = pdo_get_all("legals");
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
        <?php if(isset($_GET['msg'])) { if($_GET['msg'] == "fail") { ?>
            <div class="alert alert-danger">An error occured</div>
        <?php } else { ?>
            <div class="alert alert-success">Your legal disclaimer was <?= $_GET['msg'] ?>!</div>
        <?php }} ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <a class="btn btn-dark btn-round" href="legal.php">New legal disclaimer</a>
                </span>
                <div>
                    <span>Demo User</span><i class="fa fa-lg fa-user ml-2"></i>
                </div>
            </div>

            <div id="main-body" class="card-body layout-list">
                
                <?php foreach($data as $id => $item) { ?>
                    <div class="data-item border border-secondary">
                        <div class="item-specs">
                            <span class="item-id simple"><?= $item['id'] ?></span>
                            <span class="item-title"><?= $item["name"] ?></span>
                            <?php
                                $text = strip_tags(str_replace("<br>", " ", $item["text"]));
                                if(strlen($text) > 64) $text = substr($text, 0, 61) . "...";
                            ?>
                            <span class="item-desc"><?= $text ?></span>
                        </div>
                        <span class="item-options simple">
                            <div class="options-menu">
                                <a href="legal.php?id=<?= $item['id'] ?>"><i class="fa fa-edit"></i></a>
                            </div>
                        </span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/index.js"></script>

</body>
</html>