<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mostrar usuario</title>
</head>
<body>
  <a href="/contacts/<?=$contact['id']?>/edit">Modificar contacto</a>
  <br>
  Nombre: <?=$contact['name']?><br>
  Email: <?=$contact['email']?><br>
  Telefono: <?=$contact['phone']?><br>
  <form action="/contacts/<?=$contact['id']?>/delete" method="post">
    <button type="submit" class="btn btn-danger" >Borrar</button>
  </form>
  <p><a href="/contacts">Volver</a></p>
</body>
</html>