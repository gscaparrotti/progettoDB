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
            session_start();
            $db = mysqli_connect("localhost", $_SESSION["user"], $_SESSION["password"] != null ? $_SESSION["password"] : "", "progetto");
            if ($db) {
                if (isset($_POST["submit"])) {
                    $result = null;
                    switch ($_POST["submit"]) {
                        case "Aggiorna Ordine":
                            $result = $db->query("UPDATE Acquisto SET Stato = '$_POST[stato]' WHERE Acquisto.Prodotto = $_POST[ID] AND Acquisto.Cliente = '$_POST[Cliente]' AND Acquisto.Data = '$_POST[Data]'");
                            break;
                        case "Cancella Prodotto":
                            $result = $db->query("DELETE FROM Prodotto WHERE Codice = '$_POST[id]' ");
                            break;
                        case "Aggiungi Ordinazione Prodotto":
                            $fornitura = explode('?', $_POST['fornitura']);
                            $result = $db->query("INSERT INTO Ordinare (Fornitore, Data, Prodotto, Quantita) VALUES ('$fornitura[0]', '$fornitura[1]', $_POST[prodotto], $_POST[quantita]);");
                            break;
                        case "Aggiungi Prodotto in Negozio":
                            $amount = $db->query("SELECT COUNT(*) AS amount FROM ProdottoInNegozio WHERE Prodotto = $_POST[Prodotto] AND Fornitore = $_POST[Fornitore] AND DataFornitura = '$_POST[Data]'");
                            $max_amount = $db->query("SELECT Quantita as max_amount FROM Ordinare WHERE Prodotto = $_POST[Prodotto] AND Fornitore = $_POST[Fornitore] AND Data = '$_POST[Data]'");
                            if ($amount->fetch_assoc()['amount'] + $_POST['Quantita'] <= $max_amount->fetch_assoc()['max_amount']) {
                                for ($i = 0; $i < $_POST['Quantita']; $i++) {
                                    $result = $db->query("INSERT INTO ProdottoInNegozio(Prodotto, Fornitore, DataFornitura, DurataGaranzia, Sconto, Condizione, Venduto) 
                                                            VALUES ('$_POST[Prodotto]', '$_POST[Fornitore]', '$_POST[Data]', '$_POST[garanzia]', '$_POST[sconto]', '$_POST[condizione]', '0');");
                                }
                            } else {
                                echo "<p>Impossibile aggiungere i prodotti selezionati: vincoli non soddisfatti.</p>";
                            }
                            break;
                        case "Aggiungi Fornitura":
                            $result = $db->query("INSERT INTO Fornitura(Fornitore) VALUES ('$_POST[piva]');");
                            break;
                        case "Aggiungi Fornitore":
                            $result = $db->query("INSERT INTO Fornitore(PIVA, Ragionesociale, CAP, Citta, Via, Civico) 
                                                        VALUES ('$_POST[piva]', '$_POST[ragione_sociale]', '$_POST[cap]', '$_POST[citta]', '$_POST[via]', '$_POST[civico]');");
                            break;
                        case "Cancella Fornitore":
                            $result = $db->query("DELETE FROM Fornitore WHERE PIVA = '$_POST[piva]' ");
                            break;
                        case "Aggiungi Riparazione":
                            $date = date('Y-m-d H:i:s');
                            $result = $db->query("INSERT INTO Riparazione (Prodotto, Cliente, `Data Acquisto`, Data, Durata, Riparatore) VALUES ('$_POST[prodotto]', '$_POST[cliente]', '$_POST[data]', '$date','$_POST[ore_lavoro]', '$_POST[riparatore]');");
                            if ($result) {
                                $result = $db->query("INSERT INTO Sostituzione (Prodotto, Cliente, `Data Acquisto`, Data, Ricambio, Quantita) VALUES ('$_POST[prodotto]', '$_POST[cliente]', '$_POST[data]', '$date', '$_POST[ricambio]', '1')");
                            }
                            break;
                    }
                }
                if ($result != null) {
                    echo "<h3>Operazione eseguita correttamente!</h3>";
                } else {
                    echo "<h3>Errore nella query</h3>";
                    echo "<p>".$db->error;
                    echo $db->errno."</p>";
                }
                $db->close();
            } else {
                echo "<h3>Errore nella connessione al DB</h3>";
            }
            //    session_unset();
            //    session_destroy();
            echo "<a href='pagina.php'>TORNA ALLA PAGINA INIZIALE</a>";
            ?>
        </section>
    </section>
</main>
</body>
</html>
