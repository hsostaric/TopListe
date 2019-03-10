<!DOCTYPE html>
<html>
    <head>
        <title>PHP - Primjer file upload</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
        $userfile = $_FILES['userfile']['tmp_name'];
        $userfile_name = $_FILES['userfile']['name'];
        $userfile_size = $_FILES['userfile']['size'];
        $userfile_type = $_FILES['userfile']['type'];
        $userfile_error = $_FILES['userfile']['error'];
        if ($userfile_error > 0) {
            echo 'Problem: ';
            switch ($userfile_error) {
                case 1: echo 'Veličina veća od ' . ini_get('upload_max_filesize');
                    break;
                case 2: echo 'Veličina veća od ' . $_POST["MAX_FILE_SIZE"] . 'B';
                    break;
                case 3: echo 'Datoteka djelomično prenesena';
                    break;
                case 4: echo 'Datoteka nije prenesena';
                    break;
            }
            exit;
        }

        $upfile = 'slike/'.$userfile_name;
        
        if (is_uploaded_file($userfile)) {
            if (!move_uploaded_file($userfile, $upfile)) {
                echo 'Problem: nije moguće prenijeti datoteku na odredište';
                exit;
            }
        } else {
            echo 'Problem: mogući napad prijenosom. Datoteka: ' . $userfile_name;
            exit;
        }

        echo 'Datoteka uspješno prenesena<br /><br />';
        ?>
    </body>
</html>
