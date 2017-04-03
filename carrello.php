<!DOCTYPE html>
<html>

<?php
include ("head_login.html");
include("header_full.html");

function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}
?>

<body>

<main class="main2">
    <section id="content">
        <section id="a10" class="articolo">
            <header>
                <h2>Carrello</h2>
            </header>
            <?php
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header('Cache-Control: no cache'); //no cache
            session_cache_limiter('private_no_expire'); // works
            //session_cache_limiter('public'); // works too
            ini_set('session.gc_maxlifetime', 10);
            session_set_cookie_params(10);
            session_start();
            if ($_GET == null || !isset($_GET['step']) || !is_session_started()) {
                if (is_session_started()) {
                    session_unset();
                }
                if (isset($_POST['svuota'])) {
                    if (isset($_COOKIE['ordini'])) {
                        unset($_COOKIE['ordini']);
                        setcookie('ordini', null, -1, '/');
                    }
                }?>
                <section id="carrello_page_inner">
                    <table id="carrello_page_table" class="carrello_table carrello_table_page">
                        <tr>
                            <th>Prodotto</th>
                            <th>Quantità</th>
                        </tr>
                        <?php
                        if (isset($_COOKIE['ordini']) && count($_COOKIE['ordini']) > 0) {
                            $element = json_decode($_COOKIE['ordini'], true);
                            $_SESSION['prod'] = array();
                            foreach ($element as $item) {
                                array_push($_SESSION['prod'], $item);
                            }
                            foreach ($_SESSION['prod'] as $prod) {
                                echo "<tr><td>";
                                echo $prod['nome'];
                                echo "</td><td>";
                                echo $prod['quantita'];
                                echo "</td></tr>";
                            }
                        }
                        //print_r($_SESSION['prod']);
                        //echo(is_array($_SESSION['prod']));
                        ?>
                    </table>
                    <?php
                    if (isset($_SESSION['prod']) && count($_SESSION['prod']) >= 1) { ?>
                        <form method="post">
                            <input class="svuota svuota2" type="submit" name="svuota" value="Svuota Carrello"></input>
                        </form>
                        <form action='carrello.php?step=1' method="post">
                            <input class="svuota svuota2" type="submit" value="Avanti"></input>
                        </form>
                    <?php } else { ?>
                        <p class="svuota svuota2">Carrello vuoto</p>
                    <?php } ?>
                </section>
            <?php } else if (isset($_GET['step']) && $_GET['step'] == 1) { ?>
                <section id="carrello_page_inner">
                    <h4>Inserisci i tuoi dati personali<h4>
                            <form action='carrello.php?step=2' method="post">
                                <fieldset class="dati_carrello">
                                    <label>Nome:
                                        <input id="carrello_nome" type="text" name="nome" placeholder="Nome" required></input>
                                    </label>
                                    <label>Cognome:
                                        <input id="carrello_cognome" type="text" name="cognome" placeholder="Cognome" required></input>
                                    </label>
                                    <label>Città:
                                        <input id="carrello_citta" type="text" name="citta" placeholder="Città" required></input>
                                    </label>
                                    <label>Indirizzo:
                                        <input id="carrello_indirizzo" type="text" name="indirizzo" placeholder="Indirizzo" required></input>
                                    </label>
                                    <label>Telefono:
                                        <input id="carrello_telefono" type="number" name="telefono" placeholder="Telefono" required></input>
                                    </label>
                                    <label>E-Mail:
                                        <input id="carrello_email" type="email" name="email" placeholder="E-Mail" required></input>
                                    </label>
                                    <input class="svuota svuota2 avanti" type="submit" value="Avanti">
                                </fieldset>
                            </form>
                </section>
            <?php } else if (isset($_GET['step']) && $_GET['step'] == 2) {
                $_SESSION['dati'] = array();
                $_SESSION['dati'] = $_POST; ?>
                <h4>Riepilogo</h4>
                <h5>Prodotti nel Carrello</h5>
                <table id="carrello_page_table" class="carrello_table carrello_table_page">
                </table>
                <h5>Dati Inseriti</h5>
                <p>Nome: <?php echo $_POST['nome'] ?></p>
                <p>Cognome: <?php echo $_POST['cognome'] ?></p>
                <p>Città: <?php echo $_POST['citta'] ?></p>
                <p>Indirizzo: <?php echo $_POST['indirizzo'] ?></p>
                <p>Telefono: <?php echo $_POST['telefono'] ?></p>
                <p>E-Mail: <?php echo $_POST['email'] ?></p>
                <form action='carrello.php?step=3' method="post">
                    <input class="svuota svuota2 avanti" type="submit" value="Avanti">
                </form>
            <?php } else if (isset($_GET['step']) && $_GET['step'] == 3) {
                if (!is_session_started()) {
                    echo("<p>Sessione scaduta.</p>");
                } else { ?>
                    <section id="carrello_page_inner">
                        <h4>Inserisci i dati della tua carta di credito<h4>
                                <form action='carrello.php?step=4' method="post">
                                    <fieldset class="dati_carrello">
                                        <label>Codice carta di credito:
                                            <input id="carrello_numero_carta" name="codice" placeholder="Numero Carta"></input>
                                        </label>
                                        <label>Data di scadenza (MM-AA):
                                            <input id="carrello_scadenza_carta" name="scadenza"></input>
                                        </label>
                                        <label>Codice di sicurezza a 3 cifre:
                                            <input id="carrello_codice_sicurezza" placeholder="Codice di Sicurezza" name="codiceSicurezza"></input>
                                        </label>
                                        <input class="svuota svuota2 avanti" type="submit" value="Avanti">
                                    </fieldset>
                                </form>
                    </section>
                <?php } ?>
            <?php } else if (isset($_GET['step']) && $_GET['step'] == 4) {
                if (!is_session_started()) {
                    echo("<p>Sessione scaduta.</p>");
                } else {
                    $db = mysqli_connect("localhost", "writer", "", "progetto");
                    if (!$db) {
                        echo "connection failed: " . mysqli_connect_error();
                    } else {
                        $ok = true;
                        $prods = $_SESSION['prod'];
                        $result1 = $db->query("REPLACE INTO Cliente
                                          VALUES ('" . $_SESSION['dati']['nome'] . "','" . $_SESSION['dati']['cognome'] . "','" . $_SESSION['dati']['citta'] . "','" . $_SESSION['dati']['indirizzo'] . "','"
                                        . $_SESSION['dati']['telefono'] . "','" . $_SESSION['dati']['email'] . "','" . $_POST['codice'] . "','" . $_POST['scadenza'] . "','" . $_POST['codiceSicurezza'] . "');");

                        if ($result1) {
                            for ($i = 0; $i < sizeof($prods); $i++) {
                                $quantita = $prods[$i]['quantita'];
                                $id = $prods[$i]['id'];
                                $result = $db->query("INSERT INTO Acquisto(email, prodotto, quantita) VALUES ('" . $_SESSION['dati']['email'] . "','" . $id . "','" . $quantita . "');");
                                if ($result) {
                                    $result2 = $db->query("UPDATE Vendite SET venduti = venduti + $quantita, disponibile = disponibile - $quantita WHERE id_prodotto = $id");
                                    if (!$result2) {
                                        echo " Errore nella Query SQL: ";
                                        printf("Errormessage: %s\n", $db->error);
                                        $ok = false;
                                        break;
                                    }
                                } else {
                                    echo " Errore nella Query SQL: ";
                                    printf("Errormessage: %s\n", $db->error);
                                    $ok = false;
                                    break;
                                }
                            }
                        } else {
                            $ok = false;
                        }
                        if ($ok) {
                            session_unset();
                            session_destroy();
                            if (isset($_COOKIE['ordini'])) {
                                unset($_COOKIE['ordini']);
                                setcookie('ordini', null, -1, '/');
                            }
                            echo "<p>Ordine eseguito correttamente!</p>";
                        } else {
                            echo $db->error;
                        }
                        $db->close();
                    }
                }
            }?>
        </section>
    </section>
</main>

</body>
</html>
