<?php
$servername = "localhost";
$username="root";
$password= "";
$dbname = "guestbook_db";

try{
    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Connection failed: ".$e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $sql = "INSERT INTO entries (name, email, message) VALUES (:name, :email, :message)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    $stmt->execute();
}

$sql = "SELECT * FROM entries ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guestbook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
        <h1>Welcome to Our Guestbook</h1>

        <form method="post" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>

            <button type="submit">Sign Guestbook</button>
        </form>

<h2>Guestbook Entries</h2>
<div class="entries">
            <?php foreach ($entries as $entry): ?>
                <div class="entry">
                    <h3><?php echo $entry['name']; ?></h3>
                    <p><?php echo $entry['message']; ?></p>
                    <small><?php echo date('F j, Y, g:i a', strtotime($entry['created_at'])); ?></small>
                </div>
            <?php endforeach; ?>
</div>



</div>    




</body>
</html>