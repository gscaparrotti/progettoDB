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
                        <input type="submit" name="show" value="Mostra Ordini"/>
                        <input type="submit" name="show" value="Mostra Commenti"/>
                        <input type="submit" name="show" value="Mostra Clienti"/>
                        <input type="submit" name="show" value="Aggiorna disponibilità"/>
                        <input type="submit" name="show" value="Aggiungi Prodotto"/>
                        <input type="submit" name="show" value="Rimuovi Prodotto"/>
                        <input type="submit" name="show" value="Aggiungi Categoria"/>
                    </form>
                    <?php
                    if (isset($_GET["show"])) {
                        switch ($_GET["show"]) {
                            case ("Mostra Ordini"): ?>
                                <table class="secret_element" id="tabella_ordini" style="">
                                    <?php $result = $db->query("SELECT time, Cliente.nome, cognome, citta, indirizzo, telefono, Cliente.email, codice_carta, scadenza_carta, sic_carta, Prodotti.nome, prodotto, quantita
                                    FROM (Acquisto INNER JOIN Cliente
                                    ON Acquisto.email = Cliente.email) INNER JOIN Prodotti
                                    ON Acquisto.prodotto = Prodotti.id
                                    ORDER BY time DESC");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                        ?>
                                        <tr>
                                            <?php for ($i=0; $i<sizeof($fields); $i++) {
                                                echo "<th>".$fields[$i]->name."</th>";
                                            } ?>
                                        </tr>
                                        <?php while($row = $result->fetch_row()) {
                                            echo "<tr>";
                                            for ($i=0; $i<sizeof($row) - 2; $i++) {
                                                echo "<td>".$row[$i]."</td>";
                                            }
                                            $prodotti = $row[sizeof($row) - 2];
                                            echo "<td>";
                                            for ($i = 0; $i<sizeof($prodotti);$i++) {
                                                echo "<p class='product_in_row'>".$prodotti[$i]."</p>";
                                                if ($i < sizeof($prodotti) - 1) {
                                                    echo "<p> , </p>";
                                                }
                                            }
                                            echo "</td>";
                                            echo "<td>";
                                            echo $row[sizeof($row) - 1];
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } ?>
                                </table>
                                <?php break;
                            case ("Mostra Commenti"): ?>
                                <table class="secret_element" id="tabella_commenti" style="">
                                    <?php $result = $db->query("SELECT * FROM Commenti");
                                    $result2 = $db->query("SELECT id, nome FROM Prodotti");
                                    if ($result && $result2) {
                                        $fields = $result->fetch_fields();
                                        $products;
                                        while ($row = $result2->fetch_assoc()) {
                                            $products[$row['id']] = $row['nome'];
                                        }
                                    } ?>
                                    <tr>
                                        <?php for ($i=0; $i<sizeof($fields) - 1; $i++) {
                                            echo "<th>".$fields[$i]->name."</th>";
                                        }
                                        echo "<th>prodotto</th>";?>
                                    </tr>
                                    <?php while($row = $result->fetch_row()) {
                                        echo "<tr>";
                                        for ($i=0; $i<sizeof($row) - 1; $i++) {
                                            echo "<td>".$row[$i]."</td>";
                                        }
                                        if ($row[sizeof($row) - 1] > 0 ) {
                                            echo "<td>";
                                            echo "<p class='product_in_row'>".$row[$i]."</p>";
                                            echo "</td>";
                                        }
                                        echo "</tr>";
                                    } ?>
                                </table>
                                <?php break;
                            case ("Mostra Clienti"): ?>
                                <table class="secret_element" id="tabella_clenti" style="">
                                    <?php $result = $db->query("SELECT * FROM Cliente");
                                    if ($result) {
                                        $fields = $result->fetch_fields();
                                    } ?>
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
                                        <label>Numero del codice a barre:
                                            <input name="id" type="number" step="1" min="0" placeholder="" required=""/>
                                        </label>
                                        <label>Nome del prodotto:
                                            <input name="nome" placeholder="Nome" required=""/>
                                        </label>
                                        <label>Prezzo del prodotto:
                                            <input name="costo" type="number" step="0.01" min="0" placeholder="Prezzo" required=""/>
                                        </label>
                                        <label>Disponibilità:
                                            <input name="disp" type="number" step="1" min="0" placeholder="Disponibilità" required=""/>
                                        </label>
                                        <label>Descrizione:
                                            <textarea name="desc" placeholder="Descrizione"required="required"></textarea>
                                        </label>
                                        <label>Caratteristiche:
                                            <textarea name="car" placeholder="Caratteristiche"required="required"></textarea>
                                        </label>
                                        <?php
                                        if(!$db) {
                                            echo "connection failed: ".mysqli_connect_error();
                                        } else {
                                            $result = $db->query("SELECT * FROM Tipo_Prodotti");
                                            if($result && $result->num_rows > 0) {
                                                echo "<label>Tipo Prodotto: ";
                                                echo "<select name='tipo' form='upload'>";
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<option value='$row[nome]'>$row[nome]</option>";
                                                }
                                                echo "</select>";
                                                echo "</label>";
                                            }
                                        }
                                        ?>
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
                            case ("Aggiungi Categoria"): ?>
                                <form class="secret_element" id="category_upload1" action='do_query.php' method="post" enctype="multipart/form-data">
                                    <fieldset class="upload_fieldset" id="new_categogry_fieldset">
                                        <label>Nome cateogria:
                                            <input id="nome_categoria" name="nome" placeholder="Nome" required=""/>
                                        </label>
                                        <label>Descrizione:
                                            <textarea id="caratteristiche_categoria" name="caratteristiche" placeholder="caratteristiche"required="required"></textarea>
                                        </label>
                                        <input id="submit_button" type="submit" value="Aggiungi Categoria" name="submit">
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