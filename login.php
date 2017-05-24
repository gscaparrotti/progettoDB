<!DOCTYPE html>
<html>

<?php
include ("head_login.html");
include("header_full.html");
?>

<body>

<main class="main2">
    <section id="content">
        <section id="a0" class="articolo">
            <?php
            ini_set('session.gc_maxlifetime', 180);
            session_set_cookie_params(180);
            session_start();
            if (!isset($_SESSION["user"]) && isset($_POST["user"])) {
                $_SESSION["user"] = $_POST["user"];
            }
            if (!isset($_SESSION["password"]) && isset($_POST["password"])) {
                $_SESSION["password"] = $_POST["password"];
            }
            if(!isset($_SESSION["user"])) { ?>
                <form action="login.php" method="post" id="upload_fieldset1">
                    <label>Nome Utente:
                        <input id="user_upload" name="user" type="text" placeholder="Nome Utente"/>
                    </label>
                    <label>Password:
                        <input id="password_upload" name="password" type="password" placeholder="Password"/>
                    </label>
                    <input id="data_inserted_button" type="submit" value="Avanti">
                </form>
            <?php } else {
                mysqli_report(MYSQLI_REPORT_STRICT);
                $db = null;
                try {
                    $db = mysqli_connect("localhost", $_SESSION["user"], $_SESSION["password"] != null ? $_SESSION["password"] : "", "progetto");
                } catch (Exception $e) {
                    echo "<p>Dati inseriti non corretti. Riprovare.</p>";
                }
                if ($db != null) { ?>
                    <form id="secret_buttons" method="get">
                        Selezionare l'azione da eseguire:
                        <select name="show" form="secret_buttons">
                            <option value="Mostra Ordini">Mostra Ordini</option>
                            <option value="Mostra Commenti">Mostra Commenti</option>
                            <option value="Mostra Clienti">Mostra Clienti</option>
                            <option value="Aggiungi Prodotto">Aggiungi Prodotto</option>
                            <option value="Rimuovi Prodotto">Rimuovi Prodotto</option>
                            <option value="Aggiungi Prodotto in Negozio">Aggiungi Prodotto in Negozio</option>
                            <option value="Rimuovi Prodotto in Negozio">Rimuovi Prodotto in Negozio</option>
                            <option value="Aggiungi Fornitura">Aggiungi Fornitura</option>
                            <option value="Aggiungi Fornitore">Aggiungi Fornitore</option>
                        </select>
                        <input id="submit_secret_buttons" type="submit" value="Invio">
                    </form>
                    <?php
                    if (isset($_GET["show"])) {
                        echo "<h2>$_GET[show]</h2>";
                        switch ($_GET["show"]) {
                            case ("Mostra Ordini"): ?>
                                <table class="secret_element" id="tabella_ordini" style="">
                                    <?php $result = $db->query("SELECT ProdottoInNegozio.ID as 'Prodotto in Negozio', Acquisto.Cliente, Acquisto.Data, Prodotto.Costo, ProdottoInNegozio.Sconto, Acquisto.Pagamento, Acquisto.Stato FROM Acquisto
                                                                      INNER JOIN ProdottoInNegozio ON Acquisto.Prodotto = ProdottoInNegozio.ID
                                                                      INNER JOIN Prodotto on ProdottoInNegozio.Prodotto = Prodotto.Codice");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                        $assoc = $result->fetch_all(MYSQLI_ASSOC);
                                        $result->data_seek(0);
                                        ?>
                                        <tr>
                                            <?php for ($i=0; $i<sizeof($fields); $i++) {
                                                echo "<th>".$fields[$i]->name."</th>";
                                            } ?>
                                        </tr>
                                        <?php
                                        $current_row = 0;
                                        while($row = $result->fetch_row()) {
                                            echo "<tr>";
                                            for ($i=0; $i<sizeof($row); $i++) {
                                                echo "<td>".$row[$i]."</td>";
                                            };?>
                                            <td>
                                                <form action='do_query.php' method="post" enctype="multipart/form-data">
                                                    Nuovo stato ordine:<br>
                                                    <input type="text" name="stato"><br>
                                                    <input type="hidden" name="ID" value="<?php echo $assoc[$current_row]['Prodotto in Negozio'] ?>">
                                                    <input type="hidden" name="Cliente" value="<?php echo $assoc[$current_row]['Cliente'] ?>">
                                                    <input type="hidden" name="Data" value="<?php echo $assoc[$current_row]['Data'] ?>">
                                                    <input type="submit" value="Aggiorna Ordine" name="submit">
                                                </form>
                                            </td>
                                            <?php
                                            $current_row++;
                                            echo "</tr>";
                                        }
                                    } ?>
                                </table>
                                <?php break;
                            case ("Mostra Commenti"): ?>
                                <table class="secret_element" id="tabella_commenti" style="">
                                    <?php $result = $db->query("SELECT Commenti.nickname as Nickname, Commenti.email as Email, Commenti.commento as Commento, Prodotto.Produttore, Prodotto.Nome, Prodotto.Codice FROM Commenti INNER JOIN Prodotto ON Commenti.id_prodotto = Prodotto.Codice");
                                    $result2 = $db->query("SELECT id, nome FROM Prodotti");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                    }?>
                                    <tr>
                                        <?php for ($i=0; $i<sizeof($fields) - 1; $i++) {
                                            echo "<th>".$fields[$i]->name."</th>";
                                        } ?>
                                    </tr>
                                    <?php
                                    while($row = $result->fetch_row()) {
                                        echo "<tr>";
                                        for ($i=0; $i<sizeof($row) - 1; $i++) {
                                            echo "<td>".$row[$i]."</td>";
                                        } ?>
                                        <td>
                                            <form action="pagina.php#params[id]=<?php echo $row[$i] ?>&url=dettaglio.php">
                                                <input type="submit" value="Visualizza Prodotto" />
                                            </form>
                                        </td>
                                        <?php
                                        echo "</tr>";
                                    } ?>
                                </table>
                                <?php break;
                            case ("Mostra Clienti"): ?>
                                <table class="secret_element" id="tabella_clenti" style="">
                                    <?php $result = $db->query("SELECT `E-Mail`, Nome, Cognome, CAP, Citta, Via, Civico, Tipo as 'Metodo Pagamento', Codice, Scadenza, CodSicurezza From Cliente INNER JOIN MetodoPagamento");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                    }
                                    else {
                                        echo $db->error;
                                    }?>
                                    <tr>
                                        <?php for ($i=0; $i<sizeof($fields); $i++) {
                                            echo "<th>".$fields[$i]->name."</th>";
                                        } ?>
                                    </tr>
                                    <?php while($row = $result->fetch_row()) {
                                        echo "<tr>";
                                        for ($i=0; $i<sizeof($row); $i++) {
                                            echo "<td>".$row[$i]."</td>";
                                        }
                                        echo "</tr>";
                                    } ?>
                                </table>
                                <?php break;
                            case ("Aggiorna disponibilità"): ?>
                                <form class="secret_element" id="disp_update" action='do_query.php' method="post" enctype="multipart/form-data">
                                    <fieldset class="upload_fieldset" id="disp_fieldset">
                                        <label>Nuova disponibilità:
                                            <input id="disp_field" name="disponibile" placeholder="Disponibile" required=""/>
                                        </label>
                                        <?php
                                        if(!$db) {
                                            echo "connection failed: ".mysqli_connect_error();
                                        } else {
                                            $result = $db->query("SELECT id, nome FROM Prodotti");
                                            if($result && $result->num_rows > 0) {
                                                echo "<label>Prodotto: ";
                                                echo "<select name='id' form='disp_update'>";
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='$row[id]'>$row[nome]</option>";
                                                }
                                                echo "</select>";
                                                echo "</label>";
                                            }
                                        }
                                        ?>
                                        <input id="submit_button1" type="submit" value="Aggiorna Disponibilità" name="submit">
                                    </fieldset>
                                </form>
                                <?php break;
                            case ("Aggiungi Prodotto"): ?>
                                <form class="secret_element" id="upload" action='file_upload.php' method="post" enctype="multipart/form-data">
                                    <fieldset id="upload_fieldset">
                                        <label>Codice (a barre):
                                            <input name="id" type="number" step="1" min="0" placeholder="" required=""/>
                                        </label>
                                        <label>Nome del produttore:
                                            <input name="produttore" placeholder="Produttore" required=""/>
                                        </label>
                                        <label>Nome del prodotto:
                                            <input name="nome" placeholder="Nome" required=""/>
                                        </label>
                                        <label>Prezzo del prodotto:
                                            <input name="costo" type="number" step="0.01" min="0" placeholder="Prezzo" required=""/>
                                        </label>
                                        <label>Descrizione:
                                            <textarea name="desc" placeholder="Descrizione"required="required"></textarea>
                                        </label>
                                        <label>Tipo Prodotto:
                                        <select id="type_select" onchange="type_change(this);" name='tipo' form='upload'>
                                            <option value=0>Altro</option>
                                            <option value=1>Amplificatore</option>
                                            <option value=2>Diffusore</option>
                                            <option value=3>Lettore Multimediale</option>
                                        </select>
                                        </label>
                                        <label class="ampli type">Potenza:
                                            <input name="potenza" type="number" placeholder="Potenza"/>
                                        </label>
                                        <label class="ampli type">Risposta in Frequenza:
                                            <input name="rif" placeholder="Risposta in Frequenza"/>
                                        </label>
                                        <label class="ampli type">Risposta in Frequenza:
                                            <input name="ningressi" type="number" placeholder="Numero Ingressi"/>
                                        </label>
                                        <label class="diff type">Potenza Massima:
                                            <input name="potmax" type="number" placeholder="Potenza Massima"/>
                                        </label>
                                        <label class="diff type">Numero Vie:
                                            <input name="nvie" type="number" placeholder="Numero Vie"/>
                                        </label>
                                        <label class="lettore type">Formati Supportati:
                                            <input name="formati" placeholder="Formati Supportati"/>
                                        </label>
                                        <label class="lettore type">DAC:
                                            <input name="dac" placeholder="DAC"/>
                                        </label>
                                        <label class="lettore type">Uscite:
                                            <input name="uscite" placeholder="Uscite"/>
                                        </label>
                                        <label>
                                            Select image to upload:
                                            <input type="file" name="fileToUpload" id="fileToUpload" required="">
                                        </label>
                                        <input id="submit_button" type="submit" value="Upload" name="submit">
                                    </fieldset>
                                </form>
                                <?php break;
                            case ("Rimuovi Prodotto"): ?>
                                <form class="secret_element" id="delete_form" action='do_query.php' method="post" enctype="multipart/form-data">
                                    <fieldset class="upload_fieldset" id="delete_fieldset">
                                        <?php
                                        if(!$db) {
                                            echo "connection failed: ".mysqli_connect_error();
                                        } else {
                                            $result = $db->query("SELECT id, nome FROM Prodotti");
                                            if($result && $result->num_rows > 0) {
                                                echo "<label>Prodotto: ";
                                                echo "<select name='id' form='delete_form'>";
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='$row[id]'>$row[nome]</option>";
                                                }
                                                echo "</select>";
                                                echo "</label>";
                                                $db->close();
                                            }
                                        }
                                        ?>
                                        <input id="submit_button2" type="submit" value="Cancella" name="submit">
                                    </fieldset>
                                </form>
                                <?php break;
                            case ("Aggiungi Prodotto in Negozio"): ?>
                                <form class="secret_element" id="add_in_negozio_form" action='do_query.php' method="post" enctype="multipart/form-data">
                                    <fieldset class="upload_fieldset" id="delete_fieldset">
                                        <?php
                                        if(!$db) {
                                            echo "connection failed: ".mysqli_connect_error();
                                        } else {
                                            $result = $db->query("SELECT Codice, Nome, Produttore FROM Prodotto");
                                            if($result && $result->num_rows > 0) {
                                                echo "<label>Prodotto di Riferimento: ";
                                                echo "<select name='id' form='add_in_negozio_form'>";
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='$row[Codice]'>$row[Produttore] $row[Nome]</option>";
                                                }
                                                echo "</select>";
                                                echo "</label>";
                                                $db->close();
                                            }
                                        }
                                        ?>
                                        <input id="submit_button2" type="submit" value="ProdottoInNegozio" name="submit">
                                    </fieldset>
                                </form>
                                <?php break;
                        }
                    }
                } else { ?>
                    <p>Impossibile connettersi al Database.</p>
                    <?php
                }
            } ?>
        </section>
    </section>
</main>

</body>
</html>