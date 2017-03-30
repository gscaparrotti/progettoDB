<section id="a2" class="articolo">
  <header>
    <h2>Ultimo Prodotto</h2>
  </header>
  <section class="vetrina">
    <?php
      $db = mysqli_connect("localhost", "reader", "", "progetto");
      mysqli_set_charset($db, "utf8");
      if(!$db) {
        echo "connection failed: ".mysqli_connect_error();
      } else {
        $result = $db->query("SELECT * FROM Prodotti
                              INNER JOIN Vendite
                              ON Prodotti.id=Vendite.id_prodotto
                              WHERE disponibile > 0
                              ORDER BY reg_date DESC
                              LIMIT 1;");
        if ($result) {
          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
              <?php if($row["img"] != null) { ?>
                <h3><?php echo $row["nome"]; ?></h3>
                <img class="last_prdt_img" alt="immagine del prodotto" src="<?php echo $row["img"]; ?>">
              <?php } ?>
              <div class="dettagli_last">
                <p class="descrizione"><?php echo $row["descrizione"]; ?></p>
                <p><?php echo $row["costo"]. " €"; ?></p>
                <div class="disp_inner">
                  <p id="pezzi_disponibili"><?php echo $row["disponibile"]; ?></p>
                  <p><?php
                            if ($row["disponibile"] == 1) {
                              echo " pezzo disponibile";
                            } else {
                              echo " pezzi disponibili";
                            }?></p>
                </div>
                <button type="button" onclick="addToCart(<?php echo $row["id_prodotto"] ?>,'<?php echo $row['nome'] ?>')">Acquista</button>
                <button id="<?php echo $row["id_prodotto"]?>" class="detail_button" type="button">Informazioni</button>
              </div>
            <?php
            }
          } else {
          echo "0 results";
          }
        } else {
          echo " Errore nella Query SQL";
        }
        $db->close();
      }
    ?>
  </section>
</section>
<section id="a1" class="articolo">
  <header>
    <h2>Prodotti in vetrina</h2>
  </header>
  <p>Questi sono i prodotti attualmente in vetrina:<br></p>
  <section class="vetrina">
    <?php
      $db = mysqli_connect("localhost", "reader", "", "progetto");
      if(!$db) {
        echo "connection failed: ".mysqli_connect_error();
      } else {
        mysqli_set_charset($db, "utf8");
        $result = $db->query("SELECT * FROM Prodotti
                              INNER JOIN Vendite
                              ON Prodotti.id=Vendite.id_prodotto
                              ORDER BY venduti DESC
                              LIMIT 3;");
        if ($result) {
          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
              <table id="<?php echo $row["id_prodotto"]?>" class="tabella_prodotti">
                <?php if($row["img"] != null) { ?>
                  <tr><td class="prdt_img_row"><img class="prdt_img" alt="immagine del prodotto" src="<?php echo $row["img"]; ?>"></td></tr>
                <?php } ?>
                <tr><td><?php echo $row["nome"]; ?></td></tr>
                <tr><td><?php echo $row["costo"]. " €"; ?></td></tr>
                <tr><td><?php echo $row["disponibile"];
                          if ($row["disponibile"] == 1) {
                            echo " pezzo disponibile";
                          } else {
                            echo " pezzi disponibili";
                          }?></td></tr>
              </table>
            <?php
            }
          } else {
          echo "0 results";
          }
        } else {
          echo " Errore nella Query SQL";
        }
        $db->close();
      }
    ?>
  </section>
</section>
