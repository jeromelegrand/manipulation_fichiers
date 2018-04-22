<?php

//modification du fichier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fileEdit'])) {
    $content = $_POST['fileEdit'];
    $directory = $_POST['directory'];

    $file = fopen($directory, 'w');
    fwrite($file, $content);
    fclose($file);
}

//édition du fichier
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['directory'])) {
    $directory = $_GET['directory'];
    ?>
        <form method="POST">
            <div class="form-group">
                <input type="hidden" name="directory" value="<?= $directory ?>">
                <label for="fileEdit">Edition du fichier</label>
                <textarea id="fileEdit" name="fileEdit" class="form-control"><?php echo file_get_contents($directory) ?></textarea>
            </div>
            <input class="btn" type="submit" value="Modifier">
        </form>
    <?php
}



if (isset($_POST['directory']) && isset($_POST['delete'])) {

    $directory = $_POST['directory'];

    removeDirectory($directory);
}

function removeDirectory($path) {
    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    if (is_dir($path)) {

        rmdir($path);
    } else {
        unlink($path);
    }
    return;
}

?>

<?php include('inc/head.php'); ?>

<?php

$baseDir = 'files/';

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir), RecursiveIteratorIterator::SELF_FIRST);
foreach($objects as $name => $object){
    if (!in_array($object->getFileName(), ['.', '..'])) {
        echo $name . '<br>';
        echo '<form method="POST">
                  <div class="form-group">
                      <input type="hidden" name="delete" value="true">
                      <input type="hidden" name="directory" value="' . $object->getPathName() . '">
                      <input class="btn" type="submit" value="Supprimer">
                  </div>   
              </form>';
        if (!is_dir($name)) {
            echo '<form method="GET">
                  <input type="hidden" name="directory" value="' . $object->getPathName() . '">
                  <input class="btn" type="submit" value="Editer">
              </form>';
        }
    }
}


?>

<?php include('inc/foot.php'); ?>
