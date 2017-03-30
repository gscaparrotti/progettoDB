var current_page;
var ordina_ext;

$(document).ready(function() {
    $(window).bind( 'hashchange', function( event ) {
        var bbq = $.deparam.fragment();
        swapPage(bbq["url"], eval(bbq["params"]));
    });
    if ($.deparam.fragment()['url'] === undefined) {
        $.bbq.pushState({"url":"vetrina.php", "params":null});
    }
    $(window).trigger( 'hashchange' );
});

$(document).ready(function() {
    updateCart();
});

$(document).ready(function() {
    $(".menu_item").click(function() {
        if($(this).html().match("Prodotti in vetrina") || $(this).html().match("Home")) {
            if (current_page !== "vetrina.php") {
                $.bbq.pushState({"url":"vetrina.php", "params":null});
            } else {
                highlightPage();
            }
        } else if ($(this).html().match("Tutti i prodotti")) {
            if (current_page !== "prodotti.php") {
                $.bbq.pushState({"url":"prodotti.php", "params":null});
            } else {
                highlightPage();
            }
        } else if ($(this).html().match("Contattaci")) {
            if (current_page !== "contatti.php") {
                $.bbq.pushState({"url": "contatti.php", "params": null});
            } else {
                highlightPage();
            }
        }
    })
});

function swapPage(pageAddress, datas) {
    var upd = false;
    var spinner = Spinners.create('#spinner', {
        radius: 20,
        height: 10,
        width: 2.5,
        dashes: 30,
        padding: 10,
        color: '#000'
    }).play();
    if((current_page === "prodotti.php" || current_page === "ris_ricerca.php") && (pageAddress === "prodotti.php" || pageAddress === "ris_ricerca.php")) {
        ordina_ext = document.getElementById("ordinaPer1").value;
        upd = true;
    }
    current_page = pageAddress;
    if (datas === null) {
        $("#a0").load(pageAddress, function() {
            if (upd === true) {
                document.getElementById("ordinaPer1").value = ordina_ext;
            }
            firstStage(spinner);
        });
    } else {
        $("#a0").load(pageAddress, datas, function() {
            if (upd === true) {
                document.getElementById("ordinaPer1").value = ordina_ext;
            }
            firstStage(spinner);
        });
    }
}

function firstStage(spinner) {
    var search = false;
    var orderBy, pageNumber, postContent;
    if (current_page === "prodotti.php") {
        search = true;
        orderBy = document.getElementById("ordinaPer1").value;
        pageNumber = $.deparam.fragment()["params"]["page"];
        postContent = {"ordina" : orderBy, "page" : pageNumber};
        $("#v1").load("prodotti_inner.php", postContent, function() {
            secondStage(spinner);
        });
        $("#ricerca").fadeIn("slow");
    } else if (current_page === "ris_ricerca.php") {
        search = true;
        var search_key = $.deparam.fragment()["params"]["ricerca"];
        orderBy = document.getElementById("ordinaPer1").value;
        pageNumber = $.deparam.fragment()["params"]["page"];
        postContent = {"ordina" : orderBy, "ricerca" : search_key, "page" : pageNumber};
        $("#r2").load("prodotti_inner.php", postContent, function() {
            secondStage(spinner);
        });
        $("#ricerca").fadeIn("slow");
    } else if (current_page === "dettaglio.php") {
        search = true;
        secondStage(spinner);
        $("#ricerca").fadeIn("slow");
    } else if (current_page === "carrello.php") {
        populateCartPage();
        secondStage(spinner);
        $('#carrello_aside').css("opacity", "1.0").animate({opacity: 0}, 200, function(){
            $('#carrello_aside').css("visibility", "hidden");
        });
    } else {
        secondStage(spinner);
    }
    if (search === false) {
        $("#ricerca").fadeOut("slow");
    }
    if (current_page !== "carrello.php") {
        if(document.getElementById('carrello_aside').style.visibility === "hidden") {
            $('#carrello_aside').css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1}, 400);
        }
        Cookies.remove("datiUtente");
    }
}

function secondStage(spinner) {
    $.getScript("js/script2.js");
    $("#a0").waitForImages(function() {
        spinner.remove();
    });
}

function highlightPage() {
    $('#a0').effect("highlight", {color: '#ffe0b3'}, 1000);
    $('#a0').children().each(function () {
        $(this).effect("highlight", {color: '#ffe0b3'}, 1000);
    });
}

function addToCart(id, nome) {
    var ordini = Cookies.getJSON("ordini");
    var ok = false;
    if (ordini === undefined) {
        if (document.getElementById("pezzi_disponibili").innerHTML > 0) {
            ordini = [{"id" : id, "nome" : nome, "quantita" : 1}];
            ok = true;
        } else {
            alert("Disponibilità terminata");
        }
    } else {
        var i = 0;
        var added = false;
        for (i=0; i<ordini.length; i++) {
            if(ordini[i]["id"] === id) {
                if (ordini[i]["quantita"] < document.getElementById("pezzi_disponibili").innerHTML) {
                    ordini[i]["quantita"] += 1;
                    ok = true;
                } else {
                    alert("Disponibilità terminata");
                }
                added = true;
                break;
            }
        }
        if(!added) {
            if (document.getElementById("pezzi_disponibili").innerHTML > 0) {
                ordini.push({"id" : id, "nome" : nome, "quantita" : 1});
                ok = true;
            } else {
                alert("Disponibilità terminata");
            }
        }
    }
    if (ok) {
        Cookies.set("ordini", ordini);
    }
    updateCart();
}

function updateCart() {
    var ordini = Cookies.getJSON("ordini");
    var table = document.getElementById("carrello_aside_table");
    var table2 = document.getElementById("carrello_page_table");
    var l = table.rows.length;
    var i = 0;
    for (i=0; i<l; i++) {
        table.deleteRow(0);
        if (table2 !== null) {
            table2.deleteRow(0);
        }
    }
    var head = table.insertRow(0);
    head.innerHTML = "<th>Prodotti</br>nel carrello</th>";
    if (table2 !== null) {
        var head2 = table2.insertRow(0);
        head2.innerHTML = "<th>Prodotti nel carrello</th>";
    }
    if (ordini !== undefined) {
        for(i=0; i<ordini.length; i++) {
            var row = table.insertRow(i+1);
            var cell = row.insertCell(0);
            cell.innerHTML = ordini[i]["quantita"] + " x " + ordini[i]["nome"];
            if (table2 !== null) {
                var row2 = table2.insertRow(i+1);
                var cell2 = row2.insertCell(0);
                cell2.innerHTML = ordini[i]["quantita"] + " x " + ordini[i]["nome"];
            }
        }
    } else {
        var empty = table.insertRow(1);
        empty.innerHTML = "<td>Nessun Prodotto</td>";
        if (table2 !== null) {
            var empty2 = table2.insertRow(1);
            empty2.innerHTML = "<td>Nessun Prodotto</td>";
        }
    }
}

function emptyCart() {
    var conferma = confirm("Sei sicuro di voler svuotare il carrello?");
    if(conferma) {
        Cookies.remove("ordini");
        updateCart();
    }
}

function populateCartPage() {
    var table = document.getElementById("carrello_aside_table");
    var table2 = document.getElementById("carrello_page_table");
    if (table2 !== null) {
        table2.innerHTML = table.innerHTML;
        table2.rows[0].innerHTML = "<th>Prodotti nel carrello</th>";
    }
}

function sendComment() {
    if (document.getElementById('nickname_area').value.length >= 3 && document.getElementById('email_area').value.length > 0 && document.getElementById('textarea_messaggio').value.length > 0) {
        swapPage("contatti.php", {"nickname" : document.getElementById("nickname_area").value, "email" : document.getElementById("email_area").value,
            "comment" : document.getElementById("textarea_messaggio").value, "prodotto" : document.getElementById("prodotto_contatti").value});
    } else {
        alert("Compilare correttamente tutti i campi!");
    }
}

function reorderList() {
    var spinner = Spinners.create('#spinner', {
        radius: 20,
        height: 10,
        width: 2.5,
        dashes: 30,
        padding: 10,
        color: '#000000'
    }).play();
    firstStage(spinner);
}

function search() {
    if ($.deparam.fragment()["params"]["ricerca"] !== document.getElementById("search_field").value) {
        var spinner = Spinners.create('#spinner', {
            radius: 20,
            height: 10,
            width: 2.5,
            dashes: 30,
            padding: 10,
            color: '#000000'
        }).play();
        $.bbq.pushState({"url":"ris_ricerca.php", "params": {"ricerca" : document.getElementById("search_field").value}});
    } else {
        highlightPage();
    }
}
