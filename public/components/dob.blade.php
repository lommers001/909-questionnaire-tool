<div class="d-flex w-100">
	<?php
		$expl_placeholder = $page->placeholder;
		if (count($expl_placeholder) < 2){
			$expl_placeholder = ["Dag", "Maand", "Jaar"];
		}
		/*$expl_label = $page->label;
		if (count($expl_label) < 2){
			$expl_label = $expl_placeholder;
		}*/
	?>
	<div class="w-30 px-2">
		<div class="form-floating">
			<input id="<?= 'day_' . $id ?>" class="w-100 datefill jump-to-next ph-font" maxlength="3" type="number" name="dobday" placeholder="<?= $expl_placeholder[0] ?>">
			<label class="ph-font" for="<?= 'day_' . $id ?>"><?= $expl_placeholder[0] ?></label>
		</div>
	</div>
	<div class="w-30 px-2">
		<div class="form-floating">
			<input id="<?= 'month_' . $id ?>" class="w-100 datefill jump-to-next ph-font" maxlength="1" type="number" name="dobmonth" placeholder="<?= $expl_placeholder[1] ?>">
			<label class="ph-font" for="<?= 'month_' . $id ?>"><?= $expl_placeholder[1] ?></label>
		</div>
	</div>
	<div class="w-40 px-2">
		<div class="form-floating">
			<input id="<?= 'year_' . $id ?>" class="w-100 datefill ph-font" maxlength="" type="number" name="dobyear" placeholder="<?= $expl_placeholder[2] ?>">
			<label class="ph-font" for="<?= 'year_' . $id ?>"><?= $expl_placeholder[2] ?></label>
		</div>
	</div>
</div>