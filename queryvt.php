<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>


<body>

  <h2>Thông tin </h2>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Hash</th>
        <th>Value</th>
        <th>Link virus total</th>
      </tr>
    </thead>
    <tbody>




      <?php

      $filename = 'test.csv';
      $apiKey[0] = "302ea2c81f841b172462f49fa105980cc5bcb65fff63dfedb063ec14a6f70a5f"; // maiil randyorton
      $apiKey[1] = "983b8d30d5d2d9a5448b15697a0cbd5aa32c8e29d3336efd9644243b973dbadc"; // mail truong.nh200652@sis.hust.edu.vn
      $apiKey[2] = "6c58b3fafd376fedf1d79465237d147e4255fa8b99fc2badd3678a98c2bd89e6"; //mail nguyentruong20022002
      $apiKey[3] = "2a5d645e61c99c7fe11196f49f3b53cbf6f7fd28faf4c57f6d98ac7476e15693";
      $numberAPI = 4;
      function queryhash(&$hash, $api)
      {
        $apiUrl = "https://www.virustotal.com/vtapi/v2/file/report";
        $params = array(
          "apikey" => $api,
          "resource" => $hash
        );
        $response = file_get_contents($apiUrl . '?' . http_build_query($params));
        $report = json_decode($response);
        try {
          if ($report->response_code === 1) {
            return "$report->positives/$report->total";
          } else {
            return null;
          }
        } catch (Throwable | Exception $e) {
          sleep(3.75);
          queryhash($hash, $api);
        }
      }

      function dbupdate(&$hash, &$value)
      {
        $collection = (new MongoDB\Client)->truongnh->hash;

        $insertOneResult = $collection->insertOne([
          '_id' => $hash,
          'value' => $value,
        ]);
      }

      function DbAddNoValue(&$hash, &$value,&$namecustomer,&$ip)
      {
        $collection = (new MongoDB\Client)->truongnh->hash;

        $currentDateTime = date('Y-m-d H:i:s');

        $insertOneResult = $collection->insertOne([
          '_id' => $hash,
          'value' => $value,
          'Date1' => $currentDateTime,
          'IP1' => $ip,
          'namecustomer1'  => $namecustomer,
          'NumberQuery' => 1    ]);
      }
      function DbUpdateNoValue(&$hash, &$value,&$namecustomer,&$ip)
      {
        $collection = (new MongoDB\Client)->truongnh->hash;
        $document = $collection->findOne(['_id' => $hash]); 
        $NumbetQuery = $document->NumberQuery;
        $NumbetQuery= $NumbetQuery+1;
        $currentDateTime = date('Y-m-d H:i:s');

        $updateResult = $collection->updateOne(
          ['_id' => $hash],
          ['$set' => ['Date'.$NumbetQuery => $currentDateTime,'IP'.$NumbetQuery  => $ip,'namecustomer'.$NumbetQuery  => $namecustomer,'NumberQuery' => $NumbetQuery ]]
        );
      }


      require_once __DIR__ . '/vendor/autoload.php';

      $collection = (new MongoDB\Client)->truongnh->hash;

      if (isset($_FILES['file']) && isset($_POST['description'])  && isset($_POST['namecustomer'])) {
        $file = $_FILES['file'];
        $descrip      = $_POST['description'];
        $namecustomer  = $_POST['namecustomer'];
        $ip  = $_POST['ip'];



        if ($file['error'] === UPLOAD_ERR_OK) {
          $fileName = basename($file['name'], '.csv');
          $uploadedFileName = $fileName . $ip;
          $currentDate = date('Y-m-d');
          $uploadedFileName = $uploadedFileName . '_' . $currentDate . '.csv';

          $uploadPath = 'uploads/' . $namecustomer . '/' . $uploadedFileName; // Set your desired upload directory

          if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo 'File uploaded successfully.';
            echo '<br>';
          } else {
            echo 'Error uploading the file.';
          }
        } else {
          echo 'File upload error: ' . $file['error'];
        }



        $i = 0;

        if (($handle = fopen($uploadPath, 'r')) !== false) {
          while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if ($i > 1) {
              sleep(3.75);
            }
            echo '  <tr>';
            echo "<td>$data[0]</td>";
            $filehash = $data[1];
            echo  "<td>$filehash </td>   ";
            $document = $collection->findOne(['_id' => $filehash]); // tim kiem trong database
            // neu nhu hash da ton tai   
            if ($document == null || $document->value == "NoResource") {
              $value = queryhash($filehash, $apiKey[$i % $numberAPI]);
              if ($value != null) {
                echo "<td>$value</td>";
                echo '<td>"updatedb"<a href="https://www.virustotal.com/gui/file/' . $filehash . '" target="_blank">Link VirusTotal</a></td>';
                dbupdate($filehash, $value);
              } else {
                if ($document == null) {
                  echo "<td>No Resource</td>";
                  $NoResource = "NoResource";
                  DbAddNoValue($filehash,$NoResource,$namecustomer,$ip);
                  echo '<td><a href="detail.php?hash=' . $filehash . '" target="_blank">Link detail</a></td>';
                } else {
                  echo "<td>No Resource</td>";
                  $NoResource = "NoResource";
                  DbUpdateNoValue($filehash,$NoResource,$namecustomer,$ip);
                  echo '<td><a href="detail.php?hash=' . $filehash . '" target="_blank">Link detail</a></td>';
                }
              }
              $i++;
            } else {
              echo "<td>($document->value)</td>";
              echo '<td><a href="https://www.virustotal.com/gui/file/' . $filehash . '" target="_blank">Link VirusTotal</a></td>';
            }
            //echo '<td>' . date("H:i:s") . '</td>';

            echo '</tr>';
          }
          fclose($handle);
          echo '<script>

                  alert("Da hoan thanh");
                </script>';
        } else {
          echo 'Error opening the file.';
        }
      }

      ?>


    </tbody>
  </table>

</body>

</html>