<?php
    include "../private/includes.php";
	$slug = $_GET['slug'];
	$landing = get_content_editor($slug);
    if($landing==null){
		echo "NIETS GEVONDEN";
        exit();
	}
    $content = $landing["content"];
    $locales = $landing["locales"];
    $title = $landing["title"];
    $legal = $landing["legal"];
    $legal_c2a = $landing["legal_c2a"];
    $errors = [];
    foreach($locales as $locale){
        //Replace line breaks with spaces
        array_push($errors, preg_replace('/\r?\n|\r/', ' ', $locale["content"]));
    }
    $locales_string = implode("¿", $errors);
	$fonts = [
		"Roboto","Open Sans","Merriweather","Concert One","Lato","Montserrat","Roboto Slab","Playfair Display","Roboto Mono","Eczar","Gravitas One", "Abril Fatface",
		"Eagle Lake","New Rocker","Pirata One","Port Lligat Slab","Squada One","Katibeh","Glass Antiqua","Cormorant Upright","Cinzel","Fugaz One"
	];
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
    <link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/editor.css" rel="stylesheet">

    <!-- Pre-load fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <?php
        foreach($fonts as $font){
			$font_id = str_replace(" ", "+", $font);
			echo '<link href="https://fonts.googleapis.com/css?family='.$font_id.'" rel="stylesheet">';
		}
    ?>

    <style>
        /* Fonts */
		<?php
		foreach($fonts as $font){
			$font_id = str_replace(" ", "-", $font);
			echo '#font-select[data-chosen="'.$font.'"], #'.$font_id.' {font-family: "'.$font.'"}';
		}
		?>
    </style>

</head>

<body>
<div id="app">
    <div class="editor-float preview-float">
        <div class="row">
            <div class="col d-flex flex-row-reverse justify-content-center">
                <input type="radio" class="d-none r-hidden" name="device" id="device-1" value="1" checked>
                <label for="device-1" class="mx-2 clickable" @click="$refs.editor.setPreviewDimensions(1024, 720)">
                    <svg class="svg-hover" width="66px" height="42px" viewBox="0 0 88 56" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>Desktop</title>
                        <g id="Briefing-Prelander-Builder" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <image id="Bitmap" x="0" y="0" width="87.4146341" height="56" xlink:href="data:image/png;base64,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"></image>
                        </g>
                    </svg>
                </label>
                <input type="radio" class="d-none r-hidden" name="device" id="device-2" value="2">
                <label for="device-2" class="ml-2 mr-1 clickable" @click="$refs.editor.setPreviewDimensions(600, 720)"> 
                    <svg class="svg-hover" width="32px" height="42px" viewBox="0 0 43 56" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>Tablet</title>
                        <g id="Briefing-Prelander-Builder" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <image id="Bitmap" x="0" y="0" width="42.875" height="56" xlink:href="data:image/png;base64,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"></image>
                        </g>
                    </svg>
                </label>
                <input type="radio" class="d-none r-hidden" name="device" id="device-3" value="3">
                <label for="device-3" class="mx-2 clickable" @click="$refs.editor.setPreviewDimensions(400, 720)">
                    <svg class="svg-hover" width="22px" height="42px" viewBox="0 0 30 56" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>Mobile</title>
                        <g id="Briefing-Prelander-Builder" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <image id="Bitmap" x="0" y="0" width="28.875" height="56" xlink:href="data:image/png;base64,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"></image>
                        </g>
                    </svg>
                </label>
                <input type="checkbox" class="d-none r-hidden" name="device-rot" id="device-r" value="1" disabled>
                <label for="device-r"><i class="fa fa-refresh mt-3 mx-1" @click="$refs.editor.setPreviewDimensions(-1, -1)"></i></label>
            </div>
        </div>
    </div>
    <main class="container-fluid h-100 px-3">
    	<editor v-bind:slug="'<?= $slug ?>'" v-bind:content="'<?= $content ?>'" v-bind:errors="'<?= $locales_string ?>'" v-bind:legal="'<?= $legal ?>'" v-bind:legalc2a="'<?= $legal_c2a ?>'" ref="editor"></editor>
    </main>
    <div id="save-alert" class="editor-float save-alert save-float"></div>
    <div class="editor-float save-float">
        <div class="row">
            <div class="col d-flex flex-row align-items-center">
                <button id="save-button" class="btn btn-sm btn-light border border-dark" @click="$refs.editor.save($event)">&nbsp;Save&nbsp;</button>
                <div class="ml-3">Preview:</div>
                <a class="ml-2" target="_blank" href="../preview.php?slug=<?= $slug ?>&device=mobile&width=400&height=720"> <i class="fa fa-lg fa-mobile"></i> </a>
                <a class="ml-2" target="_blank" href="../preview.php?slug=<?= $slug ?>&device=mobile&width=600&height=720"> <i class="fa fa-lg fa-tablet"></i> </a>
                <a class="ml-2" target="_blank" href="../preview.php?slug=<?= $slug ?>&device=desktop&width=1024&height=720"> <i class="fa fa-lg fa-desktop"></i> </a>
            </div>
        </div>
    </div>
    <div id="name-of-page-being-edited">Editing:&nbsp;<?= $title ?></div>
    <div class="modal fade" id="localeModal" tabindex="-1" role="dialog" aria-labelledby="localeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="localeModalLabel">Locales</h5>
                    <button class="btn btn-primary mx-2" data-dismiss="modal" @click="$refs.editor.showErrors = true">Show</button>
                    <button class="btn btn-secondary mx-2" data-dismiss="modal" @click="$refs.editor.showErrors = false">Hide</button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                    <?php foreach($locales as $locale) { ?>
                        <div><?= ucfirst(str_replace("_", " ", $locale['title'])) ?></div>
                        <textarea class="maintain w-100 h-auto"><?= $locale['content'] ?></textarea>
                    <?php } ?>
                    </div>
                </div>
                <div class="modal-footer w-100">
                    <button class="btn btn-success" data-dismiss="modal" @click="$refs.editor.saveLocales()">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="xidModal" tabindex="-1" role="dialog" aria-labelledby="xidModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="xidModalLabel">Integration</h5>
                </div>
                <div class="modal-body d-flex" id="xid-fields">
                </div>
                <div class="modal-footer w-100">
                    <button class="btn btn-success" data-dismiss="modal" @click="$refs.editor.saveXids()">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Plugins and scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/editor.js"></script>
<script src="assets/js/are-you-sure.js"></script>
<script src="assets/js/richtext.min.js"></script>
<script src="assets/js/color-picker.js"></script>

</body>
</html>