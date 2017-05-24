<!DOCTYPE html>

<html lang="it">
  <body>
    <?php
    $target_dir = "pictures/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          session_start();
          //print_r($_POST);
          //print_r($_SESSION);
          $db = mysqli_connect("localhost", $_SESSION["user"], $_SESSION["password"] != null ? $_SESSION["password"] : "", "progetto");
          if ($db) {
              $_POST['potenza'] != null ? $potenza = $_POST['potenza'] : $potenza = "NULL";
              $_POST['rif'] != null ? $rif = $_POST['rif'] : $rif = "NULL";
              $_POST['ningressi'] != null ? $ningressi = $_POST['ningressi'] : $ningressi = "NULL";
              $_POST['potmax'] != null ? $potmax = $_POST['potmax'] : $potmax = "NULL";
              $_POST['nvie'] != null ? $nvie = $_POST['nvie'] : $nvie = "NULL";
              $_POST['formati'] != null ? $formati = $_POST['formati'] : $formati = "NULL";
              $_POST['dac'] != null ? $dac = $_POST['dac'] : $dac = "NULL";
              $_POST['uscite'] != null ? $uscite = $_POST['uscite'] : $uscite = "NULL";
            $result = $db->query("INSERT INTO Prodotto (Codice, Costo, Descrizione, Produttore, Nome, img, Tipo, Potenza, RiF, N_Ingressi, Pot_Max, N_Vie, Formati, Dac, Uscita) 
                                        VALUES ('$_POST[id]', '$_POST[costo]', '$_POST[desc]', '$_POST[produttore]', '$_POST[nome]', '$target_file', '$_POST[tipo]', 
                                        '$potenza', '$rif', '$ningressi', '$potmax', '$nvie', '$formati', '$dac', '$uscite')");
            if ($result) {
              echo "<h3>Prodotto aggiunto correttamente!</h3>";
            } else {
              echo "<h3>Errore Query</h3>";
              echo $db->error;
              echo $db->errno;

            }
            $db->close();
          } else {
            echo "<h3>Errore DB</h3>";
          }
//          session_unset();
//          session_destroy();
          //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    echo "<a href='pagina.php'>TORNA ALLA PAGINA INIZIALE</a>";
    ?>
  </body>
</html>
