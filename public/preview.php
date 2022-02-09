<?php
    include "../private/includes.php";
	$slug= $_GET['slug'];
	$content = get_content($slug);
	$device_type = $content->device;
	$redirect = $content->redirect;
	$legal_text = $content->legal_text;
	$locales = pdo_get("locales", "country_id", $content->country, true);
	$transition_speed = $content->transition_speed;

	$START_PAGE = (isset($_GET["page"]) ? intval($_GET["page"]) : 0);
	$IS_MOBILE = ($device_type == 3);

	//Page types
	$TYPE_LOADING = 0;
	$TYPE_SPLASH = 1;
	$TYPE_OPEN = 2;
	$TYPE_NUMBER = 3;
	$TYPE_MCQ = 4;
	$TYPE_PHONE = 5;
	$TYPE_EMAIL = 6;
	$TYPE_ADDRESS = 7;
	$TYPE_LEGAL= 8;
	$TYPE_DOB = 9;
	$TYPE_END = 10;
	$TYPE_FORM_LONG = 11;
	$TYPE_FORM_SHORT_1 = 12;
	$TYPE_FORM_SHORT_2 = 15;
	$TYPE_NAME = 13;
	$TYPE_CHECKBOXES = 14;
	$TYPE_TRANSITION = 16;
	$TYPE_DROPDOWN = 17;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="https://lh3.googleusercontent.com/BaqaptRm0GO-nmr53Oql3KCq7Bfk0oYGTSUAGSRcuUY-ScGWZ3TFqfFchUK0u9UjMj37eCMU1UPpjJQ7Ko9pep6JGUV6jkXeyA=s32" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="token">

    <title>Daily Offers</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="assets/css/bootstrap_new.min.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap_new.min.js"></script>

    <style>
		<?php
			$img_dir = 'storage/editor/' . $slug . '/';
			$img_exists = false;
			$font_list = [];
			$d_width = 0;
			$d_height = 0;

			/* Variables */
			echo ":root {" .
				"--tr-anim-speed: " . $transition_speed . "s;" .
				"--tr-step-1: " . ($transition_speed - 0.5) . "s;" .
				"--tr-step-2: " . ($transition_speed  * 2 - 0.25) . "s;" .
				"--tr-step-3: " . ($transition_speed  * 3) . "s;" .
				"--tr-step-4: " . ($transition_speed  * 4 + 0.25) . "s;" .
				"--tr-step-5: " . ($transition_speed  * 5 + 0.5) . "s;" .
			"}";

			if($IS_MOBILE) echo ".hide-on-mobile { display: none }";
			echo "body { background: " . $content->style->gcolor . "}";
			if(isset($_GET["width"]) && isset($_GET["height"]) && isset($_GET["device"]) && $_GET["device"] == 'mobile'){
				$d_width = intval($_GET["width"]);
				$d_height = intval($_GET["height"]);
				$w = $d_width . "px";
				$h = $d_height . "px";
				$hw = ($d_width / 2) . "px";
				$hh = ($d_height / 2) . "px";
				echo "#app > div { position: absolute; top: calc(max(0px, 50vh - $hh)); left: calc(50vw - $hw); width: $w; height: $h; border: 1px solid #888}";
				if(isset($_GET["device"]) && $_GET["device"] == "mobile"){
					echo ".under-button{ left: calc(50vw - $hw); width: $w; bottom: calc(max(0px, 50vh - $hh)) }";
					echo ".btn-next, .btn-next-fake{ left: calc(50vw - $hw + 1rem); width: calc($w - 2rem); bottom: calc(max(1rem, 50vh - $hh + 1rem)) }";
				}
			}
			else{
				echo "#app {width: 100vw; height: 100vh}";
				echo "#app > div { position: absolute; top: 0; left: 0; width: 100%; height: 100%}";
			}

			function calcContrastColor($color){
				$value = str_replace("#", "", $color);
				$value = preg_replace('/\D/', "9", $value);
				$r = intval(substr($value,0,2));
				$g = intval(substr($value,2,2));
				$b = intval(substr($value,4));
				if ($r + $g + $b > 180)
					return "black";
				return "white";
			}
			function calcOptColor($color){
				$r = hexdec(substr($color,1,2));
				$r += (255 - $r) / 2;
				$g = hexdec(substr($color,3,2));
				$g += (255 - $g) / 2;
				$b = hexdec(substr($color,5));
				$b += (255 - $b) / 2;
				return "#" . dechex($r) . dechex($g) . dechex($b);
			}

			function getImage($img_dir, $id, $img_name, $device_type, &$img_exists, $hexColor){
				$devices = ['a', 'd', 't', 'm'];
				try {
					$img_path = $img_dir . $devices[$device_type] . $img_name[0] . '.jpg';
				} catch(\Throwable $t){
					return ".img-".$id." > div { background-image: linear-gradient(#888888,#666666) }";
				}
				list($r, $g, $b)= sscanf($hexColor, '#%2x%2x%2x');
				$gradient_possible = true;
				if(!$r || !$g || !$b){
					$gradient_possible = false;
				}
				$img_gradient = ($IS_MOBILE && $img_name[3] == 5 && $gradient_possible) ? "linear-gradient(rgba($r,$g,$b,0),rgba($r,$g,$b,1))," : "";
				if(file_exists($img_path) && $img_name[$device_type] > 0){
					$img_exists = true;
					return ".img-".$id." > div { background-image: ".$img_gradient."url('" . $img_path . "'); }";
				}
				$img_path = $img_dir . $devices[$device_type] . 'G.jpg';
				if(file_exists($img_path) && $img_name[$device_type] != -1){
					$img_exists = true;
					return ".img-".$id." > div { background-image: ".$img_gradient."url('" . $img_path . "'); }";
				}
				return ".img-".$id." > div { background-image: none }";
			}

			foreach($content->pages as $id=>$page){
				echo(getImage($img_dir, $id, $page->style->img, $device_type, $img_exists, $page->style->gcolor));

				$f_header = $page->style->header;
				if(!in_array($page->style->header[0], $font_list)) array_push($font_list, $page->style->header[0]);
				$f_par = $page->style->par;
				if(!in_array($page->style->par[0], $font_list)) array_push($font_list, $page->style->par[0]);
				$f_button = $page->style->fbutton;
				if(!in_array($page->style->fbutton[0], $font_list)) array_push($font_list, $page->style->fbutton[0]);
				if ($device_type > 1){
					//Mobile
					$f_button[2] = intval(floatval($f_button[2]) * 0.75) . "px";
					$f_par[2] = intval(floatval($f_par[2]) * 0.75) . "px";
					$f_header[2] = intval(floatval($f_header[2]) * 0.75) . "px";
				}
				else {
					//Desktop
					$f_button[2] .= "px";
					$f_par[2] .= "px";
					$f_header[2] .= "px";
				}
				if($id == 0){
					$f_answer = $page->style->fanswer;
					if(!in_array($f_answer[0], $font_list)) array_push($font_list, $f_answer[0]);
					$f_label = $page->style->flabel;
					if(!in_array($f_label[0], $font_list)) array_push($font_list, $f_label[0]);
					$f_ph = $page->style->fph;
					if(!in_array($f_ph[0], $font_list)) array_push($font_list, $f_ph[0]);
					if ($device_type > 1){
						//Mobile
						$f_answer[2] = intval(floatval($f_answer[2]) * 0.75) . "px";
						$f_label[2] = intval(floatval($f_label[2]) * 0.75) . "px";
						//$f_ph[2] = intval(floatval($f_ph[2]) * 0.75) . "px";
						$f_ph[2] .= "px";
					}
					else {
						//Desktop
						$f_answer[2] .= "px";
						$f_label[2] .= "px";
						$f_ph[2] .= "px";
					}
					echo ".opt-font { font-family: ".$f_answer[0]."; font-style: ".$f_answer[1]."; font-size: ".$f_answer[2]."; color: ".$f_answer[3]."; text-align: ".$f_answer[4]."}";
					echo ".label-font { font-family: ".$f_label[0]."; font-style: ".$f_label[1]."; font-size: ".$f_label[2]."; color: ".$f_label[3]."}";
					echo ".ph-font { font-family: ".$f_ph[0]."; font-style: ".$f_ph[1]."; font-size: ".$f_ph[2]."; color: ".$f_ph[3]."; text-align: ".$f_ph[4]."}";
				}
				
				$header_border = (count($f_header) > 5 ? $f_header[5] . " " . $f_header[6] : 'none');
				echo ".header-".$id.",.header-".$id.">*{ font-family: ".$f_header[0].($f_header[1] == 'bold' ? "; font-weight:bold" : "; font-style: ".$f_header[1])."; font-size: ".$f_header[2]."; color: ".$f_header[3]."; text-align: ".$f_header[4]."; border-bottom: ".$header_border."}";
				echo ".par-".$id.",.par-".$id.">*{font-family: ".$f_par[0].($f_par[1] == 'bold' ? "; font-weight:bold" : "; font-style: ".$f_par[1])."; font-size: ".$f_par[2]."; color: ".$f_par[3]."; text-align: ".$f_par[4]."}";
				echo ".fontb-".$id." {font-family: ".$f_button[0].($f_button[1] == 'bold' ? "; font-weight:bold" : "; font-style: ".$f_button[1])."; font-size: ".$f_button[2]."; color: ".$f_button[3]."; text-align: ".$f_button[4]."}";
				echo ".btn-".$id." {background-color: ".$page->style->bcolor."; border-radius: ".$page->style->bround."px; color: ".calcContrastColor($page->style->bcolor)."; }";
				echo ".btn-".$id.":hover {background-color: ".calcContrastColor($page->style->bcolor)."; color: ".$page->style->bcolor."; border: 1px solid black; }";
				echo ".opt-".$id." {background-color: ".calcOptColor($page->style->acolor)."; border: 1px solid ".$page->style->acolor."; }";
				echo ".bg-".$id." {background: ".$page->style->gcolor."}";
				if(strlen($page->style->gcolor) > 8){
					$ub_gcolor = explode(",", $page->style->gcolor)[1];
					echo ".ubg-".$id." {background: ".$ub_gcolor."; opacity: .5 !important}";
				} else {
					echo ".ubg-".$id." {background: ".$page->style->gcolor."}";
				}
				
				if($page->type == 0){
					echo "#progressbar { background-color: ".$page->style->acolor."}";
				}
			}
		?>
    </style>
	<?php
		foreach($font_list as $font){
			if(!in_array($font, ["Arial", "Georgia", "Monospace"]))
				echo '<link href="https://fonts.googleapis.com/css?family='.$font.'" rel="stylesheet">';
		}
	?>

    <script>
        window.amountOfPages = <?= count($content->pages) ?>;
		window.redirectURL = <?= "'".$redirect."'" ?>;
		window.slug = <?= "'".$slug."'" ?>;
		window.currentPage = <?= (isset($_GET["page"]) ? $_GET["page"] : '0') ?>;
        window.errorMessages = {
            <?php 
				foreach($locales as $error){
					echo '' . $error["title"] . ':"' . preg_replace('/\r?\n|\r/', '<br>', $error["content"]) . '",';
				}
			?>
        };
		window.fieldTypes = {
			loading: <?= $TYPE_LOADING ?>,
			splashScreen: <?= $TYPE_SPLASH ?>,
			open: <?= $TYPE_OPEN ?>,
			number: <?= $TYPE_NUMBER ?>,
			multipleChoice: <?= $TYPE_MCQ ?>,
			phone: <?= $TYPE_PHONE ?>,
			email: <?= $TYPE_EMAIL ?>,
			address: <?= $TYPE_ADDRESS ?>,
			legal: <?= $TYPE_LEGAL ?>,
			dateOfBirth: <?= $TYPE_DOB ?>,
			endScreen: <?= $TYPE_END ?>,
			name: <?= $TYPE_NAME ?>,
			checkboxes: <?= $TYPE_CHECKBOXES ?>,
			formShort1: <?= $TYPE_FORM_SHORT_1 ?>,
			formShort2: <?= $TYPE_FORM_SHORT_2 ?>,
			formLong: <?= $TYPE_FORM_LONG ?>,
			transition: <?= $TYPE_TRANSITION ?>,
			dropdown: <?= $TYPE_DROPDOWN ?>,
			numberInForm: 50,
			textInForm: 51,
			phoneInForm: 55,
			emailInForm: 56,
			genderInForm: 0,
			noValidation: -1
		};
    </script>

</head>

<body>
<div id="app">
	<?php if($d_width > 0 && $d_height > 0) { ?>
		<!-- For emulating mobile -->
		<nav>
			<span>Width: <input id="dim-w" class="dimensions" value="<?= $d_width ?>"></span> &ensp; 
			<button id="dim-swap" class="btn btn-light">&harr;</button> &ensp; 
			<span>Height: <input id="dim-h" class="dimensions" value="<?= $d_height ?>"></span> &ensp;
			<button id="dev-swap" class="btn btn-light" onclick="swapDevice('<?=$IS_MOBILE ? 'desktop' : 'mobile' ?>')">Switch to <?= $IS_MOBILE ? 'desktop' : 'mobile' ?></button>
		</nav>
	<?php } else if(isset($_GET['device'])) { ?>
		<button id="dev-swap" class="btn btn-light z-above-all" onclick="swapDevice('mobile')">Switch to mobile</button>
	<?php } ?>
	<!-- The pages -->
	<?php foreach($content->pages as $id=>$page) {
		$d_image = $page->style->img[$device_type];
		$g_image = $content->style->img[$device_type];
		$img_set = ($d_image == -1 || ($d_image == 0 && $g_image == 0) || $page->type == $TYPE_TRANSITION) ? 'image-none' : '';
		if($d_image == 0 && $g_image != 0) $d_image = $g_image;
		$img_style = $d_image == 4 ? 'image-thumbnail' : ($d_image % 2 == 0 && $d_image > 0 ? 'image-poster' : 'image-border');
		$img_layout = $device_type == 1 ? ($d_image < 3 ? 'image-left' : 'image-top') : 'image-top';
		$flex = $device_type == 1 ? ($d_image < 3 ? ' layout-row ' : ' layout-column ') : ' layout-column ';
		$is_debug = isset($_GET["device"]) && $_GET["device"] == 'mobile' ? '3' : '1';
		$title = htmlspecialchars_decode($page->title);
		$paragraph = htmlspecialchars_decode($page->text);
		$content_layout = ($IS_MOBILE && $page->type != $TYPE_MCQ && $page->type != $TYPE_CHECKBOXES && $page->type != $TYPE_LOADING && $page->type != $TYPE_TRANSITION) ? "space-between" : "space-start";
		if($d_image > 0 && $page->type != $TYPE_TRANSITION)
			$img_set = ($IS_MOBILE && $d_image > 0 && $page->type == $TYPE_FORM_LONG) ? "image-cropped" : "";
	?>
		<div id="page-container-<?=$id?>" class="page bg-<?= $id.$flex.($id == $START_PAGE ? 'd-flex' : ' d-none') ?>">
			<div class="page-<?=$id?> img-<?=$id?> <?=$img_layout?> <?=$img_set?> image-container <?=$img_style?>">
				<div class="image-center"></div>
			</div>
			<?php $c_style = $img_set == '' ? ($device_type == 1 && $d_image < 3 ? 'content-right' : 'content-bottom') : 'content-full'; ?>
			<div class="page-<?=$id?> text-center <?=$c_style?>" id="page-<?=$id?>">
				<?php if($page->type == $TYPE_TRANSITION){ 
					include ("components/transition_" . (isset($_GET["page"]) ? '2' : '1') . ".blade.php");
					continue;
				} ?>

				<div class="content-container  <?=$content_layout?>">
					<?php if ($page->type != $TYPE_LEGAL && $page->type != $TYPE_TRANSITION) { ?>
						<div class="header-and-par">
							<div class="header-<?=$id?> header"> <?= $title ?> </div>
							<div class="par-<?=$id?> paragraph"> <?= $paragraph ?> </div>
						</div>
					<?php } ?>

					<!-- Input fields based on page type -->
					<div class="input-area">
						<?php
						if ($page->type == $TYPE_OPEN) include "components/open.blade.php";
						else if ($page->type == $TYPE_NUMBER) include "components/number.blade.php";
						else if ($page->type == $TYPE_MCQ) include "components/multiple_choice.blade.php";
						else if ($page->type == $TYPE_DROPDOWN) include "components/dropdown.blade.php";
						else if ($page->type == $TYPE_PHONE) include "components/phone.blade.php";
						else if ($page->type == $TYPE_EMAIL) include "components/email.blade.php";
						else if ($page->type == $TYPE_ADDRESS) include "components/address.blade.php";
						else if ($page->type == $TYPE_LEGAL) include "components/legal_notice.blade.php";
						else if ($page->type == $TYPE_DOB) include "components/dob.blade.php";
						else if ($page->type == $TYPE_LOADING) include "components/loading.blade.php";
						else if ($page->type == $TYPE_FORM_LONG) include "components/form.blade.php";
						else if ($page->type == $TYPE_FORM_SHORT_1) include "components/form_short_1.blade.php";
						else if ($page->type == $TYPE_FORM_SHORT_2) include "components/form_short_2.blade.php";
						else if ($page->type == $TYPE_NAME) include "components/name.blade.php";
						else if ($page->type == $TYPE_CHECKBOXES) include "components/multiple_list.blade.php";
						?>
						<br>
						<p class="error-message"></p>
						<br>

						<?php
							$extra_classes = "";
							$is_final = ($id < count($content->pages) - 1) ? 'false' : 'true';
							if ($IS_MOBILE && ($content->legal_at == ($id + 1) || $id == 0 && $content->legal_at == 0))
								$onclick = "validateOrNext('".$page->type."', ".$is_final.", ".$id.", ".$page->id.", true)";
							else if ($page->type == 8)
								$onclick = $IS_MOBILE ? "" : "confirmLegalDesk()";
							else
								$onclick = "validateOrNext('".$page->type."', ".$is_final.", ".$id.", ".$page->id.")";
							
							$extra_classes = in_array($page->type, [$TYPE_SPLASH, $TYPE_LEGAL, $TYPE_END]) ? "never-hide" : "d-none";
							
							$btn_text = $page->type == 8 ? $content->legal_text['c2a'] : $page->c2a;
						?>

						<button class="btn btn-next fontb-<?=$id?> btn-<?=$id?> <?=$extra_classes?> btn-dev-<?=$is_debug?>" onClick="<?=$onclick?>"> <?= $btn_text ?> </button>
						<?php if (!in_array($page->type, [$TYPE_LOADING, $TYPE_SPLASH, $TYPE_MCQ, $TYPE_LEGAL, $TYPE_END, $TYPE_TRANSITION, $TYPE_DROPDOWN])) { ?>
							<button class="btn btn-<?=$id?> fontb-<?=$id?> btn-next-fake btn-dev-<?=$is_debug?>" disabled> <?= $btn_text ?> </button>
						<?php } ?>
						<div class="ubg-<?=$id?> under-button btn-dev-<?=$is_debug?>"></div>
						<br>
						<div class="spacer"></div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
    <?php if($IS_MOBILE) { ?>
        <button id="showLegalButton" class="d-none" data-bs-toggle="modal" data-bs-target="#legalModal"></button>
    <?php include "components/legal.blade.php"; } ?>
</div>

<script src="/assets/js/form.js"></script>

</body>
</html>