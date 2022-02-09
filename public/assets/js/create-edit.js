$("#submit-button").on("click", function(event){
  let isValid = true;
  $(".form-control").each( function() {
    if($(this).val() == "" && $(this).hasClass('required')){
      isValid = false;
      $(this).addClass("is-invalid");
    }
  });
  if(!isValid){
    event.preventDefault();
  }
});

$(".form-control").on("input", function(event){
  event.target.classList.remove("is-invalid");
});

$("#see-legal").on("click", function(event){
  let legalId = $("#legal-select").val();
  $("#legal-"+legalId).toggleClass("d-none");
});