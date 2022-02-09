<div class="modal fade" id="legalModal" tabindex="-1" role="dialog" aria-labelledby="legalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <?php $id = $content->legal_at ?>
            <div class="modal-header">
                <h3 id="legalModalLabel"><?= $content->legal_text['title'] ?></h3>
            </div>
            <div class="modal-body legal-modal-body container">
                <div class="par-<?=$id?> paragraph">
                    <?= str_replace("\n", '<br>', $content->legal_text['text']) ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-<?=$id?> fontb-<?=$id?> w-100" data-bs-dismiss="modal" onClick="confirmLegalMob()"><?=$content->legal_text['c2a']?></button>
            </div>
        </div>
    </div>
</div>