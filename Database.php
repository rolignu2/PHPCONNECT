<?php

defined("GET_MAX") or define("GET_MAX", "MAX");
defined("GET_MIN") or define("GET_MIN", "MIN");
defined("GET_AVG") or define("GET_AVG", "AVG");
defined("GET_SUM") or define("GET_SUM", "SUM");
defined("GET_COUNT") or define("GET_COUNT", "COUNT");

define("GET_INNER_JOIN", "INNER ");
define("GET_LEFT_JOIN", "LEFT ");
define("GET_OUTER_JOIN", "OUTER ");
define("GET_FULL_INNER_JOIN", "FULL INNER ");
define("GET_LEFT_OUTER_JOIN", "LEFT OUTER ");
define("GET_RIGHT_OUTER_JOIN", "RIGHT OUTER ");

/**SE CAMBIARAN POR TRANSACCIONES ASINCRONAS*/


define("GET_MAX", "MAX");
define("GET_MIN", "MIN");
define("GET_AVG", "AVG");
define("GET_SUM", "SUM");
define("GET_COUNT", "COUNT");
define("GET_INNER_JOIN", "INNER ");
define("GET_LEFT_JOIN", "LEFT ");
define("GET_OUTER_JOIN", "OUTER ");
define("GET_FULL_INNER_JOIN", "FULL INNER ");
define("GET_LEFT_OUTER_JOIN", "LEFT OUTER ");
define("GET_RIGHT_OUTER_JOIN", "RIGHT OUTER ");

/**SE CAMBIARAN POR TRANSACCIONES ASINCRONAS*/

class Database {
    
    
    private $dsn                = NULL;
    
    private $driver             = NULL;
    
    private $db                 = NULL;
    
    private $prefix             = NULL;
    
    private $request            = NULL;
    
    var $query                  = NULL;
<<<<<<< HEAD
    
    protected $transact         = NULL;

=======
    
    protected $transact         = NULL;
    
    
>>>>>>> Dump
    public function __construct() {
        
        global $CONFIG_;
        
        /*VARIABLE QUE SERVIRA PARA VERIFICAR EL DRIVER DE CONEXION PDO**/
        $this->driver   = $CONFIG_["DB_CONFIG"]["driver"];
        $this->prefix   = $CONFIG_["DB_CONFIG"]["prefix"];
        
        //CONFIGURACION QUE TIPO DE DRIVER INSTALO
        switch ($CONFIG_["DB_CONFIG"]["driver"]){
            case "sqlite":
                     $this->dsn = "sqlite:" . $CONFIG_["DB_CONFIG"]["sqlite_db"];
                break;
            case "mysql":
            case "oci":
            case "pgsql":  
                
                    $this->dsn = $CONFIG_["DB_CONFIG"]["driver"].
                     ':host='.$CONFIG_["DB_CONFIG"]["host"].
                     ';dbname='.$CONFIG_["DB_CONFIG"]["database"];
                     
                     if(!empty($CONFIG_["DB_CONFIG"]["port"])){
                        $this->dsn .= ';port='.$CONFIG_["DB_CONFIG"]["port"];
                     }
                
                break;
        }
        
        try{
                $this->db = new PDO(
                        $this->dsn,
                        $CONFIG_["DB_CONFIG"]["user"] ,
                        $CONFIG_["DB_CONFIG"]["password"],
                        array( PDO::ATTR_PERSISTENT => true)
                );
                
               
                
        } catch (PDOException $ex){
            echo "Opps !! Algo esta mal (" . $ex->getMessage() . ")";
            $this->SetLog($ex->getMessage(), $ex->getLine());
        }

    }

    
    function CloseConection()
    {
       unset($this);
    }
    
    
    /**
     * Funciones de carcteres especiales
     * 
     * 
     */
    
    
    public function SimpleCaracter($value = NULL){
        if (empty($value)) {
            return "'";
        } else {
            return "'$value'";
        }
    }
    
    
    
    /***
     * REGION DE FUNCIONES GENERALES Y PUBLICAS 
     * QUERYS 
     * 
     */
    
    
    /**
     *@version 1.2
     *@param string $query 
     */
    public function RawQuery($query , $style = PDO::FETCH_CLASS)
    {
            $this->query = $query;
            $this->request = $this->db->query($this->query);
            $result = $this->request->fetchAll($style);
            return $result;
    }
    
    public function  CountRows(){
         return $this->request->rowCount();
    }
    
<<<<<<< HEAD
=======
    
>>>>>>> Dump
    public function CountColumn(){
        return  $this->request->columnCount();
    }
    
    public function SimpleQuery($query){
        
        $this->query = $query;
        return $this->db->exec($this->query);
    }
    
    public function BeginTrans(){
        $this->db->beginTransaction();
    }
    
    public function TransQuery($query){
        $this->transact[] = $this->db->exec($query);
    }
    
    public function EndTrans(){
        
        try{
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollBack();
            $this->SetLog($ex->getMessage(), $ex->getLine());
        }
        
    }
    
    public function ShowTransStatus(){
        return $this->transact;
    }
    
    public function BeginTrans(){
        $this->db->beginTransaction();
    }
    
    
    public function TransQuery($query){
        $this->transact[] = $this->db->exec($query);
    }
    
    public function EndTrans(){
        
        try{
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollBack();
            $this->SetLog($ex->getMessage(), $ex->getLine());
        }
        
    }
    
    public function ShowTransStatus(){
        return $this->transact;
    }
    
    
    
    
    /****
     * REGION DE FUNCIONES CRUD 
     * 
     */
    
    
     /**
     * @todo Funcion insert la cual inserta un nuevo registro en la base de datos
     * @param String $table Nombre de la tabla a insertar
     * @param Array $params parametros de la tabla a insertar
     * @version 1.5
     * 
     * <code>
     * 
     *  $params = array(
     *      "usuario"=>"rolando@gmail.com",
     *      "password"=>"1234",
     *      "activo"=>TRUE  
     * );
     * 
     *  
     *  $registro = Insert("user" , $params);
     *  if($registro) ... hacer algo
     *  
     *  //usuario , password , activo == campos 
     * 
     * </code>
     * 
     * @return bool true si se ejeuto con exito la sentencia
     */
    public function Insert($table , array $params)
    {
        if(!empty($this->prefix)){
            $table = $this->prefix . $table;
        }
        
        $q  = "INSERT INTO $table ";
        $q .= "(". implode(",", array_keys($params)).")";
        $q .= " VALUES ('" . implode("', '", array_values($params)) . "')";
        
        try{
            return $this->SimpleQuery($q);
        } catch (PDOException $ex) {
             echo "Opps !! Algo esta mal (" . $ex->getMessage() . ")";
             $this->SetLog($ex->getMessage(), $ex->getLine());
        }
        return false;
    }
    
      /**
     * @todo Funcion Update la cual actualiza una fila de la tabla asignada
     * @param String $table Nombre de la tabla a actualizar
     * @param Array $params parametros de la tabla a insertar
     * @param String $condition condicion de la actualizacion
     * @version 1.5
     * <code>
     * 
     *  $params = array(
     *      "usuario"=>"rolando@gmail.com",
     *      "password"=>"567",
     *      "activo"=>FALSE 
     * );
     * 
     *  
     *  $actualizar = Update("user" , $params , "ID like 1");
     *  if($actualizar) ... hacer algo
     * 
     * </code>
     */
    public function Update ($table , array $params , $condition )
    {
        
        
        if(!empty($this->prefix)){
            $table = $this->prefix . $table;
        }
        
        $arr_count =1;
        $this->query = "UPDATE $table SET ";
        foreach ($params as $key=>$value)
        {
            if ($arr_count != count($params)) {
                $this->query .= "$key='$value',";
            } else {
                $this->query .= "$key='$value'";
            }
            $arr_count++;
        }
        
        $this->query .= " WHERE $condition";
        
        try {
            return $this->SimpleQuery($this->query);
        }catch (PDOException $ex) {
            $this->SetLog($ex->getMessage(), $ex->getLine());
        }
        catch (Exception $ex){
            $this->SetLog($ex->getMessage(), $ex->getLine());
        }
        
        return false;
        
    }
    
    
    
     /**
     * @todo Funcion Delete la cual elimina un registro de la tabla asignada
     * @param String $table Nombre de la tabla a eliminar
     * @param String $condition condicion
     * @version 1.5
     * <code>
     *  $eliminar = Delete("user" , "ID like 1");
     *  if($eliminar) ... hacer algo
     * 
     * </code>
     */
    public function Delete($table , $condition)
    {
        
        if(!empty($this->prefix)){
            $table = $this->prefix . $table;
        }
        
        $this->query = "DELETE FROM $table WHERE $condition ";
        
        try{
            return $this->SimpleQuery($this->query); 
        }catch (PDOException $ex) {
            $this->SetLog($ex->getMessage(), $ex->getLine());
        }
        catch (Exception $ex){
            $this->SetLog($ex->getMessage(), $ex->getLine());
        }
        
        return false;
    }
    
    
     /**
     * @todo Busca dentro de la tabla asignada el valor o los valores
     * @param String $table tabla a buscar
     * @param String $condition condicion de la busqueda
     * @param Array $args argumentos de la busqueda 
     * @version 1.2
     * @return Array Retorna un array asociado por defecto $style = PDO::FETCH_ASSOC
     * 
     * <code>
     * 
     * $find = $conn->Find("datos", "Id = 8" , array( "nombre" , "apellido" , "edad" ););
     * 
     * 
     * $find = $conn->Find("datos", "Id = 8" , array("Nombre" => "name" , "valor"=>"value"));
     * 
     * </code>
     */
    public function Find($table , $condition , $args=array() , $style = PDO::FETCH_ASSOC)
    {
        $this->query = "SELECT ";
        if (count($args) == 0) {
            $this->query .= "* FROM ";
        } 
        else {
            if ($this->is_assoc($args)) {
                $c = count($args);
                $i = 1;
                foreach ($args as $key => $value) {
                    if ($c != $i) {
                        $this->query .= "$key AS $value ,";
                    } else {
                        $this->query .= "$key AS $value ";
                    }
                    $i++;
                }
                $this->query .= "FROM ";
            }else {
                $this->query .= implode(",", $args) . " FROM ";
            }
        }
        $this->query .= "$table WHERE $condition";
        return $this->RawQuery($this->query , $style);
    }
    
    
    /**
     * 
     * 
     * REGION QUERY BUILDER
     * 
     */
    
    protected $query_build        = [];
    
    
    public function clean_build(){
        $this->query_build = [] ;
        return $this;
    }
    
    
    public function select($table , $fields = array()){
        
            $this->query_build[] = "SELECT";
            
            
            if(empty($fields)){
                $this->query_build[] = "*";
            }  
            else {
                
                if(!array_key_exists(0, $fields)){
                    
                    $data  = [];
                    
                    foreach($fields as $k=>$v){
                         $data[] = "$k AS '$v'"; 
                    }
                
                    $this->query_build[] = implode(", ", $data);
                }else{
                    $this->query_build[] = implode(", ", $fields);
                }
            }
            
            $this->query_build[] = "FROM";
            $this->query_build[] = $table;
            
            return $this;
    }
    
    
    public function get_select ( $table , $field , $type = GET_MAX){
         $this->query_build[] = "SELECT";
         
         if($field != "*"){
            $this->query_build[] = "$type($field) AS '$field'";
         }else{
             $this->query_build[] = "$type($field)";
         }
         
         $this->query_build[] = "FROM";
         $this->query_build[] = $table;
         
         return $this;
    }
    
    
    public function limit($from , $to){
<<<<<<< HEAD
        
        $this->query_build[] = "LIMIT";
        $this->query_build[] = "$from , $to";
        
=======
        
        $this->query_build[] = "LIMIT";
        $this->query_build[] = "$from , $to";
        
        return $this;
    }
    
    
    public function where($name = NULL , $value = NULL ){
        
        $this->query_build[] = "WHERE";
        
        if (!empty($name)) {
            
            if(is_array($value)){
                $data = [];
                foreach ($value as $val){
                    $data[] = "'$val'";
                }
                $this->query_build[] = " $name IN (" . implode(", ", $data) . ")";
            }
            else if($name !== NULL && $value !== NULL){
                 $this->_like($name, $value);
            }
            else{
                $this->query_build[] = $name;
            }
        }
        
        return $this;
    }
    
    
    public function join($table , $value1 , $value2 , $type = GET_INNER_JOIN){
        $this->query_build[] = "$type JOIN $table ON $value1=$value2 ";
        
>>>>>>> Dump
        return $this;
    }
    
    
<<<<<<< HEAD
    public function where($name = NULL , $value = NULL ){
        
        $this->query_build[] = "WHERE";
        
        if (!empty($name)) {
            
            if(is_array($value)){
                $data = [];
                foreach ($value as $val){
                    $data[] = "'$val'";
                }
                $this->query_build[] = " $name IN (" . implode(", ", $data) . ")";
            }
            else if($name !== NULL && $value !== NULL){
                 $this->_like($name, $value);
            }
            else{
                $this->query_build[] = $name;
            }
        }
=======
    public function _or($name , $value , $type = "LIKE"){
        
        $value = $this->isString($value);
        $this->query_build[] = "OR $name $type $value";
>>>>>>> Dump
        
        return $this;
    }
    
    
<<<<<<< HEAD
    public function join($table , $value1 , $value2 , $type = GET_INNER_JOIN){
        $this->query_build[] = "$type JOIN $table ON $value1=$value2 ";
=======
    public function _and($name , $value , $type = "LIKE"){
        
        $value = $this->isString($value);
        $this->query_build[] = "AND $name $type $value";
>>>>>>> Dump
        
        return $this;
    }
    
    
<<<<<<< HEAD
    public function _or($name , $value , $type = "LIKE"){
        
        $value = $this->isString($value);
        $this->query_build[] = "OR $name $type $value";
        
        return $this;
    }
    
    
    public function _and($name , $value , $type = "LIKE"){
        
        $value = $this->isString($value);
        $this->query_build[] = "AND $name $type $value";
        
        return $this;
    }
    
    
    public function _like($name , $value){
            
         $value = $this->isString($value);
         $this->query_build[] = "$name LIKE $value";
        
         return $this;
    }
    
    
=======
    public function _like($name , $value){
            
         $value = $this->isString($value);
         $this->query_build[] = "$name LIKE $value";
        
         return $this;
    }
    
    
>>>>>>> Dump
    public function _Notlike($name , $value){
            
         $value = $this->isString($value);
         $this->query_build[] = "$name NOT LIKE $value";
        
         return $this;
    }
    
    
    public function build_string(){
            return implode("  ", $this->query_build) . ";";
    }
    
    
    public function build($style = PDO::FETCH_CLASS){
           
            try{
                return $this->RawQuery($this->build_string(), $style);
            } catch (PDOException $ex){
                 echo "Opps !! Algo esta mal (" . $ex->getMessage() . ")";
                 $this->SetLog($ex->getMessage(), $ex->getLine());
            } catch (Exception $ex){
                 echo "Opps !! Algo esta mal (" . $ex->getMessage() . ")";
                 $this->SetLog($ex->getMessage(), $ex->getLine());
            } catch (ErrorException $ex){
                 echo "Opps !! Algo esta mal (" . $ex->getMessage() . ")";
                 $this->SetLog($ex->getMessage(), $ex->getLine());
            }
            
            return NULL;
    }

    
    
    
    
    /**
     * REGION DE FUNCIONES PRIVADAS 
     * EN GENERAL
     * 
     * **/
    
    private function SetLog($error , $line  ){
        try{
            $file = new SplFileObject("db_log" , "w+");
            $file->fwrite($this->driver .
                  " ERROR IN LINE" . 
                  $line 
                  . " , THAT MESSAGE (" 
                  . $error . ")"
                  . "ON date " . date("d-M-y")
            );
            
            unset($file);
            
        } catch (Exception $ex){
            echo $ex->getMessage();
        }
    }
    
    private function isString($value){
        
        if(is_string($value)) {
            return "'$value'";
        }else{
            return $value;
        }
        
    }
    
    
}

