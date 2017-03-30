<?php
  $db = mysqli_connect("localhost", "reader", "", "progetto");
  if(!$db) {
    echo "connection failed: ".mysqli_connect_error();
  } else {
    $allowed = true;
    mysqli_set_charset($db, "utf8");
    if ($_POST != null) {
      if (isset($_POST['ordina']) && ($_POST['ordina'] == "nome" || $_POST['ordina'] == "reg_date" || $_POST['ordina'] == "costo")) {
        $ordina = $_POST['ordina'];
      } else {
        $ordina = "nome";
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
      $part1 = "SELECT SQL_CALC_FOUND_ROWS * FROM Prodotti
                            INNER JOIN Vendite
                            ON Prodotti.id=Vendite.id_prodotto ";
      $part2 = "WHERE ";
      $part3 = "LOWER(nome) LIKE LOWER($filter) ";
      $part4 = "AND ";
      $part5 = "disponibile > 0
                ORDER BY $ordina ASC
                LIMIT $max
                OFFSET $offset;";
      //echo $part1.$part2.$part3;
      if (!$filter) {
        $result = $db->query($part1.$part2.$part5);
      } else {
        $result = $db->query($part1.$part2.$part3.$part4.$part5);
      }
      $rows_count = $db->query("SELECT FOUND_ROWS();");
    }
    if ($result && $rows_count) {
      if(!$allowed) {
        echo "<p style='inline-block'>La stringa di ricerca contiene caratteri illegali</br>Sono visualizzati tutti i prodotti</p>";
      }
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
