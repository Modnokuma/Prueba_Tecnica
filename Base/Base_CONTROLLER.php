<?php
include_once '../Base/Base_Validations.php';

/**
 * Base_CONTROLLER
 * This class acts as a base controller for handling requests and delegating actions to the appropriate service.
 *
 * @var array $estructura Structure of the entity.
 * @var array $valores Key-value pairs representing the values of the entity's attributes.
 * @var array $listaAtributos List of attributes of the entity.
 * @var string $controlador Controller name for the entity.
 * @package Beta_TFG
 * @subpackage Base
 */

class Base_CONTROLLER extends Base_Validations
{
    /**
     * __construct()
     * Constructor for the Base_CONTROLLER class.
     * @return void
     */
    public function __construct()
    {
        
        $controlador = controlador;
       
        include_once "../" . $controlador . "/" . $controlador . "_description.php";
        $description = controlador . '_description';
        // Inicializar las propiedades heredadas
        $this->estructura = $$description;
        $this->valores = variables;
        $this->listaAtributos = array_keys($this->estructura['attributes']);
        $this->controlador = controlador;

        $respuesta_validations = $this->validations();
        
        if (is_array($respuesta_validations)) {
            //  Si existen errores
            responder($respuesta_validations);
        }

        // Si no existen errores
        $serviceFile = "../" . $controlador . "/" . $controlador . "_SERVICE.php";


        // Comprobar si los archivos existen antes de incluirlos
        if (!file_exists($serviceFile)) {
            $serviceName = "Base_SERVICE";
            include_once "../Base/Base_SERVICE.php";
        } else {
            $serviceName .= "_SERVICE";
            include_once $serviceFile;
        }

        // Instancia del service.
        $service = new $serviceName($this->estructura, action, $this->valores, $this->controlador);
        $accion = action;
        responder($service->$accion());
    }
}


/*if (method_exists(get_class($this),"data_attribute_personalize")){

            $this->data_attribute_personalize();
        
        }*/