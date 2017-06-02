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
                            <th>Condizione</th>
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
                                echo $prod["nome"];
                                echo "</td><td>";
                                echo $prod["cond"];
                                if ($prod['sconto'] != 0) {
                                    echo ", -".$prod["sconto"]."%";
                                }
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
                            <input class="svuota svuota2" type="submit" name="svuota" value="Svuota Carrello">
                        </form>
                        <form action='carrello.php?step=1' method="post">
                            <input class="svuota svuota2" type="submit" value="Avanti">
                        </form>
                    <?php } else { ?>
                        <h3 class="svuota svuota2">Carrello vuoto</h3>
                    <?php } ?>
                </section>
            <?php } else {
                if (!is_session_started()) {
                    echo("<h3>Sessione scaduta.</h3>");
                } else  if (!isset($_GET['step'])) {
                    echo("<p>Errore nell'interpretazione del passo corrente</p>");
                }else {
                    switch ($_GET['step']) {
                        case 1: ?>
                            <section id="carrello_page_inner">
                                <h4>Inserisci i tuoi dati personali</h4>
                                <p>Se è già stato effettuato un acquisto è possibile inserire solo l'indirizzo E-Mail; sarà comunque possibile ricontrollare i dati precedentemente inseriti.</p>
                                <form action='carrello.php?step=2' method="post">
                                    <fieldset class="dati_carrello">
                                        <label>Nome:
                                            <input id="carrello_nome" type="text" name="Nome" placeholder="Nome">
                                        </label>
                                        <label>Cognome:
                                            <input id="carrello_cognome" type="text" name="Cognome" placeholder="Cognome">
                                        </label>
                                        <label>CAP:
                                            <input id="carrello_cap" type="number" name="CAP" placeholder="CAP">
                                        </label>
                                        <label>Città:
                                            <input id="carrello_citta" type="text" name="Citta" placeholder="Città">
                                        </label>
                                        <label>Indirizzo:
                                            <input id="carrello_indirizzo" type="text" name="Via" placeholder="Indirizzo">
                                        </label>
                                        <label>Indirizzo:
                                            <input id="carrello_civico" type="text" name="Civico" placeholder="Civico">
                                        </label>
                                        <label>E-Mail:
                                            <input id="carrello_email" type="email" name="E-Mail" placeholder="E-Mail" required>
                                        </label>
                                        <input class="svuota svuota2 avanti" type="submit" value="Avanti">
                                    </fieldset>
                                </form>
                            </section>
                            <?php break;
                        case 2:
                            $insert = true;
                            $_SESSION['dati'] = array();
                            $db = mysqli_connect("localhost", "writer", "", "progetto");
                            if (!$db) {
                                echo "connection failed: " . mysqli_connect_error();
                            } else {
                                $email = $_POST['E-Mail'];
                                $result = $db->query("SELECT * FROM Cliente WHERE `E-Mail` = '$email' LIMIT 1");
                            }
                            if (isset($result) && $result->num_rows > 0) {
                                $_SESSION['dati'] = $result->fetch_assoc();
                                $insert = false;
                            } else {
                                $_SESSION['dati'] = $_POST;
                            }
                            $_SESSION['insert'] = $insert; ?>
                            <h4>Riepilogo</h4>
                            <h5>Prodotti nel Carrello</h5>
                            <table id="carrello_page_table" class="carrello_table carrello_table_page">
                                <tr>
                                    <th>Prodotto</th>
                                    <th>Condizione</th>
                                    <th>Quantità</th>
                                </tr>
                                <?php foreach ($_SESSION['prod'] as $prod) {
                                echo "<tr><td>";
                                        echo $prod["nome"];
                                        echo "</td><td>";
                                        echo $prod["cond"];
                                        if ($prod['sconto'] != 0) {
                                        echo ", -".$prod["sconto"]."%";
                                        }
                                        echo "</td><td>";
                                        echo $prod['quantita'];
                                        echo "</td></tr>";
                                } ?>
                            </table>
                            <h5>Dati Inseriti</h5>
                            <p>Nome: <?php echo $_SESSION['dati']['Nome'] ?></p>
                            <p>Cognome: <?php echo $_SESSION['dati']['Cognome'] ?></p>
                            <p>Città: <?php echo $_SESSION['dati']['Citta'] ?></p>
                            <p>Indirizzo: <?php echo $_SESSION['dati']['Via'].' '.$_SESSION['dati']['Civico'] ?></p>
                            <p>E-Mail: <?php echo $_POST['E-Mail'] ?></p>
                            <form action='carrello.php?step=3' method="post">
                                <input class="svuota svuota2 avanti" type="submit" value="Avanti">
                            </form>
                            <?php break;
                        case 3: ?>
                            <section id="carrello_page_inner">
                                <h4>Seleziona un metodo di pagamento</h4>
                                <form id="pay" action='carrello.php?step=4' method="post">
                                <?php
                                $db = mysqli_connect("localhost", "writer", "", "progetto");
                                if (!$db) {
                                    echo "connection failed: " . mysqli_connect_error();
                                } else {
                                    if (!$_SESSION['insert']) {
                                        $email = $_SESSION['dati']['E-Mail'];
                                        $result = $db->query("SELECT * FROM MetodoPagamento WHERE Cliente = '$email'");
                                        if ($result->num_rows > 0) {
                                            echo "<fieldset><label>Metodi di pagamento usati in precedenza: ";
                                            echo "<select name='old_method' form='pay'>";
                                            echo "<option value=-1 selected=''>(Nuovo Metodo di Pagamento)</option>";
                                            while ($record = $result->fetch_assoc()) {
                                                if ($record['Tipo'] == 1) {
                                                    echo "<option value=$record[ID]>Carta di Credito ($record[Codice])</option>";
                                                } else if ($record['Tipo'] == 0) {
                                                    echo "<option value=$record[ID]>Altro</option>";
                                                }
                                            }
                                            echo "</select></label></fieldset>";
                                        }
                                    }
                                }
                                ?>
                                    <fieldset class="dati_carrello">
                                        <label>Tipo nuovo metodo di pagamento:
                                            <select name="new_method" form="pay">
                                                <option value="1">Carta di Credito</option>
                                                <option value="0">Altro</option>
                                            </select>
                                        </label>
                                        <label>Codice carta di credito:
                                            <input id="carrello_numero_carta" name="Codice" placeholder="Numero Carta">
                                        </label>
                                        <label>Data di scadenza (MM-AA):
                                            <input id="carrello_scadenza_carta" name="Scadenza" placeholder="Data di scadenza">
                                        </label>
                                        <label>Codice di sicurezza a 3 cifre:
                                            <input id="carrello_codice_sicurezza" placeholder="Codice di Sicurezza" name="CodSicurezza">
                                        </label>
                                        <input class="svuota svuota2 avanti" type="submit" value="Avanti">
                                    </fieldset>
                                </form>
                            </section>
                            <?php break;
                        case 4:
                            $db = mysqli_connect("localhost", "writer", "", "progetto");
                            if (!$db) {
                                echo "connection failed: " . mysqli_connect_error();
                            } else {
                                $ok = false;
                                $prods = $_SESSION['prod'];
                                if ($_SESSION['insert']) {
                                    $result1 = $db->query("INSERT INTO Cliente
                                              VALUES ('" . $_SESSION['dati']['E-Mail'] . "','" . 'NULL' . "','" . $_SESSION['dati']['Nome'] . "','" . $_SESSION['dati']['Cognome'] . "','" . $_SESSION['dati']['CAP'] . "','"
                                        . $_SESSION['dati']['Via'] . "','" . $_SESSION['dati']['Citta'] . "','" . $_SESSION['dati']['Civico'] . "');");
                                } else {
                                    $result1 = true;
                                }
                                if ($result1) {
                                    $email = $_SESSION['dati']['E-Mail'];
                                    if ((isset($_POST['old_method']) && $_POST['old_method'] == -1) || !isset($_POST['old_method']) ) {
                                        if ($_POST['new_method'] == 0) {
                                            $result2 = "INSERT INTO MetodoPagamento(Cliente, Intestatario, Tipo) VALUES ($email, $email, 0)";
                                        } else if ($_POST['new_method'] == 1) {
                                            $result2 = "INSERT INTO MetodoPagamento(Cliente, Intestatario, Tipo, Codice, Scadenza, CodSicurezza) VALUES ($email, $email, 1, $_POST[Codice], $_POST[Scadenza], $_POST[CodSicurezza])";
                                        }
                                        if (isset($result2) && $result2) {
                                            $pay_id = $db->query("SELECT MAX(ID) as pay_id FROM MetodoPagamento WHERE Cliente = $email")->fetch_assoc()['pay_id'];
                                        }
                                    } else {
                                        $pay_id = $_POST['old_method'];
                                    }
                                }
                                if (isset($pay_id)) {
                                    for ($i = 0; $i < sizeof($prods); $i++) {
                                        $quantita = $prods[$i]['quantita'];
                                        for ($a = 0; $a < $quantita; $a++) {
                                            $id = $prods[$i]['id'];
                                            $cond = $prods[$i]['cond'];
                                            $sconto = $prods[$i]['sconto'];
                                            try {
                                                $purchased = $db->query("SELECT ID FROM ProdottoInNegozio WHERE Prodotto = '$id' AND Condizione = '$cond' AND Sconto = '$sconto' LIMIT 1");
                                                $real_id = $purchased->fetch_assoc()['ID'];
                                                $result3 = $db->query("UPDATE ProdottoInNegozio SET Venduto = 1 WHERE ID = $real_id");
                                                $result4 = $db->query("INSERT INTO Acquisto(Prodotto, Cliente, Pagamento, Stato) VALUES('$real_id', '$email', '$pay_id', 'Nuovo')");
                                            } catch (Exception $e) {
                                                echo "<p>Errore nell'aggiunta dell'ordine: ".$db->error."</p>";
                                                break;
                                            }
                                        }
                                        if ($a != $quantita) {
                                            break;
                                        }
                                    }
                                    if ($i == sizeof($prods)) {
                                        $ok = true;
                                    }
                                }
                                if ($ok) {
                                    session_unset();
                                    session_destroy();
                                    if (isset($_COOKIE['ordini'])) {
                                        unset($_COOKIE['ordini']);
                                        setcookie('ordini', null, -1, '/');
                                    }
                                    echo "<p>Ordine eseguito correttamente!</p>";
                                }
                                $db->close();
                            }
                            break;
                        default:
                            echo("<p>Errore nell'interpretazione del passo corrente</p>");
                    }
                }
            } ?>
        </section>
    </section>
</main>

</body>
</html>
