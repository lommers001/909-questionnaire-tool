<div class="form-container">
	<?php 
        $input_id = "input_" . $id;
    ?>
    <!-- Name -->
    <input class="d-none xform" type="radio" name="r1" value="1" id="r1s">
    <label for="r1s" class="form-label label-font mt-4">Naam</label>
    <div class="form-block">
        <div class="w-100 text-start">
            <input type="radio" class="maintain jump-to-input" id="gender-mx" name="gender" value="De heer">
            <label for="gender-mx">De heer</label>&ensp;
            <input type="radio" class="maintain jump-to-input" id="gender-fx" name="gender" value="Mevrouw">
            <label for="gender-fx">Mevrouw</label>
        </div>
        <div class="d-flex w-100">
            <div class="w-50 section-block form-floating">
                <input class="w-100 ph-font" id="fi1s" placeholder="Voornaam" onkeyup="xfocusOnKeyUp(event, '#fi2s')">
                <label class="ph-font" for="fi1s">Voornaam</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-50 section-block form-floating">
                <input class="w-100 ph-font" id="fi2s" placeholder="Achternaam" onkeyup="xfocusOnKeyUp(event, '#r2s', '#fi3s')">
                <label class="ph-font" for="fi2s">Achternaam</label>
            </div>
        </div>
    </div>
    <!-- E-mail -->
    <input class="d-none xform" type="radio" name="r2" value="2" id="r2s">
    <label for="r2s" class="form-label label-font muted" onclick="xfocus('#fi3s')">E-mail</label>
    <div class="form-block">
        <div class="w-100 form-floating">
            <input type="email" class="w-100 ph-font" id="fi3s" placeholder="E-mail" onkeyup="xfocusOnKeyUp(event, '#r3s', '#fi4s')">
            <label class="ph-font" for="fi3s">E-mail</label>
        </div>
    </div>
    <!-- DOB -->
    <input class="d-none xform" type="radio" name="r3" value="3" id="r3s">
    <label for="r3s" class="form-label label-font muted" onclick="xfocus('#fi4s')">Geboortedatum</label>
    <div class="form-block">
        <div class="d-flex w-100">
            <div class="w-25 section-block form-floating">
                <input type="number" class="w-100 jump-to-next-form date-form ph-font" id="fi4s" placeholder="Dag">
                <label class="ph-font" for="fi4">Dag</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-25 section-block form-floating">
                <input type="number" class="w-100 jump-to-next-form date-form ph-font" id="fi5s" placeholder="Maand">
                <label class="ph-font" for="fi5">Maand</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-50 section-block form-floating">
                <input type="number" class="w-100 ph-font date-form" id="fi6s" placeholder="Jaar" onkeyup="xfocusOnKeyUp(event, '.btn-<?=$id?>.btn-next')">
                <label class="ph-font" for="fi6">Jaar</label>
            </div>
        </div>
    </div>
</div>