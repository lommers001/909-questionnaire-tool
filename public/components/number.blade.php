<div class="d-flex justify-content-center align-items-center form-floating">
    <?php 
        $input_id = "input_" . $id;
    ?>
    <input class="w-100 autofill ph-font" type="number" placeholder="<?= $page->placeholder ?>" id="<?= $input_id ?>">
    <label class="ph-font" for="<?= $input_id ?>"><?= $page->placeholder ?></label>
</div>