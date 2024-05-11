<?php
//Vamos a crear la clase de este modelo, por lo general se le da el mismo nombre del archivo mdlPersona

class mdlPersona {
    //Vamos a agregar los atributos de esta clase
    public $idPersona;
    public $documento;
    public $idTipoDocumento;
    public $nombres;
    public $apellidos;
    public $telefono;
    public $direccion;
    public $email;
    public $fechaNacimiento;
    public $genero;
    public $db;

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

    //Crear el método para registrar las personas
    public function registerPerson(){
        //Vamos a crear una variable que guardará la consulta
        $sql = "INSERT INTO personas(documento, Nombres, Apellidos, Email, Telefono, Direccion, Genero, FechaNacimiento, idTipoDocumento) VALUES (?,?,?,?,?,?,?,?,?)";

        //Vamos a crear una variable para mantener lista siempre la consulta y poder enviarla o ejecutarla cada que sea llamada
        $stm = $this->db->prepare($sql);
        $stm->bindParam(1, $this->documento);
        $stm->bindParam(2, $this->nombres);
        $stm->bindParam(3, $this->apellidos);
        $stm->bindParam(4, $this->email);
        $stm->bindParam(5, $this->telefono);
        $stm->bindParam(6, $this->direccion);
        $stm->bindParam(7, $this->genero);
        $stm->bindParam(8, $this->fechaNacimiento);
        $stm->bindParam(9, $this->idTipoDocumento);
        //Respuesta
        $resultado = $stm->execute();
        return $resultado;
    }

    //Crear un método para retornar el id de la última persona registrada
    public function lastIdPerson(){
        $sql = "SELECT MAX(idPersona) AS lastIdPerson FROM personas";
        $query = $this->db->prepare($sql);
        $query->execute();
        //Respuesta
        $lastId = $query->fetchAll(PDO::FETCH_ASSOC);
        return $lastId;
    }

    //Método que nos va a permitir ver el listado de los documentos
    public function getTypeDocument(){
        //Consulta
        $sql = "SELECT idTipoDocumento, Descripcion AS doc FROM tiposdocumentos";
        $query = $this->db->prepare($sql);
        $query->execute();
        $doc = $query->fetchAll(PDO::FETCH_ASSOC);
        return $doc;
    }
}
?>