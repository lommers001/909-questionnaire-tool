 <div class="transition-screen transition-phase-2 h-100 d-flex  <?= ($IS_MOBILE ? 'transition-mobile' : 'transition-desktop') ?>">
    <div class="transition-1">
        <div class="transition-icon"></div>
        <div class="transition-block text-white">Gegevens ontvangen</div>
    </div>
    <div class="transition-2">
        <div class="transition-icon"></div>
        <div class="transition-block">Beantwoord vragen</div>
    </div>
    <div class="transition-3">
        <div class="transition-icon"></div>
        <div class="transition-block">Claim je prijs</div>
        <img class="fall-down" src="./images/present.png">
    </div>
    <script>
        window.setTimeout(function(){
            $(".transition-phase-2 > .transition-2 .transition-block").html("Vragen beantwoord")
        } , <?= $transition_speed * 2500 ?>);
        window.setTimeout(function(){
            next(<?= $id ?>, false)
        } , <?= $transition_speed * 8000 ?>);
    </script>
</div>
</div>
</div>