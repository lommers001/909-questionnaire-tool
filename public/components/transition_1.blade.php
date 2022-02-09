 <div class="transition-screen h-100 d-flex <?= ($IS_MOBILE ? 'transition-mobile' : 'transition-desktop') ?>">
    <div class="transition-1">
        <div class="transition-icon"></div>
        <div class="transition-block">Gegevens ingevuld</div>
    </div>
    <div class="transition-2">
        <div class="transition-icon"></div>
        <div class="transition-block">Beantwoord vragen</div>
        <img class="fall-down" src="./images/write.png">
    </div>
    <div class="transition-3">
        <div class="transition-icon"></div>
        <div class="transition-block">Claim je prijs</div>
    </div>
    <script>
        function playTransition(){
            if (window.currentPage != <?= $id ?>){
                window.setTimeout(playTransition , 500);
                return;
            }

            $(".transition-screen").addClass("transition-phase-1");
            
            window.setTimeout(function(){
                $(".transition-phase-1 > .transition-1 .transition-block").html("Gegevens ontvangen")
            } , <?= $transition_speed * 4500 ?>);
            window.setTimeout(function(){
                window.location = window.location.href + "&page=<?= $id ?>"
            } , <?= $transition_speed * 8500 ?>);
        }
        window.setTimeout(playTransition , 1000);
    </script>
</div>
</div>
</div>