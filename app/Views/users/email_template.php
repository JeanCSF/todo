<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm</title>
</head>
<body>
    <h1>Confirmação de e-mail</h1>
    <h5>Olá <?= $post['txtName']?>, obrigado por se cadastrar em nosso site</h5>
    <p>Para que você possa ter acesso é necessário que confirme o seu e-mail no link abaixo</p>
    <p><a href="<?= base_url('userscontroller/emailconfirm/'.'?key=' . $key)?>">Confirme aqui</a></p>
</body>
</html>