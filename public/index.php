<?php
include "../private/includes.php";
$data = index();
$countries = pdo_get_all("countries");
$url = "./?" . $_SERVER['QUERY_STRING'];
if(strpos($url, "page=") === false)
    $url = $_SERVER['QUERY_STRING'] ? ($url . "&page=") : "./?page=";
else
    $url = preg_replace("/page=\d+/", "page=", $url);
$layout = isset($_COOKIE["layout"]) ? $_COOKIE["layout"] : "list";
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
        <button class="btn"><i class="fa fa-lg fa-list"></i></button>
        <button class="btn" onClick="window.location.href='legals.php';"><i class="fa fa-lg fa-tags"></i></button>
        <button class="btn"><i class="fa fa-lg fa-pencil"></i></button>
    </div>
    <div class="main-pane">
        <?php if(isset($_GET['msg'])) { if($_GET['msg'] == "fail") { ?>
            <div class="alert alert-danger">An error occured</div>
        <?php } else { ?>
            <div class="alert alert-success">Your campaign page was <?= $_GET['msg'] ?>!</div>
        <?php }} ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <a class="btn btn-dark btn-round" href="create.php">New Campaign</a>
                </span>
                <div>
                    <span>Demo User</span><i class="fa fa-lg fa-user ml-2"></i>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center border-bottom p-3">
                <div class="d-flex input-group align-items-center">
                    <div class="input-item">
                        <input class="input-text-short search-item" placeholder="#" value="<?= $_GET['id'] ?? '' ?>" id="search-id">
                        <i class="fa fa-search text-muted input-icon"></i>
                    </div>
                    <div class="input-item">
                        <input class="input-text-long search-item" placeholder="Name" value="<?= $_GET['name'] ?? '' ?>" id="search-name">
                        <i class="fa fa-search text-muted input-icon"></i>
                    </div>
                    <div class="input-item">
                        <select class="input-text-short search-item" id="search-country">
                            <option <?= isset($_GET['country_id']) && $_GET['country_id'] != '' ? '' : 'selected' ?>></option>
                            <?php foreach($countries as $country) { ?>
                                <option value="<?= $country['id'] ?>" <?= isset($_GET['country_id']) && $_GET['country_id'] == $country['id'] ? 'selected' : '' ?>><?= $country['iso_code'] ?></option>
                            <?php } ?>
                        </select>
                        <i class="fa fa-sort-desc text-dark input-icon"></i>
                    </div>
                    <i class="fa fa-lg fa-exchange"></i>
                </div>
                <div class="d-flex icon-group">
                    <i id="toggle-display-list" class="fa fa-lg fa-list-ul"></i>
                    <i id="toggle-display-block" class="fa fa-lg fa-th-large mx-2"></i>
                </div>
            </div>

            <div id="main-body" class="card-body layout-<?= $layout ?>">
                
                <?php foreach($data["items"] as $id => $item) { ?>
                    <div class="data-item border border-secondary">
                        <div class="item-specs">
                            <span class="item-id"><?= $item['id'] ?></span>
                            <?php $img_path = file_exists('storage/editor/'.$item['slug'].'/dG.jpg') ? 'storage/editor/'.$item['slug'].'/dG.jpg' : 'images/default.png'; ?>
                            <a href="editor.php?slug=<?= $item['slug'] ?>"><span class="item-image" style="background-image: url('<?= $img_path ?>')"></span></a>
                            <span class="item-title-and-country">
                            <a href="editor.php?slug=<?= $item['slug'] ?>"><span class="item-title"><?= $item["name"] ?></span></a>
                                <span class="item-country"><?= $countries[intval($item["country_id"]) - 1]["iso_code"] ?></span>
                            </span>
                        </div>
                        <span class="item-options">
                            <label class="switch">
                                <input type="checkbox" <?php if($item['active']) echo 'checked' ?> onclick="setLandingActivity(event, '<?= $item['id'] ?>')">
                                <span class="slider"></span>
                            </label>
                            <div class="options-menu">
                                <span>|</span>
                                <a href="preview.php?slug=<?= $item['slug'] ?>" target="_blank"><i class="fa fa-eye"></i></a>
                                <i class="fa fa-bar-chart"></i>
                                <i class="fa fa-ellipsis-h" onclick="openSelect('select-<?= $id ?>')"></i>
                                <div id="select-<?= $id ?>" class="dropdown-content">
                                    <a href="edit.php?id=<?= $item['id'] ?>"><div>Edit</div></a>
                                    <a href="create.php?id=<?= $item['id'] ?>"><div>Duplicate</div></a>
                                    <div onClick="confirmBeforeDelete('delete-form-<?= $item['id'] ?>', event)">Delete</div>
                                    <form style="display:inline;" action="save.php?id=<?= $item['id'] ?>" method="POST" id="delete-form-<?= $item['id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    </form>
                                </div>
                            </div>
                        </span>
                    </div>
                <?php } ?>
            </div>
            <?php if($data["count"] > 1) { ?>
                <nav>
                    <div class="d-flex">
                        <?php if($data["current"] == 0) { ?>
                            <div class="pagination-cropped previous-page"></div>
                        <?php } else { ?>
                            <div class="page-item"><a class="page-link previous-page" href="<?= $url . $data["prev"] ?>">&laquo;</a></div>
                        <?php } for($i = 0; $i < $data["count"]; $i++) { ?>
                            <div class="page-item<?= $i == $data["current"] ? " active" : "" ?>"><a class="page-link" href="<?= $url . $i ?>"><?= $i + 1 ?></a></div>
                        <?php } if($data["count"] == $data["current"] + 1) { ?>
                            <div class="pagination-cropped next-page"></div>
                        <?php } else { ?>
                            <div class="page-item"><a class="page-link next-page" href="<?= $url . $data["next"] ?>">&raquo;</a></div>
                        <?php } ?>
                    </div>
                </nav>
            <?php } ?>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/index.js"></script>

</body>
</html>