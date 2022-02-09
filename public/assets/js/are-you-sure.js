$("input:not(.r-hidden)").on("change", function(){
  if(window.editorRef == undefined) return;
  window.editorRef.watchChanges();
});

$("div.text-input").on("input", function(){
  if(window.editorRef == undefined) return;
  window.editorRef.watchChanges();
});

$(window).bind('beforeunload', function(e){
  if(window.editorRef == undefined) e = null;
  if (window.editorRef.areChangesMade){
    return true;
  }
  else {
    e = null;
  }
});

$(window).on('mouseup', function(e){
  let target = $(e.target);
  if(target.parents("div.text-input").length){
    let areClicked = [false, false, false];
    let baseClasses = "fa fa-lg wysiwyg-icon";
    areClicked[0] = (target.prop("tagName") == "B" || target.parents("b").length);
    areClicked[1] = (target.prop("tagName") == "I" || target.parents("i").length);
    areClicked[2] = (target.prop("tagName") == "U" || target.parents("u").length);
    $("#icon-bold").attr("class", baseClasses + " fa-bold" + (areClicked[0] ? " active" : ""));
    $("#icon-italic").attr("class", baseClasses + " fa-italic" + (areClicked[1] ? " active" : ""));
    $("#icon-underline").attr("class", baseClasses + " fa-underline" + (areClicked[2] ? " active" : ""));
  }
  else if(target.attr('id') == "page-header" || target.attr('id') == "page-par"){
    $("#icon-bold").removeClass("active");
    $("#icon-italic").removeClass("active");
    $("#icon-underline").removeClass("active");
  }
  else if(target.attr('id') == "icon-bold"){
    $("#icon-bold").toggleClass("active");
  }
  else if(target.attr('id') == "icon-italic"){
    $("#icon-italic").toggleClass("active");
  }
  else if(target.attr('id') == "icon-underline"){
    $("#icon-underline").toggleClass("active");
  }
});