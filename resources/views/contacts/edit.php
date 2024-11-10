<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Contacto</title>
</head>
<body>
  <form action="/contacts/<?=$contact['id']?>" method="post">
    <div>
      <label from="name">Nombre</label>
      <input type="text" name='name' value ='<?=$contact['name']?>'>
    </div>
    <div>
      <label from="name">Email</label>
      <input type="text" name='email' value ='<?=$contact['email']?>'>
    </div>
    <div>
      <label from="name">Telefono</label>
      <input type="number" name='phone' value ='<?=$contact['phone']?>'>
    </div>
    <div>
      <button type="submit" class="btn btn-primary"> Modificar </button>
      </div>
      
  </form>
</body>
</html>