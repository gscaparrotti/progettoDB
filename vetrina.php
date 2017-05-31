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
        $result = $db->query("SELECT ID, Produttore, Nome, img, Costo, Sconto, Disp, Descrizione
                                    FROM (SELECT ProdottoInNegozio.Prodotto as ID, ProdottoInNegozio.Sconto as Sconto, Count(*) as Disp FROM ProdottoInNegozio 
                                    WHERE Venduto = 0
                                    GROUP BY ProdottoInNegozio.Prodotto
                                    ORDER BY DataFornitura DESC
                                    LIMIT 1)ProdottoPiuRecente 
                                    INNER JOIN Prodotto on ProdottoPiuRecente.ID = Prodotto.Codice");
        if ($result) {
          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
              <?php if($row["img"] != null) { ?>
                <h3><?php echo $row["Produttore"]." ".$row["Nome"]; ?></h3>
                <img class="last_prdt_img" alt="immagine del prodotto" src="<?php echo $row["img"]; ?>">
              <?php } ?>
              <div class="dettagli_last">
                <p class="descrizione"><?php echo $row["Descrizione"]; ?></p>
                <p><?php echo $row["Costo"]. " €"; ?></p>
                <div class="disp_inner">
                  <p id="pezzi_disponibili"><?php echo $row["Disp"]; ?></p>
                  <p><?php
                            if ($row["Disp"] == 1) {
                              echo " pezzo disponibile";
                            } else {
                              echo " pezzi disponibili";
                            }?></p>
                </div>
                <button id="<?php echo $row["ID"]?>" class="detail_button" type="button">Informazioni</button>
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
        $result = $db->query("SELECT Prodotto, Produttore, Nome, img, Costo, Sconto, Vendite, Descrizione, Disp
                                    FROM (SELECT Prodotto, Sconto, Count(NULLIF(0, ProdottoInNegozio.Venduto)) as Vendite, Count(NULLIF(1, ProdottoInNegozio.Venduto)) as Disp FROM ProdottoInNegozio 
                                    GROUP BY ProdottoInNegozio.Prodotto
                                    HAVING Disp > 0
                                    ORDER BY Vendite DESC
                                    LIMIT 3)ProdottiPiuVenduti
                                    INNER JOIN Prodotto on ProdottiPiuVenduti.Prodotto = Prodotto.Codice");
        if ($result) {
          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { ?>
              <table id="<?php echo $row["id_prodotto"]?>" class="tabella_prodotti">
                <?php if($row["img"] != null) { ?>
                  <tr><td class="prdt_img_row"><img class="prdt_img" alt="immagine del prodotto" src="<?php echo $row["img"]; ?>"></td></tr>
                <?php } ?>
                <tr><td><?php echo $row["Produttore"]." ".$row["Nome"]; ?></td></tr>
                <tr><td><?php echo $row["Costo"]. " €"; ?></td></tr>
                <tr><td><?php echo $row["Disp"];
                          if ($row["Disp"] == 1) {
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
