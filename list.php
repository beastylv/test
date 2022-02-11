<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=test', 'root', 'test12345');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;

    if (!$email) {
        header('Location: /list.php');
        exit;
    }

    $statement = $pdo->prepare("DELETE FROM emails WHERE email = :email");

    $statement->bindValue(':email', $email);

    $statement->execute();

    header('Location: /list.php');

    exit;
}

$search = $_GET['search'] ?? '';

$statement = $pdo->prepare('SELECT * FROM emails WHERE email LIKE :search ORDER BY date DESC');
$statement->bindValue(':search', "%$search%");
$statement->execute();

$emails = $statement->fetchAll(PDO::FETCH_ASSOC);

$searchProviders = [];

foreach ($_GET as $key => $state) {
    if ($state === 'on') $searchProviders[] = str_replace('_', '.', $key);
}

$providers = [];
$emailList = [];

foreach ($emails as $email) {
    $provider = explode('@', $email['email'])[1];
    
    if (!in_array($provider, $providers)) $providers[] = $provider;

    if (in_array($provider, $searchProviders) || empty($searchProviders)) $emailList[] = $email;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <title>Newsletter</title>
    </head>

    <body style="padding: 50px 400px;">
        <form action="">
            <div style="padding-bottom: 20px;">
                <input style="width: 350px;" type="text" placeholder="Search for emails" name="search" value="<?php echo $search ?>">
                <?php if (!empty($providers)) { ?>
                <h1>Providers</h1>
                <?php foreach ($providers as $p) { ?>
                <input type="checkbox" id="<?php echo $p; ?>" name="<?php echo $p; ?>">
                <label for="<?php echo $p; ?>"><?php echo $p; ?></label>
                <?php } ?>
                <?php } ?>
                <br>
                <button style="margin-top: 10px;" type="submit">Search</button>
            </div>
        </form>
        <table style="width:100%; border:1px solid black;">
            <tr>
                <th style="border:1px solid black;">Email</th>
                <th style="border:1px solid black;">Date added</th>
                <th style="border:1px solid black;">Actions</th>
            </tr>
            <?php foreach ($emailList as $email) { ?>
            <tr>
                <td style="border:1px solid black;"><?php echo $email['email']; ?></td>
                <td style="border:1px solid black;"><?php echo $email['date']; ?></td>
                <td style="border:1px solid black;">
                    <form action="" method="post">
                        <input type="hidden" name="email" value="<?php echo $email['email']; ?>">
                        <button style="margin: 3px;" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </body>
</html>