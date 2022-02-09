<div class="d-flex flex-column container-md align-items-center option-list">
    <?php $name = 'question_' . $page->id; ?>
    <?php foreach ($page->choices as $choice) { ?>         
        <div class="row w-100 m-1">
			<?php 
                $input_id = "input-" . $choice. "_" . $id;
                $font_answer = (isset($page->style->fanswer)) ? "fonta-" . $id : "font-" . $id; 
            ?>
            <input class="autofill d-none" type='radio' name="<?= $name ?>" id="<?= $input_id ?>" value="<?= $choice ?>">
            <label for="<?= $input_id ?>" class="<?=$font_answer?> option opt-font opt-<?=$id?> w-100 mb-0 p-1" onclick="setMCAnswer('.btn-<?=$id?>')"><?= $choice ?></label>
        </div>
    <?php } ?>
</div>