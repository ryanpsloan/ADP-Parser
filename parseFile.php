<?php
session_start();

if(isset($_FILES)) {
    try {
       if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error'])) {
            throw new RuntimeException("Invalid Parameters - No File Uploaded.");
        }
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException("No File Sent.");
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException("Exceeded Filesize Limit.");
            default:
                throw new RuntimeException("Unknown Errors.");
        }

        //check file size
        if ($_FILES['file']['size'] > 2000000) {
            throw new RuntimeException('Exceeded Filesize Limit.');
        }
        //var_dump($_FILES);
        $goodExts = array("csv");
        $goodTypes = array("application/vnd.ms-excel");
        $name = $_FILES["file"]["name"];
        $extension = end(explode(".", $name));
        $type = $_FILES["file"]["type"];
        $tmp_name = $_FILES["file"]["tmp_name"];
        //var_dump($goodExts, $goodTypes, $name, $extension, $type);
        if (in_array($extension, $goodExts) === false || in_array($type, $goodTypes) === false) {
            throw new RuntimeException("Invalid File Type/Extension. Page only accepts pdf Files");
        }


        /*$directory = "/var/www/html/adpparser";
        if (move_uploaded_file($tmp_name, "$directory/$name")) {
            echo "<p>File Successfully Uploaded.</p>";
        } else {
            throw new RuntimeException("Unable to Move File to /files.");
        }

        $today = new DateTime('now'); //create a date for now
        $month = $today->format("m");
        $day = $today->format('d');
        $year = $today->format('y');
        $fileName = "payrollData-$month-$day-$year.$extension";
        $newName = "$directory/$fileName";

        if ((rename("$directory/$name", $newName))) {
             echo "<p>File Renamed to: $newName </p>";
        } else {
            throw new RuntimeException("Unable to Rename File: $name");
        }*/

        //open the stream for file reading
        $rows = array();
        $fileData = array();

        //read the data in line by line
        if (($handle = fopen($tmp_name, "r")) !== FALSE)     {
            echo "<p>Stream Opened Successfully.</p>";
            $array_of_names = fgetcsv($handle);
            //var_dump($array_of_names);

            for($z = 0 ; $z < $count; $z++){
                if($array_of_names[$z] == ""){
                    unset($array_of_names[$z]);
                }
            }
            $count = count($array_of_names);
            //$array_of_names = array_values($array_of_names);
            //var_dump($array_of_names);
            rewind($handle);
            while(!feof($handle)) {
                $array_of_data = fgetcsv($handle); //gets data from file one line at a time
                $rows[] = $array_of_data; //breaks the line up into pieces that the array can store
            }
            //var_dump($rows);
            fclose($handle);
            $k = 0;
            $j = 1;
            for($n = 0; $n < count($array_of_names); $n+=2) {
                    $key = $array_of_names[$n];
                    //var_dump($key, $n, count($array_of_names));
                    for($m = 0; $m < count($rows); $m++) {
                        if($rows[$m][$k] < 0) {
                            $temp = abs($rows[$m][$k]);
                            //var_dump($temp, $rows[$m][$k]);
                            $rows[$m][$k] = (string)$temp;
                        }
                        if($rows[$m][$j] < 0){
                            $temp = abs($rows[$m][$j]);
                            $rows[$m][$j] = (string) $temp;
                            //var_dump($rows[$m][$j]);
                        }
                        $fileData[$key][] = array($rows[$m][$k], $rows[$m][$j]);

                    }
                    //var_dump($fileData[$key]);
                    array_pop($fileData[$key]);


                    $k += 2;
                    $j += 2;

            }
            //var_dump($fileData);


        }
        else if ($handle === false) {
            throw new RuntimeException("Unable to Open Stream.");
        }

        $_SESSION['fileData'] = $fileData;
        header("location: index.php");
    } catch (Exception $e) {
        echo $e->getMessage();
    }


}





?>