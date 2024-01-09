<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>


<body>

    <h2>Thông tin chi tiết lịch sử query hash</h2>

            <?php
            // Kiểm tra xem có giá trị hash được truyền từ URL không
            if (isset($_GET['hash'])) {
                // Lấy giá trị của hash từ URL
                $filehash = $_GET['hash'];

                // Hiển thị giá trị hash hoặc thực hiện các thao tác khác với giá trị này
                echo "Thông tin filehash: " . $filehash;
            } else {
                // Trường hợp không có giá trị hash được truyền
                echo "Không có giá trị hash được truyền từ URL.";
            }
            require_once __DIR__ . '/vendor/autoload.php';

            $collection = (new MongoDB\Client)->truongnh->hash;
            $document = $collection->findOne(['_id' => $filehash]); 
            $NumbetQuery = $document->NumberQuery;



            // Bắt đầu bảng HTML
            echo '<table>';

            // Tạo hàng tiêu đề
            echo '<tr>';
                echo '<th> Name Customer </th>';
                echo '<th> IP  </th>';
                echo '<th> Date query </th>';
            echo '</tr>';
             
            for($i = 1; $i <= $NumbetQuery; $i++){
                $ip='IP'.$i;
                $customer = 'namecustomer'.$i;
                $date = 'Date'.$i;
                echo '<tr>';
                echo '<td>' . $document->$customer . '</td>';
                echo '<td>' . $document->$ip . '</td>';
                echo '<td>' . $document->$date . '</td>';
                echo '</tr>';

            }

            // Kết thúc bảng HTML
            echo '</table>';
            ?>

</body>

</html>



