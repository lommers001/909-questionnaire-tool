$(document).ready(function () {
  window.answers = [];
  window.pageIds = [];
  window.pageTypes = [];
  window.disclaimer = null;
  window.leadId = null;
  for(var i = 0; i < window.amountOfPages; i++){
    window.answers.push("");
    window.pageIds.push(0);
    window.pageTypes.push(0);
  }

  $.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
  });

  //For mobile - hide button when on-screen keyboard is up
  $("input:not([type='radio']):not([type='checkbox'])").on('focus', function() {
    $(".btn-dev-1:not(.never-hide)").addClass("hide-on-mobile");
  });
  $("input:not([type='radio']):not([type='checkbox'])").on('focusout', function() {
    $(".btn-dev-1:not(.never-hide)").removeClass("hide-on-mobile");
  });

  //Go to next input field (DOB in form)
  $(".jump-to-next-form").on('keyup', function(event) {
    let $input = $( this );
    let valueN = Number($input.val());
    //Check if ENTER was pressed
    if((event.keyCode == 13) || (($input[0].id == "fi4" || $input[0].id == "fi4s") && valueN > 3) || (($input[0].id == "fi5" || $input[0].id == "fi5s") && valueN > 1)){
      $input.parent().next().next().children()[0].focus();
    }
  });

  //Automaticaly save the answer - single input field
  $(".autofill").change(function() {
    let $input = $( this );
    window.answers[window.currentPage] = $input.val();
  });

  $(".autofill").keyup(function(event) {
    let $input = $( this );
    let inputType = $input.attr('type');
    let answer = $input.val();
    if (inputType == "email"){
      setButtonActivity((/^[a-zA-Z0-9.!#$%&’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-]+/.test(answer)));
    }
    else if (inputType == "tel"){
      setButtonActivity(answer.length > 3);
    }
    else {
      setButtonActivity(answer.length > 0);
    }
    if (event.keyCode == 13){
      if (answer.length == 0){
        return;
      }
      $(".btn-" + window.currentPage).click();
    }
  });
  //Go to the input field - For forms
  $(".jump-to-input").click(function(){
    $( this ).parent().next().children()[0].children[0].focus();
  });
  //Automaticaly save the answer - name
  $(".namefill").keyup(function(event) {
    let isReadyForNext = false
    //Check if ENTER was pressed
    if (event.keyCode == 13){
      let $input = $( this );
      if ($input.val() == ""){
        return;
      }
      if($input.hasClass("jump-to-next")){
        $input.parent().next().next().children()[0].focus();
      }
      else {
        isReadyForNext = true;
      }
    }
    let radioField = $("#page-container-"+window.currentPage+" .namefill:checked");
    let textFields = $("#page-container-"+window.currentPage+" .namefill.ph-font");
    if(radioField.length == 0 || textFields[0].value == "" || textFields[1].value == ""){
      setButtonActivity(false);
      return;
    }
    window.answers[window.currentPage] = radioField[0].value + "¿" + textFields[0].value + "¿" + textFields[1].value;
    setButtonActivity(true);
    if(isReadyForNext){
      $(".btn-" + window.currentPage).click();
    }
  });
  //Automaticaly save the answer - DOB
  $(".datefill").keyup(function(event) {
    let $input = $( this );
    let nval = Number($input.val());
    //Check if ENTER was pressed
    if (event.keyCode == 13 || ($input.attr("maxlength") != "" && nval > Number($input.attr("maxlength")))){
      if ($input.val() == ""){
        return;
      }
      if($input.hasClass("jump-to-next")){
        $input.parent().parent().next().children()[0].children[0].focus();
      }
      else {
        $(".btn-" + window.currentPage).click();
      }
    }
    let day = $('#day_' + window.currentPage).val();
    let month = $('#month_' + window.currentPage).val();
    let year = $('#year_' + window.currentPage).val();
    if (day != "" && month != "" && year != ""){
      month = month.length == 2 ? month : "0" + month;
      day = day.length == 2 ? day : "0" + day;
      let answer = year + "-" + month + "-" + day;
      window.answers[window.currentPage] = answer;
      setButtonActivity(true);
    }
    if (day == "" || month == "" || year.length != 4){
      setButtonActivity(false);
    }
  });
  //Automaticaly save the answer - address
  $(".addressfill").keyup(function(event) {
    let $input = $( this );
    //Check if ENTER was pressed
    if (event.keyCode == 13 || $input.val().length == Number($input.attr("maxlength"))){
      if ($input.val() == ""){
        return;
      }
      if($input.hasClass("jump-to-next")){
        $input.parent().parent().next().children()[0].children[0].focus();
      }
      else {
        $input.blur();
      }
    }
  });

  $('a[href="#"]').click(function() {
    window.legalDenied = true;
    $('#legalModal').click();
    $('.btn-'+window.currentPage).click();
  });

  $('nav input').on("change", function() {
    changeDimensions();
  });

  $('#dim-swap').on("click", function() {
    console.log("test");
    let temp = $('#dim-w').val();
    $('#dim-w').val($('#dim-h').val());
    $('#dim-h').val(temp);
    changeDimensions();
  });
  //Prepare loading bar
  if(document.getElementById("progressbar") != null){
    window.loadingBar = document.getElementById("progressbar");
    window.loadingBarWidth = 0;
    window.loadingBar.style.width = window.loadingBarWidth + "%";
  }

  if( $('#page-container-0 input').length == 0)
    $('.btn-0').focus();
  else
    $('#page-container-0 input').first().focus();
});

//For mobile preview
function changeDimensions(){
  let width = $('#dim-w').val(), height = $('#dim-h').val();
  let hwidth = Number(width) / 2, hheight = Number(height) / 2;
  $('.page').css({width: width+"px", height: height+"px", top: "calc(max(0px, 50vh - "+hheight+"px))", left: "calc(50vw - "+hwidth+"px)"});
  $('.under-button.btn-dev-3').css({width: width+"px", bottom: "calc(max(0px, 50vh - "+hheight+"px))", left: "calc(50vw - "+hwidth+"px)"});
  $('.btn-next.btn-dev-3, .btn-next-fake.btn-dev-3').css({width: "calc("+width+"px - 2rem)", bottom: "calc(max(1rem, 50vh - "+hheight+"px + 1rem))", left: "calc(50vw - "+hwidth+"px + 1rem)"});
}

function swapDevice(device){
  if(document.getElementById('dim-w') != null){
    let splitURL = window.location.href.split('&');
    let params = "&device=" + device + "&width=" + $('#dim-w').val() + "&height=" + $('#dim-h').val();
    window.location.href = splitURL[0] + params;
  }
  else {
    window.location.href = window.location.href.replace("&device=desktop", "&device=mobile");
  }
}

//For legal confirmation - Mobile
function confirmLegalMob(){
  window.legalConfirmed = window.legalDenied == undefined;
  window.disclaimer = $(".modal-body").text();
  id = window.id;
  if((id + 2) >= window.pageIds.length){
    saveAnswers();
    return;
  }
  $('.error-message').css("display", "none");
  $('#page-container-' + id).removeClass("d-flex").addClass("d-none");
  $('#page-container-' + (id + 2)).removeClass("d-none").addClass("d-flex");
  if( $('#page-container-' + (id + 2) + ' input').length == 0)
    $('.btn-' + (id + 2)).focus();
  else
    $('#page-container-' + (id + 2) + ' input').first().focus();
  this.next(id + 1);
}
//For legal confirmation - Desktop
function confirmLegalDesk(){
  window.legalConfirmed = window.legalDenied == undefined;
  window.disclaimer = $("#legal-text").text();
  id = window.id;
  if((id + 1) >= window.pageIds.length)
    saveAnswers();
  else
    this.next(id + 1);
}

//Show dropdown list
function showDropdownList(e){
  let listContainer = e.nextElementSibling;
  listContainer.classList.remove('list-hide');
  let options = listContainer.querySelectorAll('label');
  options.forEach( function(option) {
    let optionId = option.getAttribute('data-id');
    option.addEventListener('click', function(){ return setMCAnswer('.btn-' + optionId)});
  });
}

//Give an element focus
function xfocus(el){
  window.setTimeout(() => {
    $(el).focus();
  }, 100);
}
//Give an element focus - For forms
function xfocusOnKeyUp(event, el, el2 = null){
  if(el[1] == 'b' && event.target.value.length > 3){
    setButtonActivity(true);
  }
  //Regular input - highlight next field in form but do not give focus
  if(event.keyCode != 13){
    if (el[1] != 'f'){
      $(el).next().removeClass("muted");
    }
  }
  //If ENTER was pressed - give focus to next field in form
  else{
    if (el[1] == 'f'){
      $(el).focus();
    } else {
      $(el).click();
      if (el2 != null){
        xfocus(el2);
      }
    }
  }
}

//Go to the next page
function next(id, showLegalMob){
  $('.error-message').css("display", "none");
  $('.btn-' + id).prop("disabled", true);
  //For mobile - show legal disclaimer if it's next
  if(showLegalMob){
    window.id = id;
    window.currentPage++;
    $('#showLegalButton').click();
    return;
  }

  //For transition to next page
  $("html, body").animate({ scrollTop: 0 }, "fast");  
  $('#page-container-' + (id + 1)).addClass("page-hide").removeClass("d-none").addClass("d-flex");
  window.setTimeout( () => {
    $('#page-container-' + (id + 1)).removeClass("page-hide");
  }, 10 );
  window.setTimeout( () => {
    $('#page-container-' + id).removeClass("d-flex").addClass("d-none");
    if( $('#page-container-' + (id + 1) + ' input').length == 0)
      $('.btn-' + (id + 1)).focus();
    else
      $('#page-container-' + (id + 1) + ' input').first().focus();
    window.id = id;
    window.currentPage++;
  }, 210 );
}

//Save the answers - send them to eternal party
function saveAnswers(){
  var nextButton = $('.btn-next.btn-'+window.currentPage);
  var nextButtonHtml = nextButton.html();
  nextButton.prop('disabled', true);
  nextButton.html("<b class='loading-spinner'>&bowtie;</b>&nbsp;"+nextButtonHtml);
  let action = "/save.php";
  let cleaned_answers = [];
  for(var i = 0; i < window.amountOfPages; i++){
    if (window.answers[i] != "")
      cleaned_answers.push(window.answers[i]);
  }

  $.ajax({
    url: action,
    type: 'POST',
    dataType: "json",
    data: {
      response: cleaned_answers,
      disclaimer: window.disclaimer,
      integration: window.integrationData,
      slug: window.slug,
      lead_id: window.leadId
    },
    success: function(data, status, xhr) {
      nextButton.prop('disabled', false);
      nextButton.html(nextButtonHtml);
      console.log(data);
      //window.location.href = window.redirectURL;
      if(data.debugMessage != undefined){
        alert("End reached - No data was sent")
      }
      else{
        alert("End reached & data sent");
      }
    }, 
    error: function(data, status, xhr){
      nextButton.prop('disabled', false);
      nextButton.html(nextButtonHtml);
      console.log(data);
      if(data.lead_id != undefined)
        window.leadId = data.lead_id;
      alert("Error - see console");
    }
  });
}

//Determines if button to go to the next page should be active or not
function setButtonActivity(isActive, index = null){
  if(index == null) index = window.currentPage;
  if(isActive && $('.btn-next.btn-'+index).hasClass('d-none')){
    $('.btn-next.btn-'+index).removeClass("d-none");
    $('.btn-next-fake.btn-'+index).addClass("d-none");
  }
  else if(!isActive && $('.btn-next-fake').hasClass('d-none')){
    $('.btn-next.btn-'+index).addClass("d-none");
    $('.btn-next-fake.btn-'+index).removeClass("d-none");
  }
}

//Hotfix for answering multiple-choice questions
function setMCAnswer(button){
    window.setTimeout(function(){$(button).click();}, 150);
}
//For setting the answer to multiple choice where more than one choice may be selected
function setMultiSelectAnswer(event, order, index, max){
  let answer = event.target.innerHTML;
  if(window.answers[window.currentPage] == ""){
    window.answers[window.currentPage] = [];
    for (let i = 0; i < max; i++) {
      window.answers[window.currentPage].push(null);
    }
  }
  if(window.answers[window.currentPage][index] == null)
    window.answers[window.currentPage][index] = answer;
  else
    window.answers[window.currentPage][index] = null;

  let isAtLeastOneChosen = false;
  for (let i = 0; i < max; i++) {
    if(window.answers[window.currentPage][i] != null){
      isAtLeastOneChosen = true;
      break;
    }
  }
  setButtonActivity(isAtLeastOneChosen, order);
}

//Shows an error message
function showError(message){
  $('#page-container-'+(window.id + 1)+' .error-message').css("display", "inline-block").html("&#9888; "+message)[0].scrollIntoView();
}

//Check answer for validation
function validate(answer, type){
  if (answer == "" && type != window.fieldTypes.noValidation){
    if (type == window.fieldTypes.dateOfBirth || type == window.fieldTypes.address || type == window.fieldTypes.name || type > 50) {
      showError(window.errorMessages.multiple_empty_fields);
      return false;
    }
    if (type == window.fieldTypes.number || type == window.fieldTypes.numberInForm){
      showError(window.errorMessages.not_a_number);
      return false;
    }
    showError(window.errorMessages.empty_field);
    return false;
  }
  if (type == window.fieldTypes.phone || type == window.fieldTypes.phoneInForm) {
    if((answer.length != 4 && (answer.length < 10 || answer.length > 12)) || /\D/.test(answer)){
      showError(window.errorMessages.invalid_phone_number);
      return false;
    }
  }
  if ((type == window.fieldTypes.email || type == window.fieldTypes.emailInForm) && !(/^[a-zA-Z0-9.!#$%&’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-]+/.test(answer))){
    showError(window.errorMessages.invalid_email);
    return false;
  }
  if (type == window.fieldTypes.dateOfBirth){
    if(isNaN(Date.parse(answer))){
      showError(window.errorMessages.invalid_date);
      return false;
    }
    let date = new Date(answer);
    if(Number(date.getFullYear()) < 1903 ){
      showError(window.errorMessages.date_too_far_in_past);
      return false;
    }
    if(date.getTime() > Date.now() ){
      showError(window.errorMessages.date_in_future);
      return false;
    }
  }
  return true;
}

//After button to go to the next page is pressed - Validate answer and then go to next page (or end questionnaire) if valid
function validateOrNext(pageType, isLast, id, pageId, showLegalMob = false){
  if($('.btn-next.btn-'+id).is(':disabled'))
    return;

  window.pageTypes[id] = pageType;
  let type = Number(pageType);
  if(type == window.fieldTypes.splashScreen || type == window.fieldTypes.endScreen || type == window.fieldTypes.legal){
    if (isLast){
      saveAnswers();
      return;
    }
    this.next(id, showLegalMob);
    return;
  }

  if(type == window.fieldTypes.checkBoxes){
    let chosen = [];
    window.answers[window.currentPage].forEach((el) => { if(el != null) chosen.push(el) });
    window.answers[window.currentPage] = chosen.join(", ");
  }
  
  let answer = window.answers[window.currentPage];
  window.pageIds[window.currentPage] = pageId;
  let isValid = true;

  //For simple form pages
  if (type < 10 || type == window.fieldTypes.name){
    isValid = validate(answer, type);
  }
  //For complex form page (short part 1)
  else if (type == window.fieldTypes.formShort1){
    formAnswers = [];
    let radioAnswer = $("#page-container-"+window.currentPage+' input[name="gender"]:checked');
    if (radioAnswer.length == 0){
      showError(window.errorMessages.multiple_empty_fields);
      return;
    }
    formAnswers.push(radioAnswer.val());
    var ftypes = [
      window.fieldTypes.genderInForm,
      window.fieldTypes.textInForm,
      window.fieldTypes.textInForm,
      window.fieldTypes.emailInForm,
      window.fieldTypes.numberInForm,
      window.fieldTypes.numberInForm,
      window.fieldTypes.numberInForm
    ];
    for(var i = 1; i < 7; i++){
      let fa = $("#fi"+i+"s").val();
      if(validate(fa, ftypes[i])){
        formAnswers.push(fa);
      }
      else{
        isValid = false;
        break;
      }
    }
    if(isValid){
      let day = formAnswers[4].length == 2 ? formAnswers[4] : "0" + formAnswers[4];
      let month = formAnswers[5].length == 2 ? formAnswers[5] : "0" + formAnswers[5];
      let year = formAnswers[6];
      var dateAnswer = year + '-' + month + '-' + day;
      console.log(dateAnswer);
      isValid = validate(dateAnswer, window.fieldTypes.dateOfBirth);
    }
    if(isValid){
      let answerString = "";
      answerString += (formAnswers[0] + "¿" + formAnswers[1] + "¿" + formAnswers[2] + "¿");
      answerString += (formAnswers[3] + "¿");
      answerString += (dateAnswer);
      window.answers[window.currentPage] = answerString;
    }
  }
  // form page (short part 2)
  else if (type == window.fieldTypes.formShort2){
    formAnswers = [];
    var ftypes = [
      window.fieldTypes.phoneInForm,
      window.fieldTypes.textInForm
    ];
    for(var i = 10; i < 12; i++){
      let fa = $("#fi"+i+"s").val();
      if(validate(fa, ftypes[i-10])){
        formAnswers.push(fa);
      }
      else{
        isValid = false;
        break;
      }
    }
    if(isValid){
      let answerString = "";
      answerString += (formAnswers[1] + "¿");
      answerString += (formAnswers[0]);
      window.answers[window.currentPage] = answerString;
    }
  }
  //For complex long form page
  else if (type == window.fieldTypes.formLong){
    formAnswers = [];
    let radioAnswer = $("#page-container-"+window.currentPage+' input[name="gender"]:checked');
    if (radioAnswer.length == 0){
      showError(window.errorMessages.multiple_empty_fields);
      return;
    }
    formAnswers.push(radioAnswer.val());
    var ftypes = [
      window.fieldTypes.genderInForm,
      window.fieldTypes.textInForm,
      window.fieldTypes.textInForm,
      window.fieldTypes.emailInForm,
      window.fieldTypes.numberInForm,
      window.fieldTypes.numberInForm,
      window.fieldTypes.numberInForm,
      window.fieldTypes.noValidation,
      window.fieldTypes.noValidation,
      window.fieldTypes.noValidation,
      window.fieldTypes.phoneInForm,
      window.fieldTypes.textInForm];
    for(var i = 1; i < 12; i++){
      let fa = $('#fi'+i).val();
      if(validate(fa, ftypes[i])){
        formAnswers.push(fa);
      }
      else{
        isValid = false;
        break;
      }
    }
    if(isValid){
      let day = formAnswers[4].length == 2 ? formAnswers[4] : "0" + formAnswers[4];
      let month = formAnswers[5].length == 2 ? formAnswers[5] : "0" + formAnswers[5];
      let year = formAnswers[6];
      var dateAnswer = year + '-' + month + '-' + day;
      console.log(dateAnswer);
      isValid = validate(dateAnswer, window.fieldTypes.dateOfBirth);
    }
    if(isValid){
      let answerString = "";
      answerString += (formAnswers[0] + "¿" + formAnswers[1] + "¿" + formAnswers[2] + "¿");
      answerString += (formAnswers[3] + "¿");
      answerString += (dateAnswer + "¿");
      answerString += (formAnswers[11] + "¿");
      answerString += (formAnswers[10]);
      window.answers[window.currentPage] = answerString;
    }
  }

  console.log(window.answers);

  if(!isValid){
    return;
  }
  
  if (isLast){
      saveAnswers();
    return;
  }
  
  this.next(id, showLegalMob);
}