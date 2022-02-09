<div class="d-flex flex-column container-md align-items-center option-list">
    <?php 
        $name = 'question_' . $page->id;
        $total_choices = count($page->choices);
    ?>
    <?php foreach ($page->choices as $idc => $choice) { ?>        
        <div class="row w-100 m-1">
			<?php 
                $input_id = "input-" . $choice. "_" . $id;
                $font_answer = (isset($page->style->fanswer)) ? "fonta-" . $id : "font-" . $id;
            ?>
            <input class="checkbox-list-item" type='checkbox' name="<?= $name ?>" id="<?= $input_id ?>" value="<?= $choice ?>">
            <label for="<?= $input_id ?>" class="<?=$font_answer?> option multi-select opt-font opt-<?=$id?> w-100 mb-0 p-1" onclick="setMultiSelectAnswer(event, <?=$id?>, <?=$idc?>, <?=$total_choices?>)">
                <?= $choice ?>
            </label>
        </div>
    <?php } ?>
</div>