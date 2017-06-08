<!DOCTYPE html>
<html>

<?php
include("head_login.html");
include("header_full.html");
?>

<body>

<main class="main2">
    <section id="content">
        <section id="a6870" class="articolo">
            <h2>I Miei Ordini</h2>
            <?php
                if (isset($_POST['buyer'])) {
                    mysqli_report(MYSQLI_REPORT_STRICT);
                    $db = null;
                    try {
                        $db = mysqli_connect("localhost", 'writer', "", "progetto");
                    } catch (Exception $e) {
                        echo "<p>Dati inseriti non corretti. Riprovare.</p>";
                    }?>
                    <table class="secret_element" id="tabella_miei_ordini" style="">
                        <?php $result = $db->query("SELECT Cliente AS Email, Data, Prodotto, Stato FROM Acquisto WHERE Cliente = '$_POST[buyer]'");
                        if ($result) {
                            $fields = $result->fetch_fields();
                        }?>
                        <tr>
                            <?php for ($i=0; $i<sizeof($fields); $i++) {
                                echo "<th>".$fields[$i]->name."</th>";
                            } ?>
                        </tr>
                        <?php
                        while($row = $result->fetch_row()) {
                            echo "<tr>";
                            for ($i=0; $i<sizeof($row); $i++) {
                                echo "<td>".$row[$i]."</td>";
                            } ?>
                            <?php
                            echo "</tr>";
                        } ?>
                    </table>
                <?php } else { ?>
                    <form action="miei_ordini.php" method="post" id="miei_ordini_form">
                        <label>Inserire il proprio indirizzo email:
                            <input id="email_buyer" name="buyer" type="text" placeholder="E-Mail"/>
                        </label>
                        <input id="data_inserted_button" type="submit" value="Avanti">
                    </form>
                <?php }
            ?>
        </section>
    </section>
</main>
</body>
</html>