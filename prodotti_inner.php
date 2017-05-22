<?php
  $db = mysqli_connect("localhost", "reader", "", "progetto");
  if(!$db) {
    echo "connection failed: ".mysqli_connect_error();
  } else {
    $allowed = true;
    mysqli_set_charset($db, "utf8");
    if ($_POST != null) {
      if (isset($_POST['ordina']) && ($_POST['ordina'] == "Nome" || $_POST['ordina'] == "Costo")) {
        $ordina = $_POST['ordina'];
      } else {
        $ordina = "Nome";
      }
      if (isset($_POST['ricerca'])) {
        if (preg_match("/^\S*$/", $_POST["ricerca"])) {
          $filter = "'%".$_POST['ricerca']."%'";
        } else {
          $filter = false;
          $allowed = false;
        }
      } else {
        $filter = false;
      }
      $max = 4;
      if (isset($_POST['page'])) {
        $temp = $_POST['page'] - 1;
        $offset = $temp * $max;
      } else {
        $offset = 0;
      }
      $part1 = "SELECT SQL_CALC_FOUND_ROWS Prodotto.Codice, Prodotto.Produttore, Prodotto.Nome, Prodotto.img, Prodotto.Costo, Count(NULLIF(1, ProdottoInNegozio.Venduto)) as disponibile
                from Prodotto inner join ProdottoInNegozio
                on Prodotto.Codice = ProdottoInNegozio.Prodotto ";
      $part2 = "WHERE ";
      $part3 = "LOWER(Nome) LIKE LOWER($filter) ";
      $part9 = "ProdottoInNegozio.Venduto = 0 ";
      $part8 = "LOWER(Produttore) LIKE LOWER($filter) ";;
      $part4 = "AND ";
      $part7 = "OR ";
      $part5 = "ORDER BY $ordina ASC
                LIMIT $max
                OFFSET $offset;";
      $part6 = "GROUP BY Prodotto.Codice ";
      //echo $part1.$part2.$part3;
      if (!$filter) {
        $result = $db->query($part1.$part6.$part5);
      } else {
        $result = $db->query($part1.$part2.$part3.$part7.$part8.$part6.$part5);
      }
      $rows_count = $db->query("SELECT FOUND_ROWS();");
    }
    if ($result && $rows_count) {
      if(!$allowed) {
        echo "<p style='inline-block'>La stringa di ricerca contiene caratteri illegali</br>Sono visualizzati tutti i prodotti</p>";
      }
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) { ?>
          <table id="<?php echo $row["Codice"]?>" class="tabella_prodotti">
            <?php if(isset($row["img"]) && $row["img"] != null) { ?>
              <tr><td class="prdt_img_row"><img class="prdt_img" alt="immagine del prodotto" src="<?php echo $row["img"]; ?>"></td></tr>
            <?php } ?>
            <tr><td><?php echo $row["Produttore"]." ".$row["Nome"]; ?></td></tr>
            <tr><td><?php echo 'Prezzo di Listino: '.$row["Costo"]. " €"; ?></td></tr>
            <tr><td><?php echo $row["disponibile"];
                      if ($row["disponibile"] == 1) {
                        echo " pezzo disponibile";
                      } else {
                        echo " pezzi disponibili";
                      }?></td></tr>
          </table>
        <?php
        }
        ?>
        <section id="result_pages">
          <p class="result_pages_text">Pagina: </p>
          <?php
            $total = $rows_count->fetch_array()[0];
            $pages = ($total / $max);
            if ($total % $max != 0) {
              $pages++;
            }
            for ($i=1; $i<=$pages; $i++) {
              if ((isset($_POST['page']) && $i == $_POST['page']) || (!isset($_POST['page']) && $i == 1)) { ?>
                <b class="result_pages_text result_pages_element"> <?php echo $i; ?></b>
              <?php } else { ?>
               <p class="result_pages_text result_pages_element"> <?php echo $i; ?></p> <?php
                    }
            }
          ?> </p> <p>&nbsp;</p> </section>
        <?php
      } else {
      echo "0 results";
      }
    } else {
      echo " Errore nella Query SQL: ";
      printf("Errormessage: %s\n", $db->error);
    }
    $db->close();
  }
?>
