<?php
    //Crear la clase para heredar del controlador
    class usuarioController extends Controller{
        //Crear los atributos. normalmente suelen ser en este caso una variable para llamar los modelos

        private $modeloU;
        private $modeloR;

        //Vamos a crear el constructor 
        public function __construct(){
            //Instanciar los modelos necesarios
            $this->modeloU = $this->loadModel("mdlUsuario");
            $this->modeloR = $this->loadModel("mdlRoles");
        } 

        //Vamos a crear un metodo para iniciar la sesion
        public function login(){
        // Controlar los errores
        $error = false;
        //Vamos a validar la comunicación con el modelo usuario y el formulario
        if(isset($_POST['btnLogin'])){
            $this->modeloU->__SET('usuario', $_POST['txtUser']);
            $this->modeloU->__SET('clave', $_POST['txtPassword']);

            //Lo anterior pasa a un arreglo vacío
            $_POST = [];

            //Con una variable vamos a llamar el método del modelo que nos permite validar los datos
            $validate = $this->modeloU->validateUser();

            //Vamos a revisar esa validación
            if($validate == true){
                $_SESSION['SESSION_START'] = true;
                $error = false;

                //Vamos a configurar superglobales para los atributos de la sesión
                $_SESSION['Nombres'] = $validate['Nombres'];
                $_SESSION['idUsuario'] = $validate['idUsuario'];
                $_SESSION['Apellidos'] = $validate['Apellidos'];
                $_SESSION['Documento'] = $validate['Documento'];
                $_SESSION['Usuario'] = $validate['Usuario'];
                $_SESSION['Descripcion'] = $validate['Descripcion'];

                //Despues de la validación, que me dirija a un admin
                header("Location:". URL . "usuarioController/main");
            }else{
                $error = true;
            }
        }
            require APP . 'view/usuarios/login.php';
        }

        public function main(){
            require APP . 'view/_templates/header.php';
            require APP . 'view/usuarios/main.php';
            require APP . 'view/_templates/footer.php';
        }

        //Método para cerrar sesión
        public function logOut(){
            //Validamos que hayan sesiones iniciadas
            if(isset($_SESSION['SESSION_START'])){
                session_destroy();
            }
            header("Location:".URL."home/index");
            exit();
        }

        //Método para llamar al formulario de registro de usuario
        public function userRegister(){
            //Con un condicional para el formulario y modelo
            if(isset($_POST['btnRegister'])){
                //Aquí empezamos la comunicación modelo y formulario
                $this->modeloU->__SET('idTipoDocumento', $_POST['selDocType']);
                $this->modeloU->__SET('documento', $_POST['txtDocument']);
                $this->modeloU->__SET('nombres', $_POST['txtNames']);
                $this->modeloU->__SET('apellidos', $_POST['txtLastname']);
                $this->modeloU->__SET('fechaNacimiento', $_POST['txtBirthdate']);
                $this->modeloU->__SET('telefono', $_POST['txtPhone']);
                $this->modeloU->__SET('direccion', $_POST['txtAddress']);
                $this->modeloU->__SET('email', $_POST['txtEmail']);
                $this->modeloU->__SET('genero', $_POST['txtGenere']);

                //Vamos a crear una variable que llamará al método del modelo para poder registrar los datos
                $person = $this->modeloU->registerPerson();


                //Vamos a validar que registre a partir de la última persona registrada
                if($person == true){
                    $ultimoId = $this->modeloU->lastIdPerson();

                    //foreach que se va a encargar de tomar los datos explícitos
                    foreach($ultimoId as $value){
                        $ultimoIdValue = $value['lastIdPerson'];
                    }
                }

                //Aquí vamos a enviar lso datos para el registro del usuario
                $this->modeloU->__SET('idPersona', $ultimoIdValue);
                $this->modeloU->__SET('usuario', $_POST['txtUser']);
                $this->modeloU->__SET('clave', $_POST['txtPassword']);
                $this->modeloU->__SET('idRol', $_POST['selRol']);

                //Vamos a crear una variable que llamará al método del modelo para poder registrar los datos
                $user = $this->modeloU->userRegister();
            }
            //Vamos a crear variables para hacer losllamados a los métodos de los diversos modelos
            $documentType = $this->modeloU->getTypeDocument();
            $roles = $this->modeloR->getRoles();
            require APP. 'view/_templates/header.php';
            require APP. 'view/usuarios/userRegister.php';
            require APP. 'view/_templates/footer.php';
        }
    }
?>