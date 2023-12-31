<!DOCTYPE html>
<html>
<head>
    <title>File Upload Example</title>
    <script>
        function validateForm() {
            var fileInput = document.getElementById("file");
            var descriptionInput = document.getElementById("description");

            if (fileInput.value === "" || descriptionInput.value === ""|| ip.value === "") {
                alert("Vui lòng điền đầy đủ thông tin trước ");
                return false; // Ngăn việc gửi biểu mẫu
            }
        }
    </script>
</head>
<body>
    <h1>Upload File CSV</h1>
    <form action="queryvt.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
        <label for="file">Select a file:</label>
        <input type="file" name="file" id="file">
         <br>
         <label for="namecustomer">Ten :</label>
        <select id="namecustomer" name="namecustomer">
            <option value="temp"></option>
            <option value="soict">soict</option>
            <option value="hust">hust</option>
        </select>
        <br>
        <label for="IP : ">IP:</label>
        <input type="text" name="ip" id="ip">
         <br>
        <label for="description">File Description:</label>
        <input type="text" name="description" id="description">
         <br>

        <input type="submit" value="Upload">
    </form>
</body>
</html>
