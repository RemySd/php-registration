<?php

$host = '127.0.0.1';
$dbName = 'php-inscription';
$port = 8889;
$username = 'root';
$password = 'root';

$pdo = new PDO("mysql:host={$host};dbname={$dbName};port={$port}", $username, $password);

$errors = [];

$isSuccess = false;

if (!empty($_POST)) {
    if (strlen($_POST['password']) < 6) {
        $errors[] = 'mot de passe trop court';
    }

    if (empty(preg_match('~[0-9]+~', $_POST['password']))) {
        $errors[] = 'Votre mot de passe dois contenir au moins 1 chiffre';
    }
}

if (!empty($_POST) && empty($errors)) {
    $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $statement = $pdo->prepare('INSERT INTO users VALUES (null, :email, :password)');
    $statement->bindValue(':email', $_POST['email']);
    $statement->bindValue(':password', $_POST['password']);
    $isSuccess = $statement->execute();

    if ($isSuccess == false) {
        $errors[] = "Echec de l'inscription";
    }

    if ($isSuccess == true) {
        // faire une redirection
        // header('location: inscription_success.php');
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Comment créer un formulaire d'inscription en PHP</title>
</head>

<body>
    <h1>Inscription</h1>

    <?php if ($_POST && $isSuccess) : ?>
        <p style="background-color: greenyellow">Inscription réussi !</p>
    <?php endif ?>

    <?php foreach ($errors as $error) : ?>
        <p style="background-color: lightcoral"><?= $error ?></p>
    <?php endforeach ?>

    <form method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" required>
        <label for="password">Password</label>
        <input type="password" name="password" required>
        <input type="submit" value="Envoyer !">
    </form>
</body>

</html>