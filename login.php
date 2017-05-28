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
                            <option value="Aggiungi Ordinazione Prodotto">Aggiungi Ordinazione Prodotto</option>
                            <option value="Mostra/Modifica Ordinazioni Prodotto">Mostra/Modifica Ordinazioni Prodotto</option>
                            <option value="Aggiungi Prodotto in Negozio">Aggiungi Prodotto in Negozio</option>
                            <option value="Rimuovi Prodotto in Negozio">Rimuovi Prodotto in Negozio</option>
                            <option value="Aggiungi Fornitura">Aggiungi Fornitura</option>
                            <option value="Aggiungi Fornitore">Aggiungi Fornitore</option>
                            <option value="Rimuovi Fornitore">Rimuovi Fornitore</option>
                            <option value="Nuova Riparazione">Nuova Riparazione</option>
                            <option value="Mostra Riparazioni">Mostra Riparazioni</option>
                            <option value="Aggiungi Riparatore / Mostra Riparatori">Aggiungi Riparatore / Mostra Riparatori</option>
                            <option value="Aggiungi Ricambio / Mostra Ricambi">Aggiungi Ricambio / Mostra Ricambi</option>
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
                                        <label class="ampli type">Numero Ingressi:
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
                                            $result = $db->query("SELECT Codice, Produttore, Nome FROM Prodotto");
                                            if($result && $result->num_rows > 0) {
                                                echo "<label>Prodotto: ";
                                                echo "<select name='id' form='delete_form'>";
                                                while($row = $result->fetch_assoc()) {
                                                    $prod = $row[Produttore].' '.$row[Nome];
                                                    echo "<option value='$row[Codice]'>$prod</option>";
                                                }
                                                echo "</select>";
                                                echo "</label>";
                                                $db->close();
                                            }
                                        }
                                        ?>
                                        <input id="submit_button2" type="submit" value="Cancella Prodotto" name="submit">
                                    </fieldset>
                                </form>
                                <?php break;
                            case ("Aggiungi Ordinazione Prodotto"): ?>
                                <form class="secret_element" id="add_in_negozio_form" action='do_query.php' method="post" enctype="multipart/form-data">
                                    <fieldset class="upload_fieldset" id="delete_fieldset">
                                        <?php
                                        if(!$db) {
                                            echo "connection failed: ".mysqli_connect_error();
                                        } else {
                                            $result = $db->query("SELECT Codice, Nome, Produttore FROM Prodotto");
                                            if($result && $result->num_rows > 0) {
                                                echo "<label>Prodotto di Riferimento: ";
                                                echo "<select name='prodotto' form='add_in_negozio_form'>";
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='$row[Codice]'>$row[Produttore] $row[Nome]</option>";
                                                }
                                                echo "</select>";
                                                echo "</label>";
                                            }
                                            $result = $db->query("SELECT * FROM Fornitura INNER JOIN Fornitore ON Fornitura.Fornitore = Fornitore.PIVA");
                                            if($result && $result->num_rows > 0) {
                                                echo "<label>Fornitura di appartenenza: ";
                                                echo "<select name='fornitura' form='add_in_negozio_form'>";
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='$row[Fornitore]?$row[Data]'>$row[Ragionesociale] - $row[Data]</option>";
                                                }
                                                echo "</select>";
                                                echo "</label>";
                                                $db->close();
                                            } ?>
                                            <label>Quantità:
                                                <input name="quantita" type="number" step="1" min="0" placeholder="" required=""/>
                                            </label>
                                            <?php
                                        }
                                        ?>
                                        <input id="submit_button2" type="submit" value="Aggiungi Ordinazione Prodotto" name="submit">
                                    </fieldset>
                                </form>
                                <?php break;
                            case ("Mostra/Modifica Ordinazioni Prodotto"): ?>
                                <table class="secret_element" id="tabella_ordini" style="">
                                    <?php $result = $db->query("SELECT Ragionesociale as Fornitore, PIVA, Data, Produttore, Nome, Quantita, Codice FROM Ordinare INNER JOIN Fornitore ON Ordinare.Fornitore = Fornitore.PIVA INNER JOIN Prodotto ON Ordinare.Prodotto = Prodotto.Codice ORDER BY Data DESC");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                        $assoc = $result->fetch_all(MYSQLI_ASSOC);
                                        $result->data_seek(0);
                                        ?>
                                        <tr>
                                            <?php for ($i=0; $i<sizeof($fields) - 1; $i++) {
                                                echo "<th>".$fields[$i]->name."</th>";
                                            } ?>
                                        </tr>
                                        <?php
                                        $current_row = 0;
                                        while($row = $result->fetch_row()) {
                                            $codice = $assoc[$current_row]['Codice'];
                                            $piva = $assoc[$current_row]['PIVA'];
                                            $data = $assoc[$current_row]['Data'];
                                            $amount = $db->query("SELECT COUNT(*) AS amount FROM ProdottoInNegozio WHERE Prodotto = $codice AND Fornitore = $piva AND DataFornitura = '$data'")->fetch_assoc()['amount'];
                                            $quantita = $assoc[$current_row]['Quantita'] - $amount;
                                            echo "<tr>";
                                            for ($i=0; $i<sizeof($row) - 1; $i++) {
                                                echo "<td>".$row[$i]."</td>";
                                            };?>
                                            <td>
                                                <form id="show_orders" class="inner_form" action='do_query.php' method="post" enctype="multipart/form-data">
                                                    <label>
                                                        Condizione:
                                                        <input type="text" name="condizione" <?php echo $quantita == 0 ? 'disabled' : ''; ?>>
                                                    </label>
                                                    <label>
                                                        Sconto (%):
                                                        <input type="number" name="sconto" <?php echo $quantita == 0 ? 'disabled' : ''; ?>>
                                                    </label>
                                                    <label>
                                                        Durata Garanzia:
                                                        <input type="number" name="garanzia" <?php echo $quantita == 0 ? 'disabled' : ''; ?>>
                                                    </label>
                                                    <input type="hidden" name="Fornitore" value="<?php echo $assoc[$current_row]['PIVA']; echo $quantita == 0 ? 'disabled' : ''; ?>">
                                                    <input type="hidden" name="Data" value="<?php echo $assoc[$current_row]['Data']; echo $quantita == 0 ? 'disabled' : ''; ?>">
                                                    <input type="hidden" name="Prodotto" value="<?php echo $assoc[$current_row]['Codice']; echo $quantita == 0 ? 'disabled' : ''; ?>">
                                                    <input type="hidden" name="Quantita" value="<?php echo $quantita; echo $quantita == 0 ? 'disabled' : ''; ?>">
                                                    <?php if ($quantita > 0) { ?>
                                                        <input type="submit" value="Aggiungi Prodotto in Negozio" name="submit" style="width: 100%;">
                                                    <?php } else { ?>
                                                        <input type="submit" value="Prodotto già aggiunto" name="submit" style="width: 100%;" disabled>
                                                    <?php } ?>
                                                </form>
                                            </td>
                                            <?php
                                            $current_row++;
                                            echo "</tr>";
                                        }
                                    } ?>
                                </table>
                                <?php break;
                            case ("Aggiungi Fornitura"): ?>
                                <form class="secret_element" id="add_fornitura_form" action='do_query.php' method="post" enctype="multipart/form-data">
                                    <fieldset class="upload_fieldset" id="delete_fieldset">
                                        <?php
                                        if(!$db) {
                                            echo "connection failed: ".mysqli_connect_error();
                                        } else {
                                            $result = $db->query("SELECT PIVA, Ragionesociale FROM Fornitore");
                                            if($result && $result->num_rows > 0) {
                                                echo "<label>Fornitore: ";
                                                echo "<select name='piva' form='add_fornitura_form'>";
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='$row[PIVA]'>$row[Ragionesociale]</option>";
                                                }
                                                echo "</select>";
                                                echo "</label>";
                                                $db->close();
                                            }
                                        }
                                        ?>
                                        <label>Data Fornitura:
                                            <select name="data_fornitura" form="add_fornitura_form">
                                                <option>Data attuale</option>
                                            </select>
                                        </label>
                                        <input id="submit_button2" type="submit" value="Aggiungi Fornitura" name="submit">
                                    </fieldset>
                                </form>
                                <?php break;
                            case ("Aggiungi Fornitore"): ?>
                                <form class="secret_element" id="aggiungi_fornitore" action='do_query.php' method="post" enctype="multipart/form-data">
                                    <fieldset id="upload_fieldset">
                                        <label>Partita IVA:
                                            <input name="piva" type="number" required=""/>
                                        </label>
                                        <label>Ragione Sociale:
                                            <input name="ragione_sociale" placeholder="Ragione Sociale" required=""/>
                                        </label>
                                        <label>CAP:
                                            <input name="cap" type="number" required=""/>
                                        </label>
                                        <label>Città:
                                            <input name="citta" placeholder="Città" required=""/>
                                        </label>
                                        <label>Via:
                                            <input name="via" placeholder="Via" required=""/>
                                        </label>
                                        <label>N. Civico:
                                            <input name="civico" type="number" required=""/>
                                        </label>
                                        <input id="submit_button" type="submit" value="Aggiungi Fornitore" name="submit">
                                    </fieldset>
                                </form>
                                <?php break;
                            case ("Rimuovi Fornitore"): ?>
                                <form class="secret_element" id="delete_fornitore_form" action='do_query.php' method="post" enctype="multipart/form-data">
                                    <fieldset class="upload_fieldset" id="delete_fieldset">
                                        <?php
                                        if(!$db) {
                                            echo "connection failed: ".mysqli_connect_error();
                                        } else {
                                            $result = $db->query("SELECT PIVA, Ragionesociale FROM Fornitore");
                                            if($result && $result->num_rows > 0) {
                                                echo "<label>Fornitore: ";
                                                echo "<select name='piva' form='delete_fornitore_form'>";
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='$row[PIVA]'>$row[Ragionesociale]</option>";
                                                }
                                                echo "</select>";
                                                echo "</label>";
                                                $db->close();
                                            }
                                        }
                                        ?>
                                        <input id="submit_button2" type="submit" value="Cancella Fornitore" name="submit">
                                    </fieldset>
                                </form>
                                <?php break;
                            case ("Nuova Riparazione"): ?>
                                <table class="secret_element" id="tabella_clenti" style="">
                                    <?php
                                    $result = $db->query("SELECT Cliente, Data, Produttore, Nome, DurataGaranzia, Acquisto.Prodotto FROM Acquisto INNER JOIN ProdottoInNegozio ON Acquisto.Prodotto = ProdottoInNegozio.ID INNER JOIN Prodotto ON ProdottoInNegozio.Prodotto = Prodotto.Codice WHERE DATEDIFF(DATE_ADD(Data, INTERVAL DurataGaranzia YEAR), NOW()) >= 0");
                                    $riparatori = $db->query("SELECT * FROM Riparatore");
                                    $ricambi = $db->query("SELECT * FROM Ricambio");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                        $assoc = $result->fetch_all(MYSQLI_ASSOC);
                                        $result->data_seek(0);
                                    }
                                    else {
                                        echo $db->error;
                                    }?>
                                    <tr>
                                        <?php for ($i=0; $i<sizeof($fields) - 1; $i++) {
                                            echo "<th>".$fields[$i]->name."</th>";
                                        } ?>
                                    </tr>
                                    <?php
                                    $current_row = 0;
                                    while($row = $result->fetch_row()) {
                                        echo "<tr>";
                                        for ($i=0; $i<sizeof($row) - 1; $i++) {
                                            echo "<td>".$row[$i]."</td>";
                                        } ?>
                                        <td>
                                            <form id="add_riparazione" class="inner_form" action='do_query.php' method="post" enctype="multipart/form-data">
                                                <label>Riparatore:
                                                    <select id="riparatore_select" class="inner_select" name='riparatore' form='add_riparazione'>
                                                        <?php
                                                        $riparatori->data_seek(0);
                                                        while ($fields = $riparatori->fetch_assoc()) {
                                                            $matricola = $fields['Matricola'];
                                                            $nome = $fields['Nome'].' '.$fields['Cognome']; ?>
                                                            <option value=<?php echo $matricola?>><?php echo $nome?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </label>
                                                <label>Ricambio:
                                                    <select id="ricambio_select" class="inner_select"  name='ricambio' form='add_riparazione'>
                                                        <?php
                                                        $ricambi->data_seek(0);
                                                        while ($fields = $ricambi->fetch_assoc()) {
                                                            $nome = $fields['Nome'];?>
                                                            <option value="<?php echo $nome?>"><?php echo $nome?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </label>
                                                <label>Ore di lavoro:
                                                    <input name="ore_lavoro" type="number" required=""/>
                                                </label>
                                                <input type="hidden" name="prodotto" value=<?php echo $assoc[$current_row]['Prodotto']; ?>>
                                                <input type="hidden" name="data" value="<?php echo $assoc[$current_row]['Data']; ?>">
                                                <input type="hidden" name="cliente" value=<?php echo $assoc[$current_row]['Cliente']; ?>>
                                                <input id="submit_button2" type="submit" value="Aggiungi Riparazione" name="submit">
                                            </form>
                                        </td>
                                        <?php echo "</tr>";
                                    $current_row++;
                                    } ?>
                                </table>
                                <?php break;
                            case ("Mostra Riparazioni"): ?>
                                <table class="secret_element" style="">
                                    <?php $result = $db->query("SELECT Prodotto.Produttore, Prodotto.Nome, Riparazione.Cliente, Riparazione.`Data Acquisto`, Riparazione.Data as 'Data Riparazione', Riparatore.Nome as 'Nome Riparatore', Riparatore.Cognome as 'Cognome Riparatore', Riparatore.PagaOraria, Riparazione.Durata, Ricambio.Costo, Sostituzione.Quantita FROM Riparazione LEFT OUTER JOIN Sostituzione ON Riparazione.Prodotto = Sostituzione.Prodotto AND Riparazione.Cliente = Sostituzione.Cliente AND Riparazione.`Data Acquisto` = Sostituzione.`Data Acquisto` AND Riparazione.Data = Sostituzione.Data LEFT OUTER JOIN Ricambio ON Sostituzione.Ricambio = Ricambio.Nome INNER JOIN ProdottoInNegozio on Riparazione.Prodotto = ProdottoInNegozio.ID INNER JOIN Prodotto ON ProdottoInNegozio.Prodotto = Prodotto.Codice INNER JOIN Riparatore ON Riparazione.Riparatore = Riparatore.Matricola ORDER BY Riparazione.Data DESC");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                    }
                                    else {
                                        echo $db->error;
                                    }?>
                                    <tr>
                                        <?php for ($i=0; $i<sizeof($fields) - 6; $i++) {
                                            echo "<th>".$fields[$i]->name."</th>";
                                        } ?>
                                        <th>Riparatore</th>
                                        <th>Costo Riparazione</th>
                                    </tr>
                                    <?php while($row = $result->fetch_row()) {
                                        echo "<tr>";
                                        for ($i=0; $i<sizeof($row) - 6; $i++) {
                                            echo "<td>".$row[$i]."</td>";
                                        }
                                        echo "<td>".$row[$i].' '.$row[$i + 1]."</td>";
                                        echo "<td>".($row[$i + 2]*$row[$i + 3] + $row[$i + 4]*$row[$i + 5])."</td>";
                                        echo "</tr>";
                                    } ?>
                                </table>
                                <?php break;
                            case ("Aggiungi Riparatore / Mostra Riparatori"): ?>
                                <form class="secret_element" id="aggiungi_riparatore" action='do_query.php'
                                      method="post" enctype="multipart/form-data">
                                    <fieldset id="upload_fieldset">
                                        <label>Matricola:
                                            <input name="matricola" type="number" required=""/>
                                        </label>
                                        <label>Nome:
                                            <input name="nome" placeholder="Nome" required=""/>
                                        </label>
                                        <label>Cognome:
                                            <input name="cognome" placeholder="Cognome" required=""/>
                                        </label>
                                        <label>Paga Oraria:
                                            <input name="paga" type="number" required=""/>
                                        </label>
                                        <input id="submit_button" type="submit" value="Aggiungi Riparatore"
                                               name="submit">
                                    </fieldset>
                                </form>
                                <hr>
                                <h2 style="margin-top: 2vh">Lista Riparatori</h2>
                                <table class="secret_element" style="">
                                    <?php $result = $db->query("SELECT * FROM Riparatore ORDER BY Matricola");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                    } else {
                                        echo $db->error;
                                    } ?>
                                    <tr>
                                        <?php for ($i = 0; $i < sizeof($fields); $i++) {
                                            echo "<th>" . $fields[$i]->name . "</th>";
                                        } ?>
                                    </tr>
                                    <?php while ($row = $result->fetch_row()) {
                                        echo "<tr>";
                                        for ($i = 0; $i < sizeof($row); $i++) {
                                            echo "<td>" . $row[$i] . "</td>";
                                        }
                                        echo "</tr>";
                                    } ?>
                                </table>
                                <?php break;
                            case ("Aggiungi Ricambio / Mostra Ricambi"): ?>
                                <form class="secret_element" id="aggiungi_ricambio" action='do_query.php'
                                      method="post" enctype="multipart/form-data">
                                    <fieldset id="upload_fieldset">
                                        <label>Nome:
                                            <input name="nome" placeholder="Nome" required=""/>
                                        </label>
                                        <label>Costo:
                                            <input name="costo" type="number" required=""/>
                                        </label>
                                        <input id="submit_button" type="submit" value="Aggiungi Ricambio"
                                               name="submit">
                                    </fieldset>
                                </form>
                                <hr>
                                <h2 style="margin-top: 2vh">Lista Ricambi</h2>
                                <table class="secret_element" style="">
                                    <?php $result = $db->query("SELECT * FROM Ricambio ORDER BY Nome");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                    } else {
                                        echo $db->error;
                                    } ?>
                                    <tr>
                                        <?php for ($i = 0; $i < sizeof($fields); $i++) {
                                            echo "<th>" . $fields[$i]->name . "</th>";
                                        } ?>
                                    </tr>
                                    <?php while ($row = $result->fetch_row()) {
                                        echo "<tr>";
                                        for ($i = 0; $i < sizeof($row); $i++) {
                                            echo "<td>" . $row[$i] . "</td>";
                                        }
                                        echo "</tr>";
                                    } ?>
                                </table>
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