<div class="form-container">
	<?php 
        $input_id = "input_" . $id;
    ?>
    <!-- Adress -->
    <input class="d-none xform" type="radio" name="r4" value="4" id="r4s">
    <label for="r4s" class="form-label label-font" onclick="xfocus('#fi7s')">Adres</label>
    <div class="form-block">
        <div class="d-flex w-100">
            <div class="w-50 section-block form-floating">
                <input class="w-100 ph-font" id="fi7s" placeholder="Postcode" onkeyup="xfocusOnKeyUp(event, '#fi8s')" onfocusout="checkAddressFormShort()">
                <label class="ph-font" for="fi7">Postcode</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-25 section-block form-floating">
                <input type="number" class="w-100 ph-font" id="fi8s" placeholder="Huisnr." onkeyup="xfocusOnKeyUp(event, '#fi9s')" onfocusout="checkAddressFormShort()">
                <label class="ph-font" for="fi8">Huisnr.</label>
            </div>
            <div class="form-gap"></div>
            <div class="w-25 section-block form-floating">
                <input class="w-100 ph-font" id="fi9s" placeholder="Toev." onkeyup="xfocusOnKeyUp(event, '#r5s', '#fi10s')" onfocusout="checkAddressFormShort()">
                <label class="ph-font" for="fi9">Toev.</label>
            </div>
        </div>
        <p id="location-finder-form" class="text-start mt-2 ms-2"><br></p>
        <input type="hidden" id="fi11s">
    </div>
    <!-- Phone -->
    <input class="d-none xform" type="radio" name="r5" value="5" id="r5s">
    <label for="r5s" class="form-label label-font muted" onclick="xfocus('#fi10s')">Telefoonnummer</label>
    <div class="form-block">
        <div class="w-100 form-floating">
            <input type="tel" class="w-100 ph-font" id="fi10s" placeholder="Telefoonnummer" onkeyup="xfocusOnKeyUp(event, '.btn-<?=$id?>.btn-next')">
            <label class="ph-font" for="fi4s">Telefoonnummer</label>
        </div>
    </div>

    <script>
		function checkAddressFormShort() {
			window.setTimeout(() => {
				let postalCode = $("#fi7s");
				let houseNumber = $("#fi8s");
				let addition = $("#fi9s");
				let hasFocus = postalCode.is(":focus") || houseNumber.is(":focus") || addition.is(":focus");
				if (postalCode.val() != "" && houseNumber.val() != "" && !hasFocus){
					let postalCodeVal = postalCode.val().replaceAll(' ', '');
					if (!(/^[0-9]{4}[a-zA-Z]{2}$/.test(postalCodeVal))){
						$('.error-message').css("display", "block");
						$('.error-message').html(window.errorMessages.invalid_postal_code);
						return;
					}
					$('.error-message').css("display", "none");
					this.ajax_addressCheckerFormShort(postalCodeVal, houseNumber.val(), addition.val());
				}
			}, 50);
		}

		function ajax_addressCheckerFormShort(postalCode, houseNumber, addition) {
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
						$('.error-message').css("display", "block");
						$('.error-message').html(window.errorMessages.invalid_address);
						return;
					}
					if (typeof data == "string")
						data = JSON.parse(data);
					// Gather results
					var street = data.street;
					var city = data.city;

					// Write results to some fields
					$('#location-finder-form').html(street + ", " + city);
					$('#fi11s').val(postalCode + "¿" + houseNumber + "¿" +addition + "¿" + street + "¿" + city);
				}
            });
		}
	</script>
</div>