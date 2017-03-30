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
                    switch ($_POST["submit"]) {
                        case "Cancella":
                            $result = $db->query("DELETE FROM Vendite WHERE id_prodotto = $_POST[id] ");
                            if ($result) {
                                $result = $db->query("DELETE FROM Prodotti WHERE id = $_POST[id] ");
                            } else {
                                echo "<h3>Errore nella prima query</h3>";
                                $result = null;
                            }
                            break;
                        case "Aggiorna Disponibilità":
                            $result = $db->query("UPDATE Vendite SET disponibile = $_POST[disponibile] WHERE id_prodotto = $_POST[id] ");
                            break;
                        case "Aggiungi Categoria":
                            $result = $db->query("INSERT INTO Tipo_Prodotti(nome, caratteristiche) VALUES ('$_POST[nome]', '$_POST[caratteristiche]');");
                            break;
                    }
                }
                if ($result) {
                    echo "<h3>Operazione eseguita correttamente!</h3>";
                } else {
                    echo "<h3>Errore nella query</h3>";
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
