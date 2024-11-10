<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  
  <h1>Hola desde la vista Contact</h1>

<?php
if(!empty($registers)){
  foreach($registers as $register){
    extract($register);
    echo "- ".$id." -- ".$name."<br>";
  };
}

?>

</body>
</html>