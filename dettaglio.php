<section id="a3" class="articolo">
    <header>
        <h2>Dettaglio Prodotto</h2>
    </header>
    <section class="vetrina">
        <?php
        $db = mysqli_connect("localhost", "reader", "", "progetto");
        mysqli_set_charset($db, "utf8");
        if(!$db) {
            echo "connection failed: ".mysqli_connect_error();
        } else {
            $ids = $_POST['id'];
            $result = $db->query("SELECT *
                                    FROM (SELECT *, Count(NULLIF(1, ProdottoInNegozio.Venduto)) as Disp FROM ProdottoInNegozio 
                                    GROUP BY ProdottoInNegozio.Prodotto, ProdottoInNegozio.Condizione, ProdottoInNegozio.Sconto)ProdottiInNegozio
                                    INNER JOIN Prodotto on ProdottiInNegozio.Prodotto = Prodotto.Codice
                                    WHERE Codice=$ids");
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc(); ?>
                    <?php if($row["img"] != null) { ?>
                        <h2><?php echo $row['Produttore'].' '.$row["Nome"]; ?></h2>
                        <img class="last_prdt_img" alt="immagine del prodotto" src="<?php echo $row["img"]; ?>">
                    <?php } ?>
                    <div class="dettagli_descrizione">
                        <p class="descrizione"><?php echo $row["Descrizione"]; ?></p>
                        <table class="detail_table">
                            <tr>
                                <th colspan="2">Caratteristiche Tecniche</th>
                            </tr>
                            <?php switch($row['Tipo']) {
                                case 1:?>
                                    <tr>
                                        <td><?php echo 'Potenza' ?></td>
                                        <td><?php echo $row['Potenza'].' Watt' ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo 'Risposta in Frequenza' ?></td>
                                        <td><?php echo $row['RiF'] ?></td>
                                    </tr>
                                    <?php break;
                                case 2: ?>
                                    <tr>
                                        <td><?php echo 'Potenza Massima' ?></td>
                                        <td><?php echo $row['Pot_Max'].' Watt' ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo 'Numero Vie' ?></td>
                                        <td><?php echo $row['N_Vie'] ?></td>
                                    </tr>
                                    <?php break;
                                case 3: ?>
                                    <tr>
                                        <td><?php echo 'Formati' ?></td>
                                        <td><?php echo $row['Formati'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo 'DAC' ?></td>
                                        <td><?php echo $row['Dac'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo 'Uscita' ?></td>
                                        <td><?php echo $row['Uscita'] ?></td>
                                    </tr>
                                    <?php break; }?>

                        </table>
                        <?php
                        $result->data_seek(0);
                        while ($row = $result->fetch_assoc()) {
                            if ($row['Disp'] > 0) { ?>
                        <div class="dettagli_bottom">
                            <p><?php echo "Condizione: ".$row['Condizione'] ?></p>
                            <p><?php echo "Prezzo:  ".($row["Costo"] - ($row["Costo"] * ($row['Sconto'] / 100))). " €"; ?></p>
                            <div class="disp_inner">
                                <p id=<?php echo "pezzi_disponibili".$row['Condizione'].$row['Sconto'] ?>><?php echo $row["Disp"]; ?></p>
                                <p><?php
                                    if ($row["Disp"] == 1) {
                                        echo " pezzo disponibile";
                                    } else {
                                        echo " pezzi disponibili";
                                    }?></p>
                            </div>
                            <button type="button" onclick="addToCart(<?php echo $row['Codice'] ?>,'<?php echo $row["Produttore"]." ".$row['Nome'] ?>','<?php echo $row['Sconto'] ?>','<?php echo $row['Condizione'] ?>')">Acquista</button>
                        </div>
                        <?php } else {
                                echo "<h4>Prodotto Esaurito</h4>";
                            }
                            } ?>
                    </div>
                    <?php
            } else {
                echo "0 results";
            }
            $db->close();
        }
        ?>
    </section>
</section>
