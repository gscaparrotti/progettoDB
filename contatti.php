<section id="contact">
  <?php
    if ($_POST) {
      $db = mysqli_connect("localhost", "commenter", "", "progetto");
      if (!$db) {
        echo "connection failed: ".mysqli_connect_error();
      } else {
        mysqli_set_charset($db, "utf8");
        if (isset($_POST["nickname"]) && isset($_POST["email"]) && isset($_POST["comment"])) {
          $result = $db->query("INSERT INTO Commenti (nickname, email, commento, id_prodotto)
                                VALUES ('".$_POST['nickname']."', '".$_POST['email']."', '".$_POST['comment']."', '".$_POST['prodotto']."');");
          if ($result) {
            echo "Commento inviato corretamente!";
          } else {
            echo "Errore nell'invio del commento. ";
            printf("Errormessage: %s\n", $db->error);
          }

        } else {
          echo "Error in receiving datas from form";
        }
      }
    } else {
  ?>
    <fieldset name="commento">
      <legend>Inviaci un commento!</legend>
      <label>
        Nick:
        <input id="nickname_area" type="text" name="nickname" autocomplete="on"
        required pattern="[a-z]{1}[a-z_]{2,19}"
        title="A nickname is composed by lowercase letters and '_'; 3 to 20 chars are allowed."
        placeholder="your_nickname">
      </label>
      <label>
        Email:
        <input id="email_area" type="email" name="email" autocomplete="on" placeholder="email@domain.ext" required="">
      </label>
      <label>
        Prodotto di riferimento:
        <?php
        $db = mysqli_connect("localhost", "reader", "", "progetto");
        if(!$db) {
          echo "connection failed: ".mysqli_connect_error();
        } else {
          $result = $db->query("SELECT * FROM Prodotti");
          if($result && $result->num_rows > 0) {
            echo "<select id='prodotto_contatti' name='tipo'>";
            echo "<option value='0'>(Nessuno)</option>";
            while($row = $result->fetch_assoc()) {
              echo "<option value='$row[id]'>$row[nome]</option>";
            }
            echo "</select>";
            echo "</label>";
            $db->close();
          }
        }
        ?>
      </label>
      <label>
        Messaggio:
        <textarea id="textarea_messaggio" name="messaggio" placeholder="scrivi qui il tuo messaggio (max 300 caratteri)" maxlength="300" required></textarea>
      </label>
    </fieldset>
    <input id="send_form" type="submit" value="Invia il commento" onclick="sendComment()">
  <?php } ?>
</section>
