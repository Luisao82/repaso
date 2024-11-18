<?php

namespace App\Models;

use mysqli;

class Model
{

  protected $db_host = DB_HOST;
  protected $db_user = DB_USER;
  protected $db_password = DB_PASSWORD;
  protected $db_name = DB_NAME;

  protected $connection;

  protected $query;
  protected $table;

  protected $sql, $data = [], $params = null;

  protected $limit = "";

  protected $select = "*";
  protected $where = "",$values = [];
  protected $orderby = '';

 
  public function __construct(){
    $this->connection(); 
  }

  public function connection(){
    
    $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);

    if($this->connection->connect_error){

      die("Error de conexión: ". $this->connection->connect_error);     

    }

  }
  /**
   * TODO: Consultas preparadas
   * @param string $sql
   * @param array $data 
   * @param string $params 
   * @return object Model
   * @throws
   */
  public function query($sql, $data = [] , $params = null) : Model {  
    if($data){

      //Para no tener que escribir s en el caso de que sea string
      if($params == null){
        $params = str_repeat('s', count($data));
      }
      /*
      Empezamos la consulta preparada
      $sql = SELECT * FROM tabla WHERE id = ?
      bind_param ('ss',parametro1,parametro2....)
      */
      $stmt = $this->connection->prepare($sql);
      
      $stmt->bind_param($params,...$data);
      $stmt->execute();

      $this->query = $stmt->get_result();

    }else{

      $this->query = $this->connection->query($sql);

    }
    
    //devuelve el objeto para poder aplicarle mas metodos de la clase
    return $this;
  }

  public function first(){

    if (empty($this->query)){
      $this->query($this->generateSelect(), $this->data, $this->params);
    }    

    return $this->query->fetch_assoc();
  }

  public function get(){

    if (empty($this->query)){
      $this->query($this->generateSelect(), $this->data, $this->params);
    }

    return $this->query->fetch_all(MYSQLI_ASSOC);
  }
 /**
   * TODO: Metodo para seleccionar los camplos a mostrar en el select.
   * @param array $columns
   * @return object Model   
   */
  public function select (...$columns): Model {

    $this->select = implode(',',$columns);

    return $this;
  }


  /**
   * TODO: Consultas preparadas
   * @param string $colum
   * @param string $operator 
   * @param array $data 
   * @param string $params 
   * @return object Model
   * @throws
   */
  /*
  Metodo where:
  Ahora lo que hace es rellenar las propiedades $sql,$data y $params, del objeto instanciado
  Posteriormente se ejecutará el metodo $this->query en los siguientes metodos ( en get(), first(), all() ... )
  */
  public function where($column,$operator,$value = null) : Model{

    if($value == null){
      $value = $operator;
      $operator = "=";
    }

    if(empty($this->where)){
      $this->where = " where {$column} {$operator} ?";
    }else{
      $this->where .= " AND {$column} {$operator} ?";
    }

    $this->data[] = $value;

    //devuelve el objeto para poder aplicarle mas metodos del objeto desde la llamada en el controlador
    return $this;

  }

  public function order_by($column, $order = 'ASC'){
    
    if(empty($this->orderby)){
      $this->orderby = " ORDER BY {$column} {$order}";  
    }else{
      $this->orderby .= ", {$column} {$order}";
    }    
    
    return $this;    
  }

  /*
  Metodo que pagina el resultado
  */
  public function paginate($registers = 5){

    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    $this->limit = " LIMIT ".(($page - 1) * $registers)." ,{$registers}";
    
    $data = $this->query($this->generateSelect(), $this->data, $this->params)->get();
   
    $route =  explode("?",trim($_SERVER['REQUEST_URI'], "/"))[0];

    $total = $this->query('SELECT FOUND_ROWS() AS total')->first()['total'];

    
    //$total = count($data);
    $datas = [
      'fist'    => 1,
      'pages'   => ceil($total / $registers),
      'total'   => $total,
      'from'    => ($page - 1) * $registers + 1,
      'current' => $page,
      'to'      => ($page - 1) * $registers + count($data),
      'next_page_url' => ($page + 1) <= (ceil($total / $registers)) ? "/{$route}?page=".$page + 1 : null,
      'prev_page_url' => ($page - 1) >= 1 ? "/{$route}?page=".$page - 1 : null,
      'data'  => $data,
    ];

    return $datas;

  }


  private function generateSelect() : string{

    $sql = "SELECT SQL_CALC_FOUND_ROWS ". $this->select ." FROM {$this->table}";
    $sql .= ($this->where ?? $this->where).($this->orderby ?? $this->orderby).($this->limit ?? $this->limit);
    
    return $sql;
  }
  //CONSULTAS

  public function all(){

    $sql = "SELECT * FROM {$this->table}";
    return $this->query($sql)->get();

  }

  public function find($id){

    $sql = "SELECT * FROM {$this->table} WHERE id = ?";

    return $this->query($sql,[$id],'i')->first();
  }



  // Metodo CREATE
  public function create ($data){

    //INSERT INTO tabla (campo1, campo2, campo3) VALUES ('valor1', 'valor2', 'valor3')
    //INSERT INTO tabla (campo1, campo2, campo3) VALUES (?, ?, ?) 

    $columns = array_keys($data);
    $columns = implode(', ',$columns);
    $values = array_values($data);   

    
    $sql = "INSERT INTO {$this->table} ({$columns}) VALUES (".str_repeat('?, ',count($data)-1)." ?)";
    
    
    $this->query($sql,$values);

    $insert_id = $this->connection->insert_id;
    
    return $this->find($insert_id);

  }

  // Metodo UPDATE
  public function update ($id,$data){
    //UPDATE table SET campo1 = ? , campo2 = ? WHERE id = ?

    foreach($data as $key => $value){
      $fields[] = "{$key} = ?";
    }

    $fields = implode(', ',$fields);

    $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";

    $values = array_values($data);
    $values[] = $id;
    
    $this->query($sql,$values);
    
    return $this->find($id);

  }

  // Metodo DELETE
  public function delete ($id){

    $deleted = $this->find($id);
    //DELETE FROM table WHERE id = x    
    $sql = "DELETE FROM {$this->table} WHERE id = ?";

    $this->query($sql, [$id], 'i');

    return $deleted;


  }
}