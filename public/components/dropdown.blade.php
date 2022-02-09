<div class="d-flex flex-column container-md align-items-center option-list">
    <?php $name = 'question_' . $page->id; ?>
    <div class="dropdown my-2" onclick="showDropdownList(this)"><?= $page->placeholder ?></div>
    <div class="dropdown-option-container list-hide">
        <?php foreach ($page->choices as $choice) { ?>         
            <div class="row w-100 m-0">
                <?php 
                    $input_id = "input-" . $choice. "_" . $id;
                    $font_answer = (isset($page->style->fanswer)) ? "fonta-" . $id : "font-" . $id; 
                ?>
                <input class="autofill d-none" type='radio' name="<?= $name ?>" id="<?= $input_id ?>" value="<?= $choice ?>">
                <label for="<?= $input_id ?>" class="<?=$font_answer?> dropdown-option opt-font w-100 m-0 py-2" data-id="<?=$id?>"><?= $choice ?></label>
            </div>
        <?php } ?>
    </div>
</div>