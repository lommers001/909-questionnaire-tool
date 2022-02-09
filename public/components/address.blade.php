<div>
	<div class="d-flex w-100">
	<?php
			$expl_placeholder = $page->placeholder;
			if (count($expl_placeholder) < 1){
				$expl_placeholder = ["Postcode", "Huisnummer"];
			}/*
			$expl_label = $page->label;
			if (count($expl_label) < 1){
				$expl_label = $expl_placeholder;
			}*/
		?>
		<div class="w-50 px-2">
			<div class="form-floating">
				<input id="<?= 'postal-code_' . $page->id ?>" class="w-100 addressfill jump-to-next ph-font" maxlength="6" name="address_zipcode" onfocusout="checkAddress()" placeholder="<?= $expl_placeholder[0] ?>">
				<label class="ph-font" for="<?= 'postal-code_' . $page->id ?>"><?= $expl_placeholder[0] ?></label>
			</div>
		</div>
		<div class="w-25 px-2">
			<div class="form-floating">
				<input id="<?= 'house-number_' . $page->id ?>" class="w-100 addressfill jump-to-next ph-font" maxlength="6" type="number" name="address_housenumber" onfocusout="checkAddress()" placeholder="<?= $expl_placeholder[1] ?>">
				<label class="ph-font" for="<?= 'house-number_' . $page->id ?>"><?= $expl_placeholder[1] ?></label>
			</div>
		</div>
		<div class="w-25 px-2">
			<div class="form-floating">
				<input id="<?= 'addition_' . $page->id ?>" class="w-100 addressfill ph-font" maxlength="6" name="address_addition" onfocusout="checkAddress()" placeholder="<?= $expl_placeholder[1] ?>">
				<label class="ph-font" for="<?= 'addition__' . $page->id ?>"><?= $expl_placeholder[1] ?></label>
			</div>
		</div>
    </div>
	<div class="row w-100">
		<p id="location-finder" class="text-start mt-2 ms-2"><br /></p>
	</div>

	<script>
		function checkAddress() {
			window.setTimeout(() => {
				let postalCode = $("#<?= 'postal-code_' . $page->id ?>");
				let houseNumber = $("#<?= 'house-number_' . $page->id ?>");
				let addition = $("#<?= 'addition_' . $page->id ?>");
				let hasFocus = postalCode.is(":focus") || houseNumber.is(":focus") || addition.is(":focus");
				if (postalCode.val() != "" && houseNumber.val() != "" && !hasFocus){
					let postalCodeVal = postalCode.val().replaceAll(' ', '');
					if (!(/^[0-9]{4}[a-zA-Z]{2}$/.test(postalCodeVal))){
						$('.error-message').css("display", "block");
						$('.error-message').html(window.errorMessages.invalid_postal_code);
						return;
					}
					$('.error-message').css("display", "none");
					this.ajax_addressChecker(postalCodeVal, houseNumber.val(), addition.val());
				}
			}, 50);
		}

		function ajax_addressChecker(postalCode, houseNumber, addition) {
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
					$('#location-finder').html(street + ", " + city);
					$('.btn-next.btn-<?=$id?>').removeClass("d-none");
					$('.btn-next-fake').addClass("d-none");
					window.answers[<?=$id?>] = postalCode + "¿" + houseNumber + "¿" +addition + "¿" + street + "¿" + city;
				}
            });
		}
	</script>

</div>