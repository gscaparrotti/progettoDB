$(document).ready(function() {
  $(".tabella_prodotti").mouseenter(function() {
    $(this).css( 'cursor', 'pointer' );
  })
});

$(document).ready(function() {
  $(".menu_item").mouseenter(function() {
    $(this).css( 'cursor', 'pointer' );
    $(this).css("background-color",'#ffe0b3');
  })
});

$(document).ready(function() {
  $(".menu_item").mouseleave(function() {
    $(this).css("background-color","inherit");
  })
});

$(document).ready(function() {
  $(".tabella_prodotti").click(function() {
    $.bbq.pushState({"url":"dettaglio.php", "params": {"id" : $(this).attr("id")}});
  })
});

$(document).ready(function() {
  $(".detail_button").click(function() {
    $.bbq.pushState({"url":"dettaglio.php", "params": {"id" : $(this).attr("id")}});
  })
});

$(document).ready(function() {
  $(".result_pages_element").click(function() {
    if ($.deparam.fragment()['url'] === "prodotti.php") {
      $.bbq.pushState({"url":"prodotti.php", "params": {"page" : $(this).html()}});
    } else if ($.deparam.fragment()['url'] === "ris_ricerca.php") {
      $.bbq.pushState({"url":"ris_ricerca.php", "params": {"ricerca" : document.getElementById("search_field").value, "page" : $(this).html()}});
    }
  });
  $(".result_pages_element").mouseenter(function() {
    $(this).css( 'cursor', 'pointer' );
  });
});

$(document).ready(function() {
  $(".buy_button").click(function() {
    $.bbq.pushState({"url":"dettaglio.php", "params": {"id" : $(this).next(".detail_button").attr("id")}});
  })
});

$(document).ready(function() {
  $("#search_field").keyup(function(event){
      if(event.keyCode === 13){
          $("#send_button").click();
      }
  });
});

function type_change(select) {
    $(".type").css('cssText', "display:none !important");
    if (select.value == 1) {
        $(".ampli").css('cssText', "display:flex !important");
    } else if (select.value == 2) {
        $(".diff").css('cssText', "display:flex !important");
    } else if (select.value == 3) {
        $(".lettore").css('cssText', "display:flex !important");
    }
}
