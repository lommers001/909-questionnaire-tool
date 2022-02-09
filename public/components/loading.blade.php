 <div class="d-flex align-items-center flex-column">
    <div class="progress w-100">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" id="progressbar">0%</div>
    </div>
    <div class="mt-4">
        <?php foreach( $page->choices as $idx=>$message ) { ?>
            <div class="<?=$idx == 0 ? 'show' : ''?> message-<?=$idx?> opt-font fade loading-screen-message"><?= $message ?></div>
        <?php } ?>
    </div>
    <script>
        function fillLoadingBar(){
            if (window.currentPage != <?= $id ?>)
                return window.setTimeout(fillLoadingBar , 500);
            
            window.loadingBarWidth += Math.ceil(Math.random() * 5);
            if (window.loadingBarWidth > 100){
                window.loadingBar.style.width = "100%";
                window.loadingBar.innerHTML = "100%";
                return window.setTimeout(() => {
                    if(<?= ($id < count($content->pages) - 1) ? 'false' : 'true' ?>)
                        this.saveAnswers();
                    else
                        this.next(window.id + 1);
                }, 587);
            }
            window.loadingBar.style.width = window.loadingBarWidth + "%";
            window.loadingBar.innerHTML = window.loadingBarWidth + "%";
            if (<?=count($page->choices) > 0 ? 'true' : 'false'?>){
                if (window.loadingBarWidth > (100/<?=count($page->choices)?>) * (loadingMessageId + 1)){
                    $(".message-" + loadingMessageId).removeClass('show');
                    loadingMessageId += 1;
                    $(".message-" + loadingMessageId).addClass('show');
                }
            }
            return window.setTimeout(fillLoadingBar , Math.ceil(Math.random() * 80) + 80);
        }
        window.setTimeout(fillLoadingBar , 500);
        var loadingMessageId = 0;
    </script>
</div>