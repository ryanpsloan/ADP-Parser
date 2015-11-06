<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>

    <title>Martin Tire Parser</title>
    <style>
        body{
            background-color: lightcoral;
        }
        div {
            text-align: center;
        }
        table{
            border-collapse:collapse;
            table-layout:auto;
            vertical-align:top;
            margin-top:15px;
            margin-bottom:15px;
            margin: auto;


        }
        td, th{
            display: table-cell;
            padding: .5em;
            border: 1px solid cadetblue;
        }
        .border {
            border: 1px double cadetblue;
        }
        .button{
            border-radius: 2em;
            padding: .5em;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

</head>
<body>
  <div>
      <h1>Martin Tire Parser</h1>
      <h3>Upload a .csv file from Martin Tire</h3>
      <form action="parseFile.php" method="POST" enctype="multipart/form-data">
         <!-- <label for="company">Enter name of Client</label>
          <input type="text" id="company" name="company" required> <br><br> -->


          <table>
              <tr>
                  <td><input type="file" id="file" name="file" class="border"></td>
                  <td><input type="button" value="Show Table" id="show" class="button"></td>

              </tr>
          <tr>
                <td><input type="submit" value="Upload Data" class="button"></td>
                <td><input type="button" value="Switch Table" id="switch"  class="button"></td>
          </tr>
          <tr><a href="downloadDoc.php">Create CSV</a></tr>
          </table>


      </form>
      <hr>
      <div name="outputDiv" id="outputDiv">
          <?php

          $fileData = $_SESSION['fileData'];
          //var_dump($fileData);
          $codes = $fileData['Codes'];
          //var_dump($codes);

          echo "<table id='table'>";
          foreach ($fileData as $key => $eeData) {

              for ($p = 0; $p < count($eeData); $p++) {
                  echo "<td>" . $eeData[$p][0] . "</td ><td>" . $eeData[$p][1] . "</td>";
              }
              echo "</tr>";
          }
          echo "</table>";
          echo "<div id='lines'><article>";
            foreach($fileData as $key => $data){
                //var_dump($data);
                if($key !== "Codes") {
                    print("------------------------$key---------------------------------");
                    for ($x = 2; $x < count($data); $x++) {
                        $printed = false;
                        print($x."<br>");
                        var_dump($data[$x][0], $data[$x][1]);
                        print("_______________________________________<br>");
                        if($data[$x][0] !== "" && $data[$x][0] !== "0.00" && $data[$x][0] !== "0" &&
                           $data[$x][1] !== "" && $data[$x][1] !== "0.00" && $data[$x][1] !== "0") {
                            $str = sprintf("%s, %s, %s, %s, %s", "'".$data[0][0]."'", "'".$data[0][1]."'", "'".$codes[$x][0]."'", "'".$data[$x][0]."'", "'".$data[$x][1]."'");
                            $lines[] = $str;
                            echo "$str<br>";
                            $printed = true;
                            $x++;
                            if($data[$x][0] !== "" && $data[$x][0] !== "0.00" && $data[$x][1] !== "" & $data[$x][1] !== "0.00"){
                                $str = sprintf("%s, %s, %s, %s, %s", "'".$data[0][0]."'", "'".$data[0][1]."'", "'".$codes[$x][0]."'", "'".$data[$x][0]."'", "'".$data[$x][1]."'");
                                $lines[] = $str;
                                //echo "$str<br>";
                            }else if($data[$x][1] !== "" & $data[$x][1] !== "0.00" & $data[$x][1] !== "0"){
                                $str = sprintf("%s, %s, %s, %s", "'".$data[0][0]."'", "'".$data[0][1]."'", "'".$codes[$x][1]."'", "'".$data[$x][1]."'");
                                $lines[] = $str;
                            }
                        }
                        if($data[$x][1] !== "" & $data[$x][1] !== "0.00" && $data[$x][1] !== "0" && $printed == false){
                            $str = sprintf("%s, %s, %s, %s", "'".$data[0][0]."'", "'".$data[0][1]."'", "'".$codes[$x][0]."'", "'".$data[$x][1]."'");
                            $lines[] = $str;
                            echo "$str<br>";
                        }



                    }

                    print("+++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br>");
                }


            }
            echo "</article></div>";
            //var_dump($lines);
            $_SESSION['strArray'] = $lines;
          ?>

      </div>
      <script>
          $(document).ready(function(){
              $("#table").hide();

          });
          $("#switch").click(function(){
              $("#table").each(function() {
                  var $this = $(this);
                  var newrows = [];
                  $this.find("tr").each(function(){
                      var i = 0;
                      $(this).find("td").each(function(){
                          i++;
                          if(newrows[i] === undefined) { newrows[i] = $("<tr></tr>"); }
                          newrows[i].append($(this));
                      });
                  });
                  $this.find("tr").remove();
                  $.each(newrows, function(){
                      $this.append(this);
                  });
              });

              return false;
          });

          var bool = true;
          $("#show").click(function() {

              $("#table").toggle();

              if(bool){
                  $("#show").val('Show Lines');
                  bool = false;
              }else{
                  $("#show").val('Show Table');
                  bool = true;
              }


          });


      </script>
  </div>
  <hr>

</body>
</html>
