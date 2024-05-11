<?php
//Crear la clase
class mdlRoles{
    //Vamos a crear el método para fijar los datos
    public function __SET($atributo,$valor){
        $this->$atributo = $valor;
    }

    //Método para reclamar los datos cuando sean necesarios
    public function __GET($atributo){
        return $this->$atributo;
    }

    //Crear la conexión a la DB
    public function __construct($db){
        //Intentar conectar
        try{
            $this->db = $db;
        }catch(PDOException $e){
            exit("Error al conectar a la base de datos");
        }
    }

    //Método para traer los datos de los roles
    public function getRoles(){
        //Creamos la consulta
        $sql = "SELECT * FROM roles ORDER BY Descripcion ASC";
        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>