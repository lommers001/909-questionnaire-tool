<div class="form-container">
	<?php 
        $input_id = "input_" . $id;
    ?>
    <!-- Name -->
    <input class="d-none xform" type="radio" name="r1" value="1" id="r1">
    <label for="r1" class="form-label label-font mt-4">Naam</label>
    <div class="form-block">
        <div class="w-100 text-start">
            <input type="radio" class="maintain jump-to-input" id="gender-m" name="gender" value="De heer">
            <label class="ph-font" for="gender-m">De heer</label>&ensp;
            <input type="radio" class="maintain jump-to-input" id="gender-f" name="gender" value="Mevrouw">
            <label class="ph-font" for="gender-f">Mevrouw</label>
        </div>
        <div class="d-flex w-100">
            <div class="w-50 section-block form-floating">
                <input class="w-100 ph-font" id="fi1" placeholder="Voornaam" onkeyup="xfocusOnKeyUp(event, '#fi2')">
                <label class="ph-font" for="fi1">Voornaam</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-50 section-block form-floating">
                <input class="w-100 ph-font" id="fi2" placeholder="Achternaam" onkeyup="xfocusOnKeyUp(event, '#r2', '#fi3')">
                <label class="ph-font" for="fi2">Achternaam</label>
            </div>
        </div>
    </div>
    <!-- E-mail -->
    <input class="d-none xform" type="radio" name="r2" value="2" id="r2">
    <label for="r2" class="form-label label-font muted" onclick="xfocus('#fi3')">E-mail</label>
    <div class="form-block">
        <div class="w-100 form-floating">
            <input type="email" class="w-100 ph-font" id="fi3" placeholder="E-mail" onkeyup="xfocusOnKeyUp(event, '#r3', '#fi4')">
            <label class="ph-font" for="fi3">E-mail</label>
        </div>
    </div>
    <!-- DOB -->
    <input class="d-none xform" type="radio" name="r3" value="3" id="r3">
    <label for="r3" class="form-label label-font muted" onclick="xfocus('#fi4')">Geboortedatum</label>
    <div class="form-block">
        <div class="d-flex w-100">
            <div class="w-25 section-block form-floating">
                <input type="number" class="w-100 jump-to-next-form date-form ph-font" id="fi4" placeholder="Dag">
                <label class="ph-font" for="fi4">Dag</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-25 section-block form-floating">
                <input type="number" class="w-100 jump-to-next-form date-form ph-font" id="fi5" placeholder="Maand">
                <label class="ph-font" for="fi5">Maand</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-50 section-block form-floating">
                <input type="number" class="w-100 ph-font date-form" id="fi6" placeholder="Jaar" onkeyup="xfocusOnKeyUp(event, '#r4', '#fi7')">
                <label class="ph-font" for="fi6">Jaar</label>
            </div>
        </div>
    </div>
    <!-- Adress -->
    <input class="d-none xform" type="radio" name="r4" value="4" id="r4">
    <label for="r4" class="form-label label-font muted" onclick="xfocus('#fi7')">Adres</label>
    <div class="form-block">
        <div class="d-flex w-100">
            <div class="w-50 section-block form-floating">
                <input class="w-100 ph-font" id="fi7" placeholder="Postcode" onkeyup="xfocusOnKeyUp(event, '#fi8')" onfocusout="checkAddressForm()">
                <label class="ph-font" for="fi7">Postcode</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-25 section-block form-floating">
                <input type="number" class="w-100 ph-font" id="fi8" placeholder="Huisnr." onkeyup="xfocusOnKeyUp(event, '#fi9')" onfocusout="checkAddressForm()">
                <label class="ph-font" for="fi8">Huisnr.</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-25 section-block form-floating">
                <input class="w-100 ph-font" id="fi9" placeholder="Toev." onkeyup="xfocusOnKeyUp(event, '#r5', '#fi10')" onfocusout="checkAddressForm()">
                <label class="ph-font" for="fi9">Toev.</label>
            </div>
        </div>
        <p id="location-finder-form" class="text-start mt-2 ms-2"><br></p>
        <input type="hidden" id="fi11">
    </div>
    <!-- Phone -->
    <input class="d-none xform" type="radio" name="r5" value="5" id="r5">
    <label for="r5" class="form-label label-font muted" onclick="xfocus('#fi10')">Telefoonnummer</label>
    <div class="form-block">
        <div class="w-100 form-floating">
            <input type="tel" class="w-100 ph-font" id="fi10" placeholder="Telefoonnummer" onkeyup="xfocusOnKeyUp(event, '.btn-<?=$id?>.btn-next')">
            <label class="ph-font" for="fi10">Telefoonnummer</label>
        </div>
    </div>

    <script>
		function checkAddressForm() {
			window.setTimeout(() => {
				let postalCode = $("#fi7");
				let houseNumber = $("#fi8");
				let addition = $("#fi9");
				let hasFocus = postalCode.is(":focus") || houseNumber.is(":focus") || addition.is(":focus");
				if (postalCode.val() != "" && houseNumber.val() != "" && !hasFocus){
					let postalCodeVal = postalCode.val().replaceAll(' ', '');
					if (!(/^[0-9]{4}[a-zA-Z]{2}$/.test(postalCodeVal))){
						$('.error-message').css("display", "block");
						$('.error-message').html(window.errorMessages.invalid_postal_code);
						return;
					}
					$('.error-message').css("display", "none");
					this.ajax_addressCheckerForm(postalCodeVal, houseNumber.val(), addition.val());
				}
			}, 50);
		}

		function ajax_addressCheckerForm(postalCode, houseNumber, addition) {
			let action = window.location.origin + "/address.php";
			let additionVal = addition == "" ? null : addition;

			$.ajax({
                url: action,
                type: 'POST',
                dataType: "json",
                data: {
                    post_code: postalCode,
                    house_number: houseNumber,
					h_addition: additionVal
                },
                success: function(data) 
				{
					if (data === 400 || data == 404){
                        showError(window.errorMessages.invalid_address);
						//$('.error-message').css("display", "block");
						//$('.error-message').html(window.errorMessages.invalid_address);
						return;
					}
					if (typeof data == "string")
						data = JSON.parse(data);
					// Gather results
					var street = data.street;
					var city = data.city;

					// Write results to some fields
					$('#location-finder-form').html(street + ", " + city);
					$('#fi11').val(postalCode + "¿" + houseNumber + "¿" +addition + "¿" + street + "¿" + city);
				}
            });
		}
	</script>
</div>