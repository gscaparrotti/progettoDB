<section id="a10" class="articolo">
  <header>
    <h2>Carrello</h2>
  </header>
<?php
  if ($_POST == null) { ?>
      <section id="carrello_page_inner">
        <table id="carrello_page_table" class="carrello_table carrello_table_page">
        </table>
        <button class="svuota svuota2" onclick="emptyCart()">Svuota Carrello</button>
        <button class="svuota svuota2" onclick="finisciAcquisto('1')">Avanti</button>
      </section>
  <?php } else if ($_POST['step'] == 1) { ?>
    <section id="carrello_page_inner">
      <h4>Inserisci i tuoi dati personali<h4>
      <fieldset class="dati_carrello">
        <label>Nome:
          <input id="carrello_nome" placeholder="Nome"></input>
        </label>
        <label>Cognome:
          <input id="carrello_cognome" placeholder="Cognome"></input>
        </label>
        <label>Città:
          <input id="carrello_citta" placeholder="Città"></input>
        </label>
        <label>Indirizzo:
          <input id="carrello_indirizzo" placeholder="Indirizzo"></input>
        </label>
        <label>Telefono:
          <input id="carrello_telefono" type="number" placeholder="Telefono"></input>
        </label>
        <label>E-Mail:
          <input id="carrello_email" type="email" placeholder="E-Mail"></input>
        </label>
        <input class="svuota svuota2 avanti" type="submit" onclick="finisciAcquisto('2')" value="Avanti">
      </fieldset>
    </section>
    <?php } else if ($_POST['step'] == 2) { ?>
      <h4>Riepilogo</h4>
      <h5>Prodotti nel Carrello</h5>
      <table id="carrello_page_table" class="carrello_table carrello_table_page">
      </table>
      <h5>Dati Inseriti</h5>
      <p>Nome: <?php echo $_POST['nome'] ?></p>
      <p>Cognome: <?php echo $_POST['cognome'] ?></p>
      <p>Città: <?php echo $_POST['citta'] ?></p>
      <p>Indirizzo: <?php echo $_POST['indirizzo'] ?></p>
      <p>Telefono: <?php echo $_POST['telefono'] ?></p>
      <p>E-Mail: <?php echo $_POST['email'] ?></p>
      <button class="svuota svuota2" onclick="finisciAcquisto('3')">Avanti</button>
    <?php } else if ($_POST['step'] == 3) { ?>
      <section id="carrello_page_inner">
        <h4>Inserisci i dati della tua carta di credito<h4>
        <fieldset class="dati_carrello">
          <label>Codice carta di credito:
            <input id="carrello_numero_carta" placeholder="Numero Carta"></input>
          </label>
          <label>Data di scadenza (MM-AA):
            <input id="carrello_scadenza_carta"></input>
          </label>
          <label>Codice di sicurezza a 3 cifre:
            <input id="carrello_codice_sicurezza" placeholder="Codice di Sicurezza"></input>
          </label>
          <input class="svuota svuota2 avanti" type="submit" onclick="finisciAcquisto('4')" value="Completa Acquisto">
        </fieldset>
      </section>
    <?php } else if ($_POST['step'] == 4) {
      $db = mysqli_connect("localhost", "writer", "", "progetto");
      if(!$db) {
        echo "connection failed: ".mysqli_connect_error();
      } else {
        $ok = true;
        $prod = explode(",", $_POST['prodotti']);
        $unique = array_unique($prod);
        $amounts = array_count_values($prod);
        $result1 = $db->query("REPLACE INTO Cliente
                               VALUES ('".$_POST['nome']."','".$_POST['cognome']."','".$_POST['citta']."','".$_POST['indirizzo']."','".$_POST['telefono']."','"
                               .$_POST['email']."','".$_POST['codice']."','".$_POST['scadenza']."','".$_POST['codiceSicurezza']."');");

       if($result1) {
         for ($i=0; $i<sizeof($unique); $i++) {
           $b = $unique[$i];
           $result = $db->query("INSERT INTO Acquisto(email, prodotto, quantita)
                         VALUES ('".$_POST['email']."','".$unique[$i]."','".$amounts[$b]."');");

           if ($result) {
             $result2 = $db->query("UPDATE Vendite
                                    SET venduti = venduti + $amounts[$b], disponibile = disponibile - $amounts[$b]
                                    WHERE id_prodotto = $unique[$i]");
             if(!$result2) {
               echo " Errore nella Query SQL: ";
               printf("Errormessage: %s\n", $db->error);
               $ok = false;
               break;
             }
           } else {
             echo " Errore nella Query SQL: ";
             printf("Errormessage: %s\n", $db->error);
             $ok = false;
             break;
           }
         }
       } else {
         $ok = false;
       }
        if ($ok) {
          echo "<p>Ordine eseguito correttamente!</p>";
          echo "<script> ordineFinito() </script>";
        } else {
          echo $db->error;
        }
      $db->close();
    }
  }?>
  </section>
