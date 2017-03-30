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
            $result = $db->query("INSERT INTO Prodotti(id, tipo_prodotto, nome, costo, img, descrizione, caratteristiche)
                                  VALUES ('".$_POST['id']."','".$_POST['tipo']."','".$_POST['nome']."','".$_POST['costo']."','".$target_file."','".$_POST['desc']."','"
                                  .$_POST['car']."');");
            if ($result) {
              $result2 = $db->query("INSERT INTO Vendite(id_prodotto, disponibile, venduti)
                                    VALUES ('".$_POST['id']."','".$_POST['disp']."','0')");
            } else {
              echo "<h1>Errore</h1>";
            }
            $db->close();
            if ($result2) {
              echo "<h3>Prodotto aggiunto correttamente!</h3>";
            } else {
              echo "<h3>Errore</h3>";
            }
          } else {
            echo "<h3>Errore</h3>";
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
