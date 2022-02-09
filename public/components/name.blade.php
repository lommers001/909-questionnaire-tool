<div class="form-container">
	<?php 
        $input_id = "input_" . $id;
        $expl_placeholder = $page->placeholder;
        if (count($expl_placeholder) < 2){
            $expl_placeholder = ["Voorrnaam", "Achternaam"];
        }
        $expl_label = $page->label;
        if (count($expl_label) < 2){
            $expl_label = ["De heer", "Mevrouw"];
        }
    ?>
    <div class="w-100 text-start">
        <input type="radio" class="maintain namefill jump-to-input" id="gender-ms" name="gender" value="<?= $expl_label[0] ?>">
        <label class="ph-font" for="gender-ms"><?= $expl_label[0] ?></label>&ensp;
        <input type="radio" class="maintain namefill jump-to-input" id="gender-fs" name="gender" value="<?= $expl_label[1] ?>">
        <label class="ph-font" for="gender-fs"><?= $expl_label[1] ?></label>
    </div>
    <div class="d-flex w-100">
        <div class="w-50 section-block form-floating">
            <input class="w-100 namefill jump-to-next ph-font" placeholder="<?= $expl_placeholder[0] ?>">
            <label class="ph-font" for="fi1"><?= $expl_placeholder[0] ?></label>
        </div>
        <div class="form-gap"></div>
        <div class="w-50 section-block form-floating">
            <input class="w-100 namefill ph-font" placeholder="<?= $expl_placeholder[1] ?>">
            <label class="ph-font" for="fi2"><?= $expl_placeholder[1] ?></label>
        </div>
    </div>
</div>