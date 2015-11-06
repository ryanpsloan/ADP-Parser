<?php
session_start();
//Create a file name using todays date and current time
$file = "files/Prepared Evo Export - Martin Tire.csv";
$handle = fopen($file, 'w');
$fileData = $_SESSION['strArray'];
//var_dump($fileData);
//create a .csv from updated original fileData
for($i = 0; $i < count($fileData); $i++){
    fwrite($handle, $fileData[$i]."\r\n");

}

fclose($handle);


    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

?>