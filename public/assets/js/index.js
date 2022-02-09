$("#toggle-display-list").on("click", function(){
  let el = $("#main-body");
  document.cookie = "layout=list; SameSite=Strict; max-age=2600000";
  if (el.attr("class").search("layout-grid") > 0)
      el.addClass("layout-list").removeClass("layout-grid");
});

$("#toggle-display-block").on("click", function(){
  let el = $("#main-body");
  document.cookie = "layout=grid; SameSite=Strict; max-age=2600000";
  if (el.attr("class").search("layout-list") > 0)
      el.addClass("layout-grid").removeClass("layout-list");
});

$(".search-item").on("change", function(){
  let input = $(this)[0];
  window.setTimeout(function(){
      if($(".search-item:focus").length > 0 && input.id != "search-country") return;
      let href = window.location.href.split('?')[0] + "?id=" + $("#search-id").val() + "&name=" + $("#search-name").val() + "&country_id=" + $("#search-country").val();
      window.location.href = href;
  }, 100)
});

function setLandingActivity(event, id){
  $.ajaxSetup({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
  });

  let isActive = event.target.checked ? 1 : 0;
  let action = window.location.href.split("?")[0] + "/save.php";

  $.ajax({
      url: action,
      type: 'POST',
      dataType: "json",
      data: {
          action: 'activate',
          active: isActive,
          id: id
      }
  });	
}

function openSelect(elementId) {
  document.getElementById(elementId).classList.toggle("show");
};

function confirmBeforeDelete(formName, event){
  event.preventDefault();
  let c = window.confirm("Are you sure you want to delete this?");
  let form = document.getElementById(formName);
  if (c == true){
      form.submit();
  }
}

window.onclick = function(event) {
  if (!event.target.matches('.fa-ellipsis-h')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
              openDropdown.classList.remove('show');
          }
      }
  }
}