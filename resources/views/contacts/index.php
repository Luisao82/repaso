<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div class="container mx-auto">
    <h1>Listado contactos</h1>
    <form action="/contacts" class='flex'>
      <input type="text" name='search' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar" 
              value = '<?=isset($_GET['search']) ? $_GET['search'] : "" ?>' />
      <button type="submit" class="ml-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>
    
    <a href="/contacts/create">Crear Contacto</a>
    <br><br>
    <ul>
      <?php foreach($contacts['data'] as $contact): ?>
        <li>
          <a  href='/contacts/<?= "{$contact['id']}"?>'>
            <?= "{$contact['name']} - {$contact['email']}  - {$contact['phone']}" ?>
          </a> 
        </li>
      <?php endforeach ?>
    </ul>

  <?php

      $pagination = "contacts";
      
      include_once('../resources/views/asset/pagination.php');
  ?>
  </div>

</body>
</html>