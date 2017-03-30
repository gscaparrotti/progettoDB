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
        $result = $db->query("SELECT * FROM Prodotti
                              INNER JOIN Vendite
                              ON Prodotti.id=Vendite.id_prodotto
                              WHERE id=$ids");
        $result2 = json_decode($db->query("SELECT caratteristiche FROM Tipo_Prodotti
                              WHERE nome=(SELECT tipo_prodotto FROM Prodotti
                                        WHERE id=$ids)")->fetch_assoc()["caratteristiche"], true);
        $result3 = json_decode($db->query("SELECT caratteristiche FROM Prodotti
                              WHERE id=$ids")->fetch_assoc()["caratteristiche"], true);
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) { ?>
            <?php if($row["img"] != null) { ?>
              <h2><?php echo $row["nome"]; ?></h2>
              <img class="last_prdt_img" alt="immagine del prodotto" src="<?php echo $row["img"]; ?>">
            <?php } ?>
            <div class="dettagli_descrizione">
              <p class="descrizione"><?php echo $row["descrizione"]; ?></p>
              <table class="detail_table">
                <tr>
                  <th colspan="2">Caratteristiche Tecniche</th>
                </tr>
                  <?php foreach ($result2["caratteristiche"] as $x => $value) { ?>
                    <tr>
                      <td><?php echo $value ?></td>
                      <td><?php if (isset($result3[$value])) { echo $result3[$value]; } else {echo "N/D";} ?></td>
                    </tr>
                  <?php } ?>
              </table>
              <div class="dettagli_bottom">
                <p><?php echo "Prezzo:  ".$row["costo"]. " €"; ?></p>
                <div class="disp_inner">
                  <p id="pezzi_disponibili"><?php echo $row["disponibile"]; ?></p>
                  <p><?php
                            if ($row["disponibile"] == 1) {
                              echo " pezzo disponibile";
                            } else {
                              echo " pezzi disponibili";
                            }?></p>
                </div>
                <button type="button" onclick="addToCart(<?php echo $ids ?>,'<?php echo $row['nome'] ?>')">Acquista</button>
              </div>
            </div>
          <?php
            }
          } else {
          echo "0 results";
          }
        $db->close();
      }
    ?>
  </section>
</section>
