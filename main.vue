<template>
	<div class="row h-100">
		<!-- Icon Pane -->
		<div class="icon-pane">
			<button class="btn p-1 w-100" onClick="window.location.href='/';"><div class="go-back clickable"></div></button>
			<button class="btn" onClick="window.location.href='/';"><i class="fa fa-lg fa-list"></i></button>
        	<button class="btn" onClick="window.location.href='legals.php';"><i class="fa fa-lg fa-tags"></i></button>
			<button class="btn"><i class="fa fa-lg fa-pencil"></i></button>
		</div>
		<!-- Foolbar for Fonts -->
		<div class="editor-float wysiwyg-toolbar" v-show="showToolbar">
			<div class="d-flex align-items-center">
				<select id="font-select" class="wysiwyg-select w-10em" v-model="bindFont()[FONT_NAME]" @click="changeToLocal" @change="refresh(true); currFont = $event.target.value;" :data-chosen="currFont">
					<option id="Katibeh" value="Katibeh">Arabic</option>
					<option id="Port-Lligat-Slab" value="Port Lligat Slab">Artistic</option>
					<option id="Concert-One" value="Concert One">Concert</option>
					<option id="Cormorant-Upright" value="Cormorant Upright">Cursive</option>
					<option id="Eczar" value="Eczar">Eczar</option>
					<option id="Squada-One" value="Squada One">Geometric</option>
					<option id="Glass-Antiqua" value="Glass Antiqua">Glass</option>
					<option id="Abril-Fatface" value="Abril Fatface">Headline</option>
					<option id="Gravitas-One" value="Gravitas One">Headline Fat</option>
					<option id="New-Rocker" value="New Rocker">Heavy Metal</option>
					<option id="Lato" value="Lato">Lato</option>
					<option id="Merriweather" value="Merriweather">Merriweather</option>
					<option id="Montserrat" value="Montserrat">Montserrat</option>
					<option id="Open-Sans" value="Open Sans">Open Sans</option>
					<option id="Eagle-Lake" value="Eagle Lake">Paintbrush</option>
					<option id="Playfair-Display" value="Playfair Display">Playfair</option>
					<option id="Pirata-One" value="Pirata One">Pirata</option>
					<option id="Fugaz-One" value="Fugaz One">Racecar</option>
					<option id="Roboto" value="Roboto">Roboto</option>
					<option id="Roboto-Mono" value="Roboto Mono">Roboto Mono</option>
					<option id="Roboto-Slab" value="Roboto Slab">Roboto Slab</option>
					<option id="Cinzel" value="Cinzel">Travel</option>
				</select>
				<select class="wysiwyg-select w-7em" v-model="bindFont()[FONT_STYLE]" @click="changeToLocal" @change="refresh(true)">
					<option value="normal">Normal</option>
					<option value="italic">Italic</option>
					<option value="bold">Bold</option>
				</select>
				<input id="font-size-selector" class="wysiwyg-select w-5em maintain" @click="changeToLocal" v-model="bindFont()[FONT_SIZE]" @input="refresh(true)">
				<input class="editor-float font-size-float" type="range" min="10" max="40" v-model="bindFont()[FONT_SIZE]" @input="refresh(true)" style="display: none">
				<span class="font-size-float font-size-container" style="display: none"></span>
				<input type="color" class="wysiwyg-color" v-model="bindFont()[FONT_COLOR]" @click="changeToLocal(); bindColor('font', $event)">
				<div :class="(fontActive == 'header' || fontActive == 'par') ? 'd-flex' : 'd-none'">
					<div class="wysiwyg-separator">|</div>
					<i id="icon-bold" class="fa fa-lg fa-bold wysiwyg-icon" @click="insertTag('B')"></i>
					<i id="icon-italic" class="fa fa-lg fa-italic wysiwyg-icon" @click="insertTag('I')"></i>
					<i id="icon-underline" class="fa fa-lg fa-underline wysiwyg-icon" @click="insertTag('U')"></i>
					<div class="wysiwyg-separator">|</div>
					<input type="radio" class="d-none r-hidden" id="align-left" name="text-align" value="left" v-model="bindFont()[FONT_ALIGN]" @click="changeToLocal(); bindFont()[4] = 'left'; refresh(true)">
					<label class="mb-0 align-button" for="align-left"><i class="fa fa-lg fa-align-left wysiwyg-icon"></i></label>
					<input type="radio" class="d-none r-hidden" id="align-center" name="text-align" value="center" v-model="bindFont()[FONT_ALIGN]" @click="changeToLocal(); bindFont()[4] = 'center'; refresh(true)">
					<label class="mb-0 align-button" for="align-center"><i class="fa fa-lg fa-align-center wysiwyg-icon"></i></label>
					<input type="radio" class="d-none r-hidden" id="align-right" name="text-align" value="right" v-model="bindFont()[FONT_ALIGN]" @click="changeToLocal(); bindFont()[4] = 'right'; refresh(true)">
					<label class="mb-0 align-button" for="align-right"><i class="fa fa-lg fa-align-right wysiwyg-icon"></i></label>
				</div>
				<div :class="fontActive == 'header' ? 'd-flex' : 'd-none'">
					<div class="wysiwyg-separator">|</div>
					<select class="wysiwyg-select w-7em" v-model="bindFont()[FONT_BORDER_STYLE]" @click="changeToLocal" @change="refresh(true)">
						<option value="none">No border</option>
						<option value="1px solid">Thin border</option>
						<option value="3px solid">Thick border</option>
						<option value="3px double">Dual border</option>
					</select>
					<input type="color" class="wysiwyg-color" v-model="bindFont()[FONT_BORDER_COLOR]" @click="changeToLocal(); bindColor('font-border', $event)">
				</div>
				<div class="wysiwyg-separator">|</div>
				<span class="border rounded p-1">{{ invIsGlobalStyle && !mayLocal ? "Global" : "This page" }}</span>
			</div>
		</div>
		<!-- Image editing widget -->
		<div class="editor-float image-float" v-show="imageWidgetData[IMG_DATA_VISIBLE]">
			<label>Choose image location</label>
			<div class="d-flex">
				<button v-show="imageWidgetData[IMG_DATA_IMAGE_TYPE] == IMG_FOR_DESKTOP" type="button" :class="'btn m-1 btn-'+(imageWidgetData[IMG_DATA_FONTNAME] == IMG_LOCATION_1 ? 'primary' : 'secondary')" @click="imgToggle(IMG_LOCATION_1)"><div id="img-11" class="image-location-img"></div></button>
				<button v-show="imageWidgetData[IMG_DATA_IMAGE_TYPE] == IMG_FOR_DESKTOP" type="button" :class="'btn m-1 btn-'+(imageWidgetData[IMG_DATA_FONTNAME] == IMG_LOCATION_2 ? 'primary' : 'secondary')" @click="imgToggle(IMG_LOCATION_2)"><div id="img-12" class="image-location-img"></div></button>
				<button v-show="imageWidgetData[IMG_DATA_IMAGE_TYPE] == IMG_FOR_DESKTOP" type="button" :class="'btn m-1 btn-'+(imageWidgetData[IMG_DATA_FONTNAME] == IMG_LOCATION_3 ? 'primary' : 'secondary')" @click="imgToggle(IMG_LOCATION_3)"><div id="img-21" class="image-location-img"></div></button>
				<button v-show="imageWidgetData[IMG_DATA_IMAGE_TYPE] == IMG_FOR_DESKTOP" type="button" :class="'btn m-1 btn-'+(imageWidgetData[IMG_DATA_FONTNAME] == IMG_LOCATION_4 ? 'primary' : 'secondary')" @click="imgToggle(IMG_LOCATION_4)"><div id="img-22" class="image-location-img"></div></button>
				<button v-show="imageWidgetData[IMG_DATA_IMAGE_TYPE] == IMG_FOR_MOBILE" type="button" :class="'btn m-1 btn-'+(imageWidgetData[IMG_DATA_FONTNAME] == IMG_LOCATION_1 ? 'primary' : 'secondary')" @click="imgToggle(IMG_LOCATION_1)"><div id="img-21" class="image-location-img"></div></button>
				<button v-show="imageWidgetData[IMG_DATA_IMAGE_TYPE] == IMG_FOR_MOBILE" type="button" :class="'btn m-1 btn-'+(imageWidgetData[IMG_DATA_FONTNAME] == IMG_LOCATION_2 ? 'primary' : 'secondary')" @click="imgToggle(IMG_LOCATION_2)"><div id="img-22" class="image-location-img"></div></button>
				<button v-show="imageWidgetData[IMG_DATA_IMAGE_TYPE] == IMG_FOR_MOBILE" type="button" :class="'btn m-1 btn-'+(imageWidgetData[IMG_DATA_FONTNAME] == IMG_WITH_FADE ? 'primary' : 'secondary')" @click="imgToggle(IMG_WITH_FADE)"><div id="img-31" class="image-location-img"></div></button>
			</div>
			<button class="btn btn-dark w-100 my-1" @click="selectImage()">{{isImgSetForWidget() ? 'Replace Image' : 'Upload Image'}}</button>
			<button class="btn btn-danger w-100 my-1" v-show="isImgSetForWidget()" @click="removeImage()">Remove Image</button>
		</div>
		<!-- BG gradient widget -->
		<div class="editor-float gradient-float" v-show="gradientWidgetData[GRADIENT_DATA_VISIBLE]">
			<div class="mb-2">Colors</div>
			<div class="d-flex">
				<input type="color" id="grad-color-1" @click="bindColor('gradient', $event, null, 1)">
				<div class="w-75 ml-1 pt-1">Color 1</div>
			</div>
			<div class="d-flex">
				<input type="color" id="grad-color-2" @click="bindColor('gradient', $event, null, 3)">
				<div class="w-75 ml-1 pt-1">Color 2</div>
			</div>
			<div class="mb-2">Direction</div>
			<table>
				<tr>
					<input type="radio" class="d-none" id="grad-d-45deg" name="grad-direction" value="-45deg">
					<td><label for="grad-d-45deg" class="grad-dir-button" @click="bindGradient(0, '-45deg')">&nwarr;</label></td>
					<input type="radio" class="d-none" id="grad-d0deg" name="grad-direction" value="0deg">
					<td><label for="grad-d0deg" class="grad-dir-button" @click="bindGradient(0, '0deg')">&uarr;</label></td>
					<input type="radio" class="d-none" id="grad-d45deg" name="grad-direction" value="45deg">
					<td><label for="grad-d45deg" class="grad-dir-button" @click="bindGradient(0, '45deg')">&nearr;</label></td>
				</tr>
				<tr>
					<input type="radio" class="d-none" id="grad-d90deg" name="grad-direction" value="90deg">
					<td><label for="grad-d90deg" class="grad-dir-button" @click="bindGradient(0, '90deg')">&larr;</label></td>
					<td></td>
					
					<input type="radio" class="d-none" id="grad-d-90deg" name="grad-direction" value="-90deg">
					<td><label for="grad-d-90deg" class="grad-dir-button" @click="bindGradient(0, '-90deg')">&rarr;</label></td>
				</tr>
				<tr>
					<input type="radio" class="d-none" id="grad-d225deg" name="grad-direction" value="225deg">
					<td><label for="grad-d225deg" class="grad-dir-button" @click="bindGradient(0, '225deg')">&swarr;</label></td>
					<input type="radio" class="d-none" id="grad-d180deg" name="grad-direction" value="180deg">
					<td><label for="grad-d180deg" class="grad-dir-button" @click="bindGradient(0, '180deg')">&darr;</label></td>
					<input type="radio" class="d-none" id="grad-d135deg" name="grad-direction" value="135deg">
					<td><label for="grad-d135deg" class="grad-dir-button" @click="bindGradient(0, '135deg')">&searr;</label></td>
				</tr>
			</table>
			<div class="my-2">Precentage</div>
			<input type="range" min="0" max="100" id="grad-per" class="w-100" @change="bindGradient(2, $event.target.value + '%')"><br>
			<span class="grad-preview gp-1" :style="{backgroundColor: querySel('#grad-color-1') == null ? '#000' : querySel('#grad-color-1').value}"></span>
			<span class="grad-preview gp-2" :style="{backgroundColor: querySel('#grad-color-2') == null ? '#000' : querySel('#grad-color-2').value}"></span>
		</div>
		<!-- Editing tools are shown here -->
		<div class="left-pane">
			<!-- Global Style -->
			<div class="section" id="style-section">
				<hr class="mt-4 mb-1">
				<div id="style-menu" class="my-2 global-style">
					<div id="style-menu-header" class="p-1 d-flex clickable hover-blue" data-toggle="collapse" data-target="#collapse-global-style" aria-expanded="false" aria-controls="collapse-global-style">
						<div class="w-75">Global Style</div>
						<i class="fa fa-pencil w-25 mt-1 text-right"></i>
					</div>
					<div class="collapse" id="collapse-global-style">
						<!-- Fields for editing style -->
						<div class="w-100 mt-2">
							<h6>Fonts</h6>
							<div :class="'font-block border-thick p-2 ' + (fontActive == 'header' && invIsGlobalStyle ? 'border-dark' : '')" @click="showWysiwygToolbar($event, 'header')">
								<div>Header</div>
								<div>{{ getFontDescription('header') }}</div>
							</div>
							<div :class="'font-block border-thick p-2 mt-1 ' + (fontActive == 'par' && invIsGlobalStyle ? 'border-dark' : '')" @click="showWysiwygToolbar($event, 'par')">
								<div>Text</div>
								<div>{{ getFontDescription('par') }}</div>
							</div>
							<div :class="'font-block border-thick p-2 mt-1 ' + (fontActive == 'fbutton' && invIsGlobalStyle ? 'border-dark' : '')" @click="showWysiwygToolbar($event, 'fbutton')">
								<div>Button</div>
								<div>{{ getFontDescription('fbutton') }}</div>
							</div>
							<div class="d-flex mt-2">
								<label class="text-left w-50">Button Rounding</label>
								<label class="text-right w-50">Button Color</label>
							</div>
							<div class="d-flex mb-2">
								<div class="w-50 my-1 mr-1">
									<input class="w-100 mt-1" type="range" min="0" max="20" step="5" v-model="bindStyle().bround" @change="refresh(true);">
								</div>
								<div class="w-50 d-flex flex-row-reverse my-1 ml-1">
									<input type="color" v-model="bindStyle().bcolor" @click="bindColor('bcolor', $event)">
								</div>
							</div>
							<div class="d-flex">
								<label class="text-left w-50">Image Mobile</label>
								<label class="text-right w-50">Image Desktop</label>
							</div>
							<div class="d-flex">
								<div :class="'w-50 border-thick clickable image-preview mr-2' + (imageWidgetData[IMG_DATA_VISIBLE] && !imageWidgetData[IMG_DATA_IS_LOCAL] && imageWidgetData[IMG_DATA_IMAGE_TYPE] == IMG_FOR_MOBILE ? ' border-dark' : '')" @click="showImageWidget(IMG_FOR_MOBILE)">
									<div v-if="isImgSet(IMG_FOR_MOBILE)" id="image-default-mobile" class="image-default"></div>
									<div v-else id="image-preview-mobile" class="w-100" :style="{backgroundImage: getBgImage(IMG_FOR_MOBILE, true)}"></div>
								</div>
								<div :class="'w-50 border-thick clickable image-preview ml-2' + (imageWidgetData[IMG_DATA_VISIBLE] && !imageWidgetData[IMG_DATA_IS_LOCAL] && imageWidgetData[IMG_DATA_IMAGE_TYPE] == IMG_FOR_DESKTOP ? ' border-dark' : '')" @click="showImageWidget(IMG_FOR_DESKTOP)">
									<div v-if="isImgSet(IMG_FOR_DESKTOP)" id="image-default-desktop" class="image-default"></div>
									<div v-else id="image-preview-desktop" class="w-100" :style="{backgroundImage: getBgImage(IMG_FOR_DESKTOP, true)}"></div>
								</div>
							</div>
							<label>Background</label>
							<div class="d-flex mb-2 w-100">
								<button :class="'w-50 mr-2 btn btn-pane btn-' + IsSolidSet" @click="getColorSolid('normal-color')"><input id="normal-color" type="color" @click="bindColor('bg', $event)">&nbsp;<span class="btn-fc">Full Color</span></button>
								<input type="hidden" id="input-gradient" v-model="bindStyle().gcolor">
								<button :class="'w-50 ml-2 btn btn-pane btn-' + IsGradientSet" @click="showGradientWidget()">Gradient</button>
							</div>
							<div class="d-flex justify-content-between">
								<label>Answer / Loading Bar</label>
								<input type="color" v-model="bindStyle().acolor" @click="bindColor('acolor', $event)">
							</div>
							<button class="w-100 btn btn-sm btn-dark my-1" data-toggle="modal" data-target="#localeModal">Edit Form</button>
						</div>
					</div>
				</div>
			</div>
			<div class="section">
				<!-- List of pages -->
				<select class="form-control page-select clickable w-100" @change="addPage">
					<option value="-1" disabled selected hidden>Add Page</option>
					<option value="1">Splash Page</option>
					<option value="8">Legal</option>
					<option value="13">Name</option>
					<option value="2">Open Question (Text)</option>
					<option value="3">Open Question (Number)</option>
					<option value="4">Multiple Choice</option>
					<option value="17">Dropdown</option>
					<option value="14">Checkboxes</option>
					<option value="5">Phone Number</option>
					<option value="6">Email</option>
					<option value="7">Address</option>
					<option value="9">Date of Birth</option>
					<option value="0">Loading Screen</option>
					<option value="11">Long Form</option>
					<option value="12">Short Form - Part 1</option>
					<option value="15">Short Form - Part 2</option>
					<option value="16">Transition</option>
					<option value="10">Thank You</option>
				</select>
			</div>
				
			<div class="d-flex flex-column p-2" id="drag-and-drop">
				<div class="page-list-top position-relative" @drop="drop($event, DEFAULT_PAGE)" @dragenter="dragEnter($event, DEFAULT_PAGE)" @dragover="dragPrevent" @dragleave="dragLeave"></div>
				<div class="page-list-element border-thick border-dark mb-2" v-for="(page, index) in pages" :key="index" :id="'p-'+index">
					<div class="d-flex justify-content-between" draggable="true" @dragstart="drag($event, index)" @dragend="dragStop($event)">
						<span class="drag-display"></span>
						<span class="page-display-text hover-blue py-2 ml-2" @click="show($event, index)" data-toggle="collapse" :data-target="'#collapse-'+index" aria-expanded="false" :aria-controls="'collapse-'+index">{{(index + 1)}}.&nbsp;{{getType(page.type)}}</span>
						<span class="dragover-display-text py-2 ml-2 d-none"></span>
						<span class="page-list-icons">
							<div v-if="currPage == index && !allPagesCollapsed" class="border-thick-lb">
								<span class="clickable hover-green" @mouseup="copyPage(index)"><i class="fa fa-copy"></i></span>
								<span class="clickable hover-red" @mouseup="removePage(index)"><i class="fa fa-trash"></i></span>
							</div>
							<div v-else>
								<span class="mx-1 clickable hover-blue" @mouseup="setVisibility($event, index, 5)"><i :class="'fa fa-2x ' + (getVisibility(index, 5) ? 'fa-eye-slash' : 'fa-eye')"></i></span>
							</div>
						</span>
					</div>
					<div class="collapse collapse-group px-2" :id="'collapse-'+index" data-parent="#drag-and-drop">
						<div class="d-flex mt-3 mb-2">
							<button :class="'btn btn-sm w-50 mr-1 border border-dark ' + (getVisibility(index, 1) ? 'btn-dark' : 'btn-light')" @mouseup="setVisibility($event, index, 1)">Mobile</button>
							<button :class="'btn btn-sm w-50 ml-1 border border-dark ' + (getVisibility(index, 2) ? 'btn-dark' : 'btn-light')" @mouseup="setVisibility($event, index, 2)">Desktop</button>
						</div>
						<h6>Styling</h6>
						<div class="d-flex my-2">
							<button :class="'btn btn-sm btn-1 w-50 mr-1 border border-dark ' + (IsPageStyleSet ? 'btn-light' : 'btn-dark')" @click="resetToGlobal">Global</button>
							<button :class="'btn btn-sm btn-2 w-50 ml-1 border border-dark ' + (IsPageStyleSet ? 'btn-dark' : 'btn-light')" @click="movePageStylingSection(index, true)">This page</button>
						</div>
						<div :id="'page-styling-'+index"></div>
					</div>
					<div class="w-100 drag-target" @drop="drop($event, index)" @dragenter="dragEnter($event, index)" @dragover="dragPrevent" @dragleave="dragLeave"></div>
				</div>
			</div>
			
		</div>
		<!-- Content is shown here -->
		<div class="main-pane">
			<div v-show="showToolbar" class="toolbar-overlay" @click="hideWysiwygToolbar()"></div>
			<div v-show="imageWidgetData[IMG_DATA_VISIBLE] || gradientWidgetData[GRADIENT_DATA_VISIBLE]" class="widget-overlay" @click="hideWidgets()"></div>
			<div :id="mainContainer" class="main-content" :style="previewDimensions">
				<i v-show="getPage().xid != undefined" :class="'fa fa-lg fa-cog clickable'+ (getPage().xid != undefined && getPage().xid[0] != '' ? '' : ' missing')" @click="showXids" data-toggle="modal" data-target="#xidModal"></i>
				<div id="bg-color" class="background-color" :style="getBgColor()"></div>
				<div id="bg-image" :class="getBgImageStyle() + BgImageDClasses" :style="{backgroundImage: getBgImage()}" @click="openImagePopup"></div>
				<div :class="'d-flex text-center ' + ContentDClasses">
					<div id="dimensions" class="position-absolute text-muted">Dimensions: 1024 * 720</div>
					<div class="container" id="content-container">
						<div v-show="getPage().type != typeLegal && getPage().type != typeTransition && currPage != DEFAULT_PAGE">
							<div id="page-header" class="text-input" :style="getFontStyle('header')" @input="setTitle" @click="showWysiwygToolbar($event, 'header', false, true)"></div>
							<div id="page-par" class="text-input" v-show="getPage().type != typeLoading" :style="getFontStyle('par')" @input="setPar" @click="showWysiwygToolbar($event, 'par', false, true)"></div>
						</div>
						<div v-show="getPage().type == typeLegal" id="legal-text" class="text-input">
						</div>
						<div v-if="getPage().type == typeAddress">
							<div class="input-container container-md mt-4 p-2">
								<div class="row align-items-center">
									<input class="col-7 text-input maintain" v-model="getPage().placeholder[0]" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
									<div class="col-1"></div>
									<input class="col-4 text-input maintain" v-model="getPage().placeholder[1]" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
								</div>
							</div>
							<span class="text-danger" v-show="showErrors">{{error[1]}}<br>{{error[8]}}<br>{{error[9]}}</span>
						</div>
						<div v-else-if="getPage().type == typeDOB">
							<div class="input-container container mt-4 p-2">
								<div class="row align-items-center">
									<input class="col-3 text-input maintain" v-model="getPage().placeholder[0]" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
									<div class="col-1"></div>
									<input class="col-3 text-input maintain" v-model="getPage().placeholder[1]" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
									<div class="col-1"></div>
									<input class="col-4 text-input maintain" v-model="getPage().placeholder[2]" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
								</div>
							</div>
							<span class="text-danger" v-show="showErrors">{{error[1]}}<br>{{error[5]}}<br>{{error[6]}}<br>{{error[7]}}</span>
						</div>
						<div v-else-if="[typeDropdown, typeMultipleChoice, typeCheckboxes].includes(getPage().type)">
							<div v-if="getPage().type == typeDropdown" class="input-container dropdown-container">
								<input class='w-100 text-input maintain' v-model="getPage().placeholder" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
							</div>
							<div :class="'input-container container d-flex flex-column mt-4 multiple-choice-container' + (getPage().type == typeCheckboxes ? ' checkboxes' : '')">
								<div v-for="(choice, index) in getChoices()" :key="index" class="row mb-1 p-2" :style="{backgroundColor: calcBrightness(getStyle('acolor')), borderColor: getStyle('acolor')}">
									<input class="w-100 text-input" v-model="getChoices()[index]" @change="removeChoiceOnEmpty($event, index)" :style="getFontStyle('fanswer')" @click="showWysiwygToolbar($event, 'fanswer')">
								</div>
								<button class="btn btn-sm btn-outline-primary" @click="addChoice"> Add Answer&ensp;<i class="fa fa-plus-circle"></i> </button>
							</div>
						</div>
						<div v-else-if="getPage().type == typeLoading">
							<div class="input-container container d-flex flex-column">
								<div class="progress mb-2">
									<div class="progress-bar progress-bar-striped progress-bar-animated w-50" role="progressbar" id="progressbar" :style="{backgroundColor: getStyle('acolor')}">0%</div>
								</div>
								<div v-for="(choice, index) in getChoices()" :key="index" class="row align-items-center">
									<input class="border rounded ml-3 mr-3 w-100 text-input" v-model="getChoices()[index]" @change="removeChoiceOnEmpty($event, index)" :style="getFontStyle('fanswer')" @click="showWysiwygToolbar($event, 'fanswer')">
								</div>
								<button class="btn btn-sm btn-outline-primary ml-1" @click="addChoice"> Add Message&ensp;<i class="fa fa-plus-circle"></i> </button>
							</div>
						</div>
						<div v-else-if="getPage().type == typeFormShort_1">
							<div class="input-container container mt-4 p-2">
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">Naam</div>
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">E-mail</div>
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">Geboortedatum</div>
							</div>
							<span class="text-danger" v-show="showErrors">{{error[1]}}</span>
						</div>
						<div v-else-if="getPage().type == typeFormShort_2">
							<div class="input-container container mt-4 p-2">
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">Adres</div>
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">Telefoonnummer</div>
							</div>
							<span class="text-danger" v-show="showErrors">{{error[1]}}</span>
						</div>
						<div v-else-if="getPage().type == typeFormLong">
							<div class="input-container container mt-4 p-2">
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">Naam</div>
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">E-mail</div>
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">Geboortedatum</div>
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">Adres</div>
								<div class='w-100 element-form' :style="getFontStyle('flabel')" @click="showWysiwygToolbar($event, 'flabel')">Telefoonnummer</div>
							</div>
							<span class="text-danger" v-show="showErrors">{{error[1]}}</span>
						</div>
						<div v-else-if="getPage().type == typeTransition">
							<div class="input-container container mt-4 p-2">
								<p>Transition page<br>Determine transition speed</p>
								<br>
								<input class="w-75 mt-1" type="range" min="1" max="5" step="1" v-model="getPage().speed" @change="refresh(true);">
							</div>
						</div>
						<div v-else-if="getPage().type == typeName">
							<div class="input-container container mt-4 p-2">
								<div class="w-100 text-left">
									<input type="radio" class="maintain" id="gender-m" name="gender" value="m" disabled>
									<input class="col-3 text-input text-left" v-model="getPage().label[0]" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
									<input type="radio" class="maintain" id="gender-f" name="gender" value="f" disabled>
									<input class="col-3 text-input text-left" v-model="getPage().label[1]" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
								</div>
								<div class="d-flex w-100 mt-2">
									<input class='w-50 text-input maintain mr-2' v-model="getPage().placeholder[0]" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
									<input class='w-50 text-input maintain ml-2' v-model="getPage().placeholder[1]" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
								</div>
							</div>
							<span class="text-danger" v-show="showErrors">{{error[1]}}</span>
						</div>
						<div v-else-if="getPage().type > typeSplash && getPage().type < typeAddress">
							<div class='input-container d-flex justify-content-center'>
								<input class='w-100 text-input maintain' v-model="getPage().placeholder" :style="getFontStyle('fph')" @click="showWysiwygToolbar($event, 'fph')">
							</div>
							<span class="text-danger" v-show="showErrors">{{error[0]}}
								<span v-show="getPage().type == typeOpenNumber"><br>{{error[2]}}</span>
								<span v-show="getPage().type == typePhone"><br>{{error[3]}}</span>
								<span v-show="getPage().type == typeEmail"><br>{{error[4]}}</span>
							</span>
						</div>
						<div :id="nextButton" v-show="![typeLoading, typeMultipleChoice, typeTransition, typeDropdown].includes(getPage().type) && currPage != DEFAULT_PAGE" class="btn mt-4" :style="{backgroundColor: getStyle('bcolor'), borderRadius: getStyle('bround') + 'px'}">
							<input v-if="getPage().type != typeLegal" class="text-input" :style="getFontStyle('fbutton')" v-model="getPage().c2a" @click="showWysiwygToolbar($event, 'fbutton', false, true)">
							<input v-else class="text-input" :style="getFontStyle('fbutton')" :value="legalc2a" disabled @click="showWysiwygToolbar($event, 'fbutton', false, true)">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="d-none" id="not-visible">
			<input id="input-image" name="image" type="file" accept="image/*" @change="uploadImage">
			<div id="page-styling" class="my-2">
				<!-- Fields for editing local style -->
				<div class="w-100 mt-2">
					<h6>Fonts</h6>
					<div :class="'font-block border-thick p-2 ' + (fontActive == 'header' && !invIsGlobalStyle ? 'border-dark' : '')" @click="showWysiwygToolbar($event, 'header', true)">
						<div :class="checkOverrides('header')">Header</div>
						<div>{{ getFontDescription('header', true) }}</div>
					</div>
					<div :class="'font-block border-thick p-2 mt-1 ' + (fontActive == 'par' && !invIsGlobalStyle ? 'border-dark' : '')" @click="showWysiwygToolbar($event, 'par', true)">
						<div :class="checkOverrides('par')">Text</div>
						<div>{{ getFontDescription('par', true) }}</div>
					</div>
					<div :class="'font-block border-thick p-2 mt-1 ' + (fontActive == 'fbutton' && !invIsGlobalStyle ? 'border-dark' : '')" @click="showWysiwygToolbar($event, 'fbutton', true)">
						<div :class="checkOverrides('fbutton')">Button</div>
						<div>{{ getFontDescription('fbutton', true) }}</div>
					</div>
					<div class="d-flex mt-2">
						<label :class="'text-left w-50 ' + checkOverrides('bround')">Button Rounding</label>
						<label :class="'text-right w-50 ' + checkOverrides('bcolor')">Button Color</label>
					</div>
					<div class="d-flex mb-2">
						<div :class="'w-50 my-1 mr-1 ' + checkOverrides('bround')">
							<input class="w-100 mt-1" type="range" min="0" max="20" step="5" v-model="bindStyle(true).bround" @change="refresh(true);">
						</div>
						<div class="w-50 d-flex flex-row-reverse my-1 ml-1">
							<input type="color" v-model="bindStyle(true).bcolor" @click="bindColor('bcolor', $event, true)" :class="checkOverrides('bcolor')">
						</div>
					</div>
					<div class="d-flex">
						<label :class="'text-left w-50 '+checkOverrides('3')">Image Mobile</label>
						<label :class="'text-right w-50 '+checkOverrides('1')">Image Desktop</label>
					</div>
					<div class="d-flex">
						<div :class="'w-50 border-thick clickable image-preview mr-2' + (imageWidgetData[IMG_DATA_VISIBLE] && imageWidgetData[IMG_DATA_IS_LOCAL] && imageWidgetData[IMG_DATA_IMAGE_TYPE] == DEVICE_TYPE_MOBILE ? ' border-dark' : '')" @click="showImageWidget(DEVICE_TYPE_MOBILE, true)">
							<div v-if="isImgSet(IMG_FOR_MOBILE, true)" id="image-preview-mobile" class="w-100" :style="{backgroundImage: getBgImage(IMG_FOR_MOBILE)}"></div>
							<div v-else id="image-default-mobile" class="image-default"></div>
						</div>
						<div :class="'w-50 border-thick clickable image-preview ml-2' + (imageWidgetData[IMG_DATA_VISIBLE] && imageWidgetData[IMG_DATA_IS_LOCAL] && imageWidgetData[IMG_DATA_IMAGE_TYPE] == DEVICE_TYPE_DESKTOP ? ' border-dark' : '')" @click="showImageWidget(DEVICE_TYPE_DESKTOP, true)">
							<div v-if="isImgSet(IMG_FOR_DESKTOP, true)" id="image-preview-desktop" class="w-100" :style="{backgroundImage: getBgImage(IMG_FOR_DESKTOP)}"></div>
							<div v-else id="image-default-desktop" class="image-default"></div>
						</div>
					</div>
					<label :class="checkOverrides('gcolor')">Background</label>
					<div class="d-flex mb-2 w-100">
						<button :class="'w-50 mr-2 btn btn-pane btn-sm btn-' + IsSolidSetLocal" @click="getColorSolid('normal-color-local')"><input id="normal-color-local" type="color" @click="bindColor('bg', $event, true)">&nbsp;<span class="btn-fc">Full Color</span></button>
						<input type="hidden" id="input-gradient-local" v-model="bindStyle(true).gcolor" :class="checkOverrides('gcolor')">
						<button :class="'w-50 ml-2 btn btn-pane btn-sm btn-' + IsGradientSetLocal" @click="showGradientWidget(true)">Gradient</button>
					</div>
					<div class="d-flex justify-content-between">
						<label :class="checkOverrides('acolor')">Answer / Loading Bar</label>
						<input type="color" v-model="bindStyle(true).acolor" @click="bindColor('acolor', $event, true)" :class="checkOverrides('acolor')">
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'App',
	props: {
		content: String,
		slug: String,
		errors: String,
		legal: String,
		legalc2a: String
	},
	data() {
		return {
			pages: JSON.parse(this.content).pages,
			style: JSON.parse(this.content).style,
			error: this.errors.split("¿"),
			currFont: "Roboto",
			currPage: -1,
			defaultPage: {"id": 0, "title": "", "type": -1, "text": "", "placeholder" : "", "c2a" : "", "choices": [], "style" : {} },
			dragId: -1,
			isGlobalStyle: true,
			invIsGlobalStyle: true,
			mayLocal: false,
			imageUpdateNumber: 1,
			imageUploadType: 1,
			imageWidgetData: [false, false, 0, 0],
			gradientWidgetData: [false, false],
			currentDType: 1,
			devices: ['a', 'd', 't', 'm'],
			showErrors: false,
			fontActive: null,
			showToolbar: false,
			prevSelection: null,
			newEl: null,
			areChangesMade: false,
			allPagesCollapsed: false,

			previewDimensions: {width: '1024px', height: '720px', top: 'max(0px, calc(50vh - 380px))', left: 'max(0px, calc(50vw - 710px))'},
			mainContainer: "content-container-landscape",
			nextButton: "button-next",

			typeLoading: 0,
			typeSplash: 1,
			typeOpenText: 2,
			typeOpenNumber: 3,
			typeMultipleChoice: 4,
			typePhone: 5,
			typeEmail: 6,
			typeAddress: 7,
			typeLegal: 8,
			typeDOB: 9,
			typeThanks: 10,
			typeFormLong: 11,
			typeFormShort_1: 12,
			typeName: 13,
			typeCheckboxes: 14,
			typeFormShort_2: 15,
			typeTransition: 16,
			typeDropdown: 17,

			IMG_PREFIX: 0,
			IMG_FOR_DESKTOP: 1,
			IMG_FOR_MOBILE: 3,
			IMG_TYPE_NONE: -1,
			IMG_TYPE_DEFAULT: 0,
			IMG_LOCATION_1: 1,
			IMG_LOCATION_2: 2,
			IMG_LOCATION_3: 3,
			IMG_LOCATION_4: 4,
			IMG_WITH_FADE: 5,

			IMG_DATA_VISIBLE: 0,
			IMG_DATA_IS_LOCAL: 1,
			IMG_DATA_IMAGE_TYPE: 2,
			IMG_DATA_FONTNAME: 3,

			GRADIENT_DATA_VISIBLE: 0,
			GRADIENT_DATA_ISLOCAL: 1,
			
			FONT_NAME: 0,
			FONT_STYLE: 1,
			FONT_SIZE: 2,
			FONT_COLOR: 3,
			FONT_ALIGN: 4,
			FONT_BORDER_STYLE: 5,
			FONT_BORDER_COLOR: 6,

			DEFAULT_PAGE: -1,

			DEVICE_TYPE_DESKTOP: 1,
			DEVICE_TYPE_MOBILE: 3
			
		}
	},
	computed: {
		BgImageDClasses: function() { return (this.getPage().type == this.typeTransition || this.getPage().type == this.typeLegal || this.getBgImage() == "none") ? 'd-none' : 'd-block' },
		ContentDClasses: function() { return (this.getPage().type == this.typeTransition || this.getPage().type == this.typeLegal || this.getBgImage() == "none") ? 'view-full' : 'view-half' },
		IsGradientSet: function() { return (this.style.gcolor.length > 8 ? 'dark' : 'outline-dark') },
		IsGradientSetLocal: function() { return (this.getStyle('gcolor').length > 8 ? 'dark' : 'outline-dark') },
		IsPageStyleSet: function() { return (this.getPage().style != undefined && Object.keys(this.getPage().style).length > 0) },
		IsSolidSetLocal: function() { return (this.getStyle('gcolor').length > 8 ? 'outline-dark' : 'dark') },
		IsSolidSet: function() { return (this.style.gcolor.length > 8 ? 'outline-dark' : 'dark') }
	},
	methods: {
		addChoice() {
			if (this.getChoices().length < 10){
				this.getChoices().push("Text");
				this.watchChanges();
			}
		},

		addPage(event){
			let pageType = Number(event.target.value);
			if (pageType == this.DEFAULT_PAGE)
				return;
			var newId = this.createNewId();
			let dXids = ["","","","","","f_12_phone1","f_1_email"];
			let fXids = ["","f_1_email","f_2_title","f_3_firstname","f_4_lastname","f_5_dob","f_6_address1","f_7_address2","f_8_address3","f_9_towncity","","f_11_postcode","f_12_phone1"];
			let newPage = {"id": newId, "type": pageType, "title": "Question", "text": "Lorem ipsum", "c2a" : "Next"};

			if (pageType == this.typeSplash || pageType == this.typeThanks){
				newPage.title = "Title";
			} else if (pageType == this.typeMultipleChoice || pageType == this.typeCheckboxes || pageType == this.typeDropdown){
				newPage.choices = ["Option 1", "Option 2"];
				newPage.xid = [""];
				if(pageType != this.typeCheckboxes) newPage.c2a = "";
				if(pageType == this.typeDropdown) newPage.placeholder = "Select";
			} else if (pageType == this.typeLegal){
				newPage = {"id": newId, "type": pageType, "title": "", "text": "", "c2a" : "OK"};
			} else if (pageType == this.typeAddress){
				newPage.placeholder = ["Postcode","Huisnr"];
				newPage.xid = [fXids[6],fXids[7],fXids[8],fXids[9],fXids[11]];
			} else if (pageType == this.typeDOB){
				newPage.placeholder = ["Day","Month","Year"];
				newPage.xid = [fXids[5]];
			} else if (pageType == this.typeName){
				newPage.label = ["Mr.","Ms."];
				newPage.placeholder = ["First Name","Last Name"];
				newPage.xid = [fXids[2],fXids[3],fXids[4]];
			} else if (pageType == this.typeLoading){
				newPage = {"id": newId, "type": pageType, "title": "Loading", "text": "", "choices": ["Message"], "c2a" : ""};
			} else if (pageType == this.typeFormShort_1){
				newPage.xid = [fXids[2],fXids[3],fXids[4],fXids[1],fXids[5]];
			} else if (pageType == this.typeFormShort_2){
				newPage.xid = [fXids[6],fXids[7],fXids[8],fXids[9],fXids[11],fXids[12]];
			} else if (pageType == this.typeFormLong){
				newPage.xid = [fXids[2],fXids[3],fXids[4],fXids[1],fXids[5],fXids[6],fXids[7],fXids[8],fXids[9],fXids[11],fXids[12]];
			} else if (pageType == this.typeTransition){
				newPage = {"id": newId, "type": pageType, "title": "", "text": "", "c2a" : "", "speed": "3"};
			} else {
				newPage.placeholder = "Field";
				newPage.xid = [dXids[pageType]];
			}
			this.pages.push(newPage);
			event.target.value = "-1";
			this.watchChanges();
		},

		bindBgColor(event, isPageStyle = false){
			document.querySelector("#input-gradient" + (isPageStyle ? "-local" : "")).value = event.target.value;
			this.bindStyle(isPageStyle).gcolor = event.target.value;
			document.querySelector("#bg-color").style.backgroundImage = event.target.value;
			this.refresh();
		},

		bindColor(type, event, isPageStyle = false, gradValue = null){
			if(type == "bg"){
				this.bindBgColor(event, isPageStyle);
				return;
			}
			else if(type == "font"){
				this.bindFont()[3] = event.target.value;
			}
			else if(type == "font-border"){
				this.bindFont()[6] = event.target.value;
			}
			else if(type == "gradient"){
				this.bindGradient(gradValue, event.target.value);
			}
			else{
				this.bindStyle(isPageStyle)[type] = event.target.value;
			}
			this.$forceUpdate();
		},

		bindFont(){
			if (this.fontActive == null)
				return this.bindStyle().par;
			if (!this.invIsGlobalStyle){
				let page = this.getPage();
				if(page.style == undefined)
					page.style = {};
				if(page.style[this.fontActive] == undefined){
					page.style[this.fontActive] = JSON.parse(JSON.stringify(this.style[this.fontActive]));
					document.querySelector("#p-"+this.currPage+" .btn-2").click();
				}
				if(this.fontActive == 'header' && page.style['header'].length < 6){
					page.style['header'].push('none', '#000000');
				}
				return page.style[this.fontActive];
			}
			if(this.fontActive == 'header' && this.style['header'].length < 6){
				this.style['header'].push('none', '#000000');
			}
			return this.bindStyle()[this.fontActive];
		},

		bindGradient(position, value){
			let bgColor = this.getStyleIfExists('gcolor', this.gradientWidgetData[this.GRADIENT_DATA_ISLOCAL]);
			if(bgColor != null && bgColor.length > 8){
				let grad = bgColor.replace("linear-gradient(", "");
				grad = grad.replace(")", "");
				let splitGrad = grad.split(",");
				splitGrad[position] = value;
				grad = splitGrad.join(",");
				grad = "linear-gradient(" + grad + ")";
				let id = "input-gradient" + (this.gradientWidgetData[this.GRADIENT_DATA_ISLOCAL] ? "-local" : "");
				document.getElementById(id).value = grad;
				this.bindBgColor({"target": { "value": grad } }, this.gradientWidgetData[this.GRADIENT_DATA_ISLOCAL]);
			}
		},

		bindStyle(isPageStyle = false){
			if(!isPageStyle || this.currPage == this.DEFAULT_PAGE)
				return this.style;
			if (this.pages[this.currPage].style == undefined){
				this.pages[this.currPage]['style'] = {};
			}
			return this.pages[this.currPage].style;
		},

		calcBrightness(color){
			let value = color.replace("#", "");
			let r = parseInt(Number("0x" + value.substring(0,2)));
			r += (255 - r) / 2;
			let g = parseInt(Number("0x" + value.substring(2,4)));
			g += (255 - g) / 2;
			let b = parseInt(Number("0x" + value.substring(4)));
			b += (255 - b) / 2;
			return "rgb(" + r + "," + g + "," + b + ")";
		},

		calcFontSize(size){
			let rawSize = size;
			if(this.currentDType == this.DEVICE_TYPE_DESKTOP)
				return rawSize + 'px';
			//if(this.currentDType == 2)
			//	return parseInt(Number(rawSize) * 0.9) + 'px';
			return parseInt(Number(rawSize) * 0.8) + 'px';
		},

		changeToLocal(){
			if(this.mayLocal && (this.fontActive == 'header' || this.fontActive == 'par' || this.fontActive == 'fbutton')){
				this.invIsGlobalStyle = false;
				this.refresh(true);
			}
		},

		checkOverrides(option){
			let pageStyle = this.getPage().style;
			if (pageStyle != undefined){
				if (option.length == 1 && pageStyle.img != undefined){
					let imgSet = pageStyle.img[Number(option)];
					return (imgSet != 0 && imgSet != this.style.img[Number(option)]) ? "local-override" : "global-set";
				}
				if (pageStyle[option] != undefined){
					return "local-override";
				}
			}
			return "global-set";
		},

		copyPage(index){
			let newPage = JSON.parse(JSON.stringify(this.pages[index]));
			newPage.id = this.createNewId();
			this.pages.push(newPage);
			this.watchChanges();
			this.$nextTick(() => {
				let el = document.querySelector("#p-"+(this.pages.length - 1)+" .page-display-text");
				el.click();
				window.setTimeout(() => el.scrollIntoView({behavior: "smooth"}), 300);
			});
		},

		createNewId(){
			var newId = 0;
			var amount = this.pages.length;
			for (var i = 0; i < amount; i++){
				if (this.pages[i].id >= newId){
					newId = this.pages[i].id;
				}
			}
			return (newId + 1);
		},

		drag(event, index){
			let el = event.target;
			this.dragId = index;
			el.parentNode.classList.add("dragged");
		},

		dragPrevent(event){
			event.preventDefault();
		},

		dragEnter(event, index){
			let el = event.target;
			if (el.classList.contains("dragged"))
				return;
			el.classList.add("dragover");
		},

		dragLeave(event){
			let el = event.target;
			if (el.classList.contains("dragged"))
				return;
			el.classList.remove("dragover");
		},

		dragStop(event){
			event.target.parentNode.classList.remove("dragged");
		},

		drop(event, index){
			let el = event.target;
			el.classList.remove("dragover");
			let pos1 = index;
			let pos2 = this.dragId;

			if (pos1 < pos2)
				pos1++;
			else if (pos2 == this.DEFAULT_PAGE)
				pos2++;

			let removed = this.pages.splice(pos2, 1)[0];
			this.pages.splice(pos1, 0, removed);
			var fragment = document.createDocumentFragment();
			fragment.appendChild(document.getElementById('page-styling'));
			document.getElementById('not-visible').appendChild(fragment);
			this.watchChanges();
		},

		hexImageGradient(){
			let hex = this.getStyle('gcolor');
			if(hex.length > 8) hex = "#ffffff";
			let c = hex.substring(1).split('');
			c= '0x'+c.join('');
			return 'linear-gradient(rgba(255,255,255,0),rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',1)),';
		},

		getBgColor() {
			let color = this.getStyle('gcolor');
			if (color.length < 8){
				return { backgroundColor: color };
			}
			return { backgroundImage: color };
		},

		getBgImage(preview = null, forceGlobal = false) {
			//let path = window.location.origin + "/storage/landings/" + this.slug + "/";
			let path = window.location.origin + "/storage/editor/" + this.slug + "/";
			let defaultImage = preview == null ? 'none' : "url(" + window.location.origin + "/images/default.png)";
			let currentDevice = (preview == null ? this.currentDType : preview);
			let device = this.devices[currentDevice];
			let pageStyle = this.getPage().style;
			if (!forceGlobal && pageStyle != undefined && pageStyle.img != undefined){
				if(pageStyle.img[currentDevice] > 0){
					if (pageStyle.img[currentDevice] == this.IMG_WITH_FADE) return this.hexImageGradient() + "url('" + path + device + pageStyle.img[this.IMG_PREFIX] + ".jpg?lastmod=" + this.imageUpdateNumber + "')";
					return "url('" + path + device + pageStyle.img[this.IMG_PREFIX] + ".jpg?lastmod=" + this.imageUpdateNumber + "')";
				}
				if(pageStyle.img[currentDevice] == this.IMG_TYPE_NONE) return defaultImage;
			}
			if (this.style.img[currentDevice] > 0){
				if (this.style.img[currentDevice] == this.IMG_WITH_FADE) return this.hexImageGradient() + "url('" + path + "mG.jpg?lastmod=" + this.imageUpdateNumber + "')";
				return "url('" + path + device + this.style.img[this.IMG_PREFIX] + ".jpg?lastmod=" + this.imageUpdateNumber + "')";
			}
			return defaultImage;
		},

		getBgImageStyle() {
			if(this.getPage().type == this.typeLegal){
				this.mainContainer = 'content-container-landscape';
				this.nextButton = "button-next";
				return "editor-bg-image ";
			}
			if (this.getPage().style != undefined && this.getPage().style.img != undefined && this.getPage().style.img[this.currentDType] > 0){
				if (this.currentDType != this.DEVICE_DESKTOP || this.getPage().style.img[this.IMG_FOR_DESKTOP] > 2){
					this.mainContainer = 'content-container-upright';
					this.nextButton = "button-next-bottom";
				}
				else{
					this.mainContainer = 'content-container-landscape';
					this.nextButton = "button-next";
				}
				return this.getPage().style.img[this.currentDType] % 2 == 1 ? "editor-bg-image " : "editor-poster-image ";
			}
			if (this.style.img[this.currentDType] > 0){
				if (this.currentDType != this.DEVICE_DESKTOP || this.style.img[this.IMG_FOR_DESKTOP] > 2){
					this.mainContainer = 'content-container-upright';
					this.nextButton = "button-next-bottom";
				}
				else{
					this.mainContainer = 'content-container-landscape';
					this.nextButton = "button-next";
				}
				return this.style.img[this.currentDType] % 2 == 1 ? "editor-bg-image " : "editor-poster-image ";
			}
			return "editor-bg-image ";
		},

		getChoices() {
			return this.getPage().choices != undefined ? this.getPage().choices : [];
		},

		getColorSolid(id){
			document.querySelector("#"+id).CP.apply();
		},

		getFont(id, textType = 'par', forceLocal = false){
			let type = textType;
			let font = [];
			if (forceLocal)
				font = this.style[type];
			else
				font = this.getStyle(type);
			if (font.length > id)
				return font[id];
			let backup = ['Roboto', 'normal', type == 'header' ? 28 : 16, "#000", 0];
			return backup[id];
		},

		getFontDescription(type, isLocal = false){
			if (isLocal){
				let page = this.getPage();
				if (page.style == undefined || page.style[type] == undefined){
					return "Same as global";
				}
			}
			return this.getFont(this.FONT_NAME, type, !isLocal) + " " + this.getFont(this.FONT_STYLE, type, !isLocal) + ", " + this.getFont(this.FONT_SIZE, type, !isLocal);
		},

		getFontStyle(style){
			let weight = this.getFont(this.FONT_STYLE, style) == "bold" ? 600 : 400;
			let fontStyle = weight == 600 ? "normal" : this.getFont(this.FONT_STYLE, style);
			let fontBorder = this.getFont(this.FONT_BORDER_COLOR, style) == undefined ? 'none' : (this.getFont(this.FONT_BORDER_STYLE, style) + ' ' + this.getFont(this.FONT_BORDER_COLOR, style));
			return {
				fontSize: this.calcFontSize(this.getFont(this.FONT_SIZE, style)), 
				fontFamily: this.getFont(this.FONT_NAME, style), 
				fontWeight: weight, 
				fontStyle: fontStyle, 
				color: this.getFont(this.FONT_COLOR, style),
				textAlign: this.getFont(this.FONT_ALIGN, style),
				borderBottom: fontBorder
			};
		},
		
		getPage(){
			if(this.currPage == this.DEFAULT_PAGE)
				return this.defaultPage;
			return this.pages[this.currPage];
		},

		getStyle(style){
			let page = this.getPage();
			if(page.style != undefined && page.style[style] != undefined){
				return page.style[style];
			}
			return this.style[style];
		},

		getStyleIfExists(style, isLocal){
			if(!isLocal){
				return this.style[style];
			}
			let page = this.getPage();
			if(page.style != undefined && page.style[style] != undefined){
				return page.style[style];
			}
				
			return null;
		},

		getType(type){
			if (type == this.typeSplash) return "Splash Page";
			if (type == this.typeOpenText) return "Open Question (Text)";
			if (type == this.typeOpenNumber) return "Open Question (Number)";
			if (type == this.typeMultipleChoice) return "Multiple Choice";
			if (type == this.typeAddress) return "Address";
			if (type == this.typeEmail) return "E-Mail";
			if (type == this.typePhone) return "Phone";
			if (type == this.typeDOB) return "Date of Birth";
			if (type == this.typeLoading) return "Loading Screen";
			if (type == this.typeThanks) return "Thank You";
			if (type == this.typeFormShort_1) return "Short Form (Part 1)";
			if (type == this.typeFormShort_2) return "Short Form (Part 2)";
			if (type == this.typeFormLong) return "Long Form";
			if (type == this.typeName) return "Name";
			if (type == this.typeCheckboxes) return "Checkboxes";
			if (type == this.typeTransition) return "Transition";
			if (type == this.typeDropdown) return "Dropdown";
			return "Legal Disclaimer";
		},

		getVisibility(index, check) { 
			let type = this.pages[index].device;
			if (type == undefined) return check < 4;
			if (check > 4) return type > 4 || type == 0;
			return (type == check || type == 3);
		},

		hideWidgets(){
			this.imageWidgetData = [false, false, 0];
			this.gradientWidgetData = [false, false];
		},

		hideWysiwygToolbar(){
			this.showToolbar = false;
			this.fontActive = null;
			this.invIsGlobalStyle = false;
			this.mayLocal = false;
		},

		imgToggle(newValue){
			let imgType = this.imageWidgetData[this.IMG_DATA_IMAGE_TYPE];
			let isLocal = this.imageWidgetData[this.IMG_DATA_IS_LOCAL];
			this.imageWidgetData[this.IMG_DATA_FONTNAME] = newValue;
			if (isLocal) {
				if(this.getPage().style == undefined){
					this.getPage().style = [];
				}
				if(this.getPage().style.img == undefined){
					this.getPage().style.img = JSON.parse(JSON.stringify(this.style.img));
				}
				this.getPage().style.img[imgType] = newValue;
			} else {
				this.style.img[imgType] = newValue;
			}
			if (imgType == this.currentDType && imgType == this.IMAGE_FOR_DESKTOP){
				this.mainContainer = newValue < 3 ? 'content-container-landscape' : 'content-container-upright';
			}
			this.nextButton = this.mainContainer == 'content-container-landscape' ? "button-next" : "button-next-bottom";
			this.refresh();
		},

		insertTag(tag){
			let el = document.getElementById("page-" + this.fontActive);
			el.focus();
			let medium = this.fontActive == 'par' ? window.parMedium : window.headerMedium;
			if(tag == 'B') medium.bold();
			if(tag == 'I') medium.italicize();
			if(tag == 'U') medium.underline();
			this.getPage().text = el.innerHTML;
      		this.watchChanges();
		},

		isImgSetForWidget() {
			let currentDevice = this.imageWidgetData[this.IMG_DATA_IMAGE_TYPE];
			let pageStyle = this.getPage().style;
			let isLocal = this.imageWidgetData[this.IMG_DATA_IS_LOCAL];
			if (isLocal && pageStyle != undefined && pageStyle.img != undefined){
				return (this.style.img[currentDevice] > 0 && pageStyle.img[currentDevice] != this.IMG_TYPE_NONE) || (pageStyle.img[currentDevice] > 0);
			}
			return (this.style.img[currentDevice] > 0);
		},

		isImgSet(imgType, isPageStyle = false) { 
			if (isPageStyle){
				if(this.style.img[imgType] == 0 && (this.getPage().style == undefined || this.getPage().style.img == undefined)){
					return false;
				}
				return true;
			} else {
				return this.style.img[imgType] <= 0;
			}
		},

		movePageStylingSection(index, styleButtonSwitch = false) {
			var fragment = document.createDocumentFragment();
			fragment.appendChild(document.getElementById('page-styling'));
			document.getElementById('page-styling-' + index).appendChild(fragment);
			if (styleButtonSwitch){
				let c1 = document.querySelector("#collapse-" + index + " .btn-1");
				let c2 = document.querySelector("#collapse-" + index + " .btn-2");
				c1.classList.remove("btn-dark");
				c2.classList.add("btn-dark");
				c1.classList.add("btn-light");
				c2.classList.remove("btn-light");
			}
		},

		openImagePopup(){
			let imageId = this.currentDType == this.DEVICE_TYPE_DESKTOP ? this.DEVICE_TYPE_DESKTOP : this.DEVICE_TYPE_MOBILE;
			let isLocal = this.getPage().style != undefined && Object.entries(this.getPage().style).length > 0;
			let styleHeader = isLocal ? document.querySelector("#page-list-selected + .collapse-group") : document.getElementById("collapse-global-style");
			if (styleHeader.classList.add("show")){
				styleHeader.click();
			}
			styleHeader.scrollIntoView({behavior: "smooth"});
			if(isLocal) this.allPagesCollapsed = false;
			this.showImageWidget(imageId, isLocal);
		},

		querySel(query){
			return document.querySelector(query);
		},

		refresh(localOnly = false){
			this.watchChanges();
			if(localOnly){
				this.$forceUpdate();
				return;
			}
			this.currPage--;
			this.currPage++;
		},

		removeChoiceOnEmpty(event, index) {
			if (event.target.value == "")
				this.getChoices().splice(index, 1);
		},

		removeImage(){
			let imgType = this.imageWidgetData[this.IMG_DATA_IMAGE_TYPE];
			this.isGlobalStyle = !this.imageWidgetData[this.IMG_DATA_IS_LOCAL];
			if (!this.isGlobalStyle && this.getPage().style != undefined){
				if(this.getPage().style.img == undefined || this.getPage().style.img[this.IMG_PREFIX] == "G"){
					if(this.getPage().style.img == undefined) this.getPage().style.img = JSON.parse(JSON.stringify(this.style.img));
					this.getPage().style.img[imgType] = this.IMG_TYPE_NONE;
					this.refresh();
				}
				else if(this.getPage().style.img != undefined && this.getPage().style.img[imgType] > 0){
					this.saveImage("null", this.devices[imgType] + this.getPage().style.img[this.IMG_PREFIX]);
					this.getPage().style.img[imgType] = this.IMG_TYPE_NONE;
				}
			} else if (this.isGlobalStyle && this.style.img[imgType] > 0){
				this.style.img[imgType] = this.IMG_TYPE_DEFAULT;
				this.saveImage("null", this.devices[imgType] + this.style.img[this.IMG_TYPE_DEFAULT]);
			} 
		},

		removeLocal(event, option){
			if (!this.isGlobalStyle && this.getPage().style[option] != undefined){
				event.preventDefault();
				delete this.getPage().style[option];
				this.refresh();
			}
		},
		
		removePage(index){
			document.querySelector('#drag-and-drop span[data-target="#collapse-'+index+'"]').click();
			this.pages.splice(index, 1);
			this.watchChanges();
		},

		resetToGlobal(){
			if(this.getPage().style != undefined){
				this.getPage().style = [];
				let c1 = document.querySelector("#collapse-" + this.currPage + " .btn-1");
				let c2 = document.querySelector("#collapse-" + this.currPage + " .btn-2");
				c1.classList.remove("btn-light");
				c2.classList.add("btn-light");
				c1.classList.add("btn-dark");
				c2.classList.remove("btn-dark");
				var fragment = document.createDocumentFragment();
				fragment.appendChild(document.getElementById('page-styling'));
				document.getElementById('not-visible').appendChild(fragment);
			}
		},

		save(event){
			var saveButton = event.target;
			var saveAlert = document.getElementById("save-alert");
			saveAlert.classList.add("show");
			saveAlert.innerHTML = "Saving...";
			saveButton.disabled = true;
			var vuePointer = this;
			//let action = window.location.href.replace('#', '') + "/save";
			let action = "save.php";
			let content = '{"pages":' + JSON.stringify(this.pages) + ', "style":' + JSON.stringify(this.style) + '}';
			let formData = new FormData();
			formData.append("content", content);
			formData.append("slug", this.slug);
			axios.post(action, formData, { headers: { 'Content-Type': 'multipart/form-data' } }).then(function (response) { 
				saveButton.disabled = false;
				saveAlert.innerHTML = "Changes saved!";
				vuePointer.areChangesMade = false;
				window.areChangesMade = false;
				$("#save-button").addClass("btn-light").removeClass("btn-dark");
				window.setTimeout(() => saveAlert.classList.remove("show"), 3000);
			}).catch(function (error) {
				console.log(error);
				saveAlert.innerHTML = "Error, see console";
				window.setTimeout(() => saveAlert.classList.remove("show"), 5000);
			});
		},

		saveImage(file, name){
			//let action = window.location.href.replace('#', '') + "/save";
			let action = "save.php";
			let formData = new FormData();
			var vuePointer = this;
			vuePointer.refresh(true);
			formData.append("image", file);
			formData.append("id", name);
			formData.append("slug", this.slug);
			axios.post(action, formData, { headers: { 'Content-Type': 'multipart/form-data' } }).then(function (response) {
				vuePointer.imageUpdateNumber++;
				window.setTimeout(function() {
					vuePointer.refresh(); 
				}, 1000);
			});
		},

		saveLocales(){
			let inputs = document.querySelectorAll("#localeModal textarea");
			let count = inputs.length;
			let values = [];
			for(var i = 0; i < count; i++){
				values.push(inputs[i].value);
			}
			this.error = values;
			//let action = window.location.href.replace('#', '') + "/save";
			let action = "save.php";
			let formData = new FormData();
			formData.append("locales", values.join("¿"));
			formData.append("slug", this.slug);
			axios.post(action, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
		},

		saveXids(){
			let fields = document.querySelectorAll("#xid-fields input");
			let values = [];
			fields.forEach(el => {
				values.push(el.value);
			});
			this.getPage().xid = values;
			this.watchChanges();
		},

		selectImage(){
			let imgType = this.imageWidgetData[this.IMG_DATA_IMAGE_TYPE];
			this.isGlobalStyle = !this.imageWidgetData[this.IMG_DATA_IS_LOCAL];
			document.getElementById("input-image").click();
			this.imageUploadType = imgType;
		},

		setPar(event = null){
			if(event != null){
				this.getPage().text = event.target.innerHTML;
			}
			else {
				this.getPage().text = document.getElementById('page-par').innerHTML;
			}
		},

		setTitle(event = null){
			if(event != null){
				this.getPage().title = event.target.innerHTML;
			}
			else {
				this.getPage().title = document.getElementById('page-header').innerHTML;
			}
		},

		setPreviewDimensions(width, height){
			var x = width;
			var y = height;
			if (width == -1 || height == -1){
				if(this.previewDimensions.width.length > 5) return;
				x = Number(this.previewDimensions.height.slice(0, 3));
				y = Number(this.previewDimensions.width.slice(0, 3));
			}

			this.previewDimensions.width = x + 'px';
			this.previewDimensions.height = y + 'px';
			this.previewDimensions.top = 'max(0px, calc(50vh - ' + ((y + 50) / 2) + 'px))';
			this.previewDimensions.left = 'max(0px, calc(50vw - ' + ((x + 380) / 2) + 'px))';
			let isDesktop = x > 700 && y > 500;
			let isTablet = x > 500;
			document.getElementById("device-r").disabled = x > 900;
			
			if (isDesktop){
				if (this.getPage().style != undefined && this.getPage().style.img != undefined && this.getPage().style.img[this.IMG_FOR_DESKTOP] > 2){
					this.mainContainer = 'content-container-upright';
				} else if (this.style.img[this.IMG_FOR_DESKTOP] > 2) {
					this.mainContainer = 'content-container-upright';
				}
				else {
					this.mainContainer = 'content-container-landscape';
				}
			}
			else {
				this.mainContainer = 'content-container-upright';
			}
			this.currentDType = isDesktop ? 1 : 3;
			document.getElementById("dimensions").innerHTML = "Dimensions: " + x + " * " + y;
		},

		setVisibility(event, index, type){
			if (this.pages[index].device == undefined)
				this.pages[index]["device"] = 3;
			let device = this.pages[index].device;
			if (device == 2 && type == 1)
				device = 3;
			else if (device < 4)
				device = device < type ? device + type : device - type;
			else if (type > 4)
				device -= type;
			else
				device = type;
			this.pages[index].device = device;
			event.target.focus();
			this.$forceUpdate();
		},

		show(event, index){
			this.hideWysiwygToolbar();
			if (document.querySelector(event.target.getAttribute("data-target")).classList.contains("collapsing"))
				return;
			
			if(this.currPage == index){
				this.allPagesCollapsed = true;
				return;
			}
			
			this.currPage = index;
			this.allPagesCollapsed = false;
			let page = this.getPage();
			let isStyleSet = (this.fontActive != null && page.style != undefined && page.style[this.fontActive] != undefined);

			this.invIsGlobalStyle = this.fontActive == null ? true : !isStyleSet;
			let prev = document.getElementById("page-list-selected");
			if (prev != null)
				prev.id = "";
			event.target.parentNode.id = "page-list-selected";
			
			if (this.pages[index].type == this.typeLegal){
				this.isGlobalStyle = true;
				document.getElementById("legal-text").innerHTML = this.legal;
			}

			if(this.pages[index].style != undefined && Object.keys(this.pages[index].style).length > 0){
				this.movePageStylingSection(index);
			}

			document.getElementById('page-par').innerHTML = this.getPage().text;
			document.getElementById('page-header').innerHTML = this.getPage().title;
			document.querySelector("#normal-color-local").value = this.getStyle("gcolor").length < 8 ? this.getStyle("gcolor") : "#000000";
			//this.nextButton = this.mainContainer == 'content-container-landscape' ? "button-next" : "button-next-bottom";
		},

		showImageWidget(imgType = 0, isLocal = false){
			let shouldShow = imgType > 0 && !this.imageWidgetData[this.IMG_DATA_VISIBLE];
			this.imageWidgetData = [shouldShow, isLocal, imgType];
			if (isLocal) {
				if(this.getPage().style != undefined && this.getPage().style.img != undefined)
					this.imageWidgetData[this.IMG_DATA_FONTNAME] = this.getPage().style.img[imgType];
				else
					this.imageWidgetData[this.IMG_DATA_FONTNAME] = this.style.img[imgType];
			} else {
				this.imageWidgetData[this.IMG_DATA_FONTNAME] = this.style.img[imgType];
			}
		},

		showGradientWidget(isLocal = false){
			let shouldShow = !this.gradientWidgetData[this.GRADIENT_DATA_VISIBLE];
			this.gradientWidgetData = [shouldShow, isLocal];
			let bgColor = this.getStyleIfExists('gcolor', isLocal);
			if(bgColor == null){
				this.getPage()['gcolor'] = this.style.gcolor;
				bgColor = this.style.gcolor;
			}
			if(bgColor.length < 8){
				let newBgColor = "linear-gradient(0deg,"+bgColor+",50%,"+bgColor+")";
				document.getElementById("input-gradient" + (isLocal ? "-local" : "")).value = newBgColor;
				this.bindBgColor({"target": { "value": newBgColor } }, isLocal);
				bgColor = newBgColor;
			}
			let grad = bgColor.replace("linear-gradient(", "");
			grad = grad.replace(")", "");
			let splitGrad = grad.split(",");
			document.getElementById("grad-d" + splitGrad[0]).click();
			document.getElementById("grad-color-1").value = splitGrad[1];
			document.getElementById("grad-per").value = splitGrad[2];
			document.getElementById("grad-color-2").value = splitGrad[3];
			this.refresh();
		},

		showWysiwygToolbar(event, type, isLocal = false, mayLocal = false){
			this.fontActive = type;
			this.showToolbar = true;
			if(isLocal) this.invIsGlobalStyle = false;
			else this.invIsGlobalStyle = this.getStyleIfExists(type, true) == null;
			this.mayLocal = mayLocal;
			this.currFont = this.bindFont()[0];
		},

		showXids(){
			let mbody = document.getElementById("xid-fields");
			mbody.innerHTML = "";
			var inner = "<div class='d-flex flex-column w-50'>";
			let xids = this.getPage().xid;
			if(xids == undefined || xids.length == 0) {
				mbody.innerHTML = "<h4>No integration fields for this page</h4>";
				return;
			}
			let type = this.getPage().type;
			var labels = [this.getType(type)];
			if(type == this.typeAddress) labels = ["Postal code", "House number", "Addition", "Street", "City"];
			else if(type == this.typeName) labels = ["Title", "First name", "Last name"];
			else if(type == this.typeFormLong) labels = ["Title", "First name", "Last name", "Email", "Date of birth", "Postal code", "House number", "Addition", "Street", "City", "Phone number"];
			else if(type == this.typeFormShort_1) labels = ["Title", "First name", "Last name", "Email", "Date of birth"];
			else if(type == this.typeFormShort_2) labels = ["Postal code", "House number", "Addition", "Street", "City", "Phone number"];
			labels.forEach(el => {
				inner += "<div class='xid-label'>"+el+"</div>";
			});
			inner += "</div><div class='d-flex flex-column w-50'>";
			xids.forEach(el => {
				inner += "<input class='maintain mb-2' type='text' value='"+el+"'>";
			});
			inner += "</div>";
			mbody.innerHTML = inner;
		},

		uploadImage(event){
			let name = "";
			if(this.isGlobalStyle){
				this.style.img[this.IMG_PREFIX] = "G";
				if(this.style.img[this.imageUploadType] == this.IMG_TYPE_DEFAULT)
					this.style.img[this.imageUploadType] = 1;
				name = this.devices[this.imageUploadType] + "G";
			} else {
				let page = this.pages[this.currPage];
				if (page.style == undefined){
					page['style'] = {};
				}
				if (page.style.img == undefined){
					page.style['img'] = ["",0,0,0];
				}
				let img = page.style.img;
				if(img[this.IMG_PREFIX] == "G"){
					page.style.img[this.IMG_FOR_DESKTOP] = img[this.IMG_FOR_DESKTOP] == this.IMG_TYPE_NONE ? this.IMG_TYPE_NONE : 0;
					page.style.img[this.IMG_FOR_MOBILE] = img[this.IMG_FOR_MOBILE] == this.IMG_TYPE_NONE ? this.IMG_TYPE_NONE : 0;
				}
				page.style.img[this.IMG_PREFIX] = this.getPage().id;
				if(img[this.imageUploadType] <= 0)
					page.style.img[this.imageUploadType] = 1;
				name = this.devices[this.imageUploadType] + this.getPage().id;
			}
			let file = event.target.files[0];
			this.saveImage(file, name);
		},

		watchChanges(){
			if(!this.areChangesMade){
				this.areChangesMade = true;
				window.areChangesMade = true;
				$("#save-button").addClass("btn-dark").removeClass("btn-light");
			}
		}
	},

	mounted: function() {
		window.editorRef = this;
		window.setTimeout(function(e){
			if(window.editorRef.pages.length > 0)
				document.querySelector("#p-0 .page-display-text").click();
		}, 500);
		if (this.getStyle("gcolor").length < 8)
			document.querySelector("#normal-color").value = this.getStyle("gcolor");
	}
}
</script>