<?php

/**
 * Base_SERVICE
 * This class acts as a service layer, handling business logic and delegating database operations to the model.
 *
 * @var object $model Instance of the model associated with the entity.
 * @var array $listaAtributos List of attributes of the entity.
 * @var array $valores Key-value pairs representing the values of the entity's attributes.
 * @var array $estructura Structure of the entity.
 * @var string $accion Current action being performed (e.g., ADD, EDIT, DELETE).
 * @var string $controlador Controller name for the entity.
 * @package Beta_TFG
 * @subpackage Base
 */

class Base_SERVICE
{

    public $model;
    public $listaAtributos = array();
    public $valores = array();
    public $estructura;
    public $accion;
    public $controlador;
    public $prueba;

    /**
     * __construct($estructura, $action, $variables, $controlador)
     * Constructor for the Base_SERVICE class.
     * Initializes the service with the provided structure, action, variables, and controller.
     * 
     * @param array $estructura Structure of the entity.
     * @param string $action Action that is going to be performed (ADD, EDIT, SEARCH, DELETE).
     * @param array $variables Key-value pairs. They are the names and values of the entity's attributes.
     * @param string $controlador name of the entity which is going to be used.
     */
    function __construct($estructura, $action, $variables, $controlador)
    {

        $this->accion = $action;
        $this->valores = $variables;
        $this->controlador = $controlador;
        $this->estructura = $estructura;
        $this->listaAtributos = array_keys($this->estructura['attributes']);

        // crear en base service en base al nombre de la clase instanciada
        $this->model = $this->crearModelo($this->controlador);
    }

    /**
     * crearModelo($controlador)
     * This method creates an instance of the model. It checks if a specific model
     * file exists for the controller; if not, it uses Base_MODEL.
     *
     * @param string $controlador name of the entity which is going to be used.
     * @return void
     */
    function crearModelo($controlador)
    {

        $modelFile = "./app/" . $controlador . "/" . $controlador . "_MODEL.php";

        if (!file_exists($modelFile)) {
            $entidad_modelo = "Base_MODEL";
            include_once "../Base/Base_MODEL.php";
        } else {
            $entidad_modelo = $controlador . "_MODEL";
            include_once $modelFile;
        }

        $this->model = new $entidad_modelo;

        // Si existe la lista de atributos de la entidad se rellena el model
        if (isset($this->listaAtributos)) {
            $this->rellenarModelo($this->listaAtributos);
        }

        $this->model->estructura = $this->estructura;
        $this->model->tabla = $controlador;
        // Aqui deberiamos rellenar el atributo $this->model->clavesPrimarias con el array de PK  

        return $this->model;
    }

    /**
     * rellenarModelo($listaAtributos)
     * This method fills the model.
     *
     * @param array $listaAtributos List of attributes of the entity.
     * @return void
     */
    function rellenarModelo($listaAtributos)
    {
        $clavesPrimarias = array();
        foreach ($listaAtributos as $atributo) {

            if (!isset($this->valores[$atributo])) {
                //Aqui deberiamos comprobar si tiene un valor predeterminado. Hacerlo en un futuro.
                if (isset($this->estructura['attributes'][$atributo]['default_value'])) {
                    $this->valores[$atributo] = $this->estructura['attributes'][$atributo]['default_value'];
                } else {
                    $this->valores[$atributo] = '';
                }
            }
            //Si el atributo es una PK, lo guardamos en el array de claves primarias
            if (isset($this->estructura['attributes'][$atributo]['pk']) && isset($this->valores[$atributo])) {
                $clavesPrimarias[$atributo] = $this->valores[$atributo];
            }

            $this->model->valores[$atributo] = $this->valores[$atributo];
        }

        $this->model->clavesPrimarias = $clavesPrimarias;
        $this->model->listaAtributos = $this->listaAtributos;
    }

    /**
     * ADD()
     * This method is used to add new data to the database.     *
     * @return string The current action.
     */
    function ADD()
    {

        return $this->model->ADD();
    }

    /**
     * EDIT()
     * This method is used to edit data in the database.
     * @return void
     */
    function EDIT()
    {

        return $this->model->EDIT();
    }

    /**
     * SEARCH()
     * This method is used to search for data in the database.
     * @return void
     */
    function SEARCH()
    {

        return $this->model->SEARCH();
    }
    /**
     * SEARCH_BY()
     * This method is used to search for data in the database based on specific values.
     * @return void
     */
    function SEARCH_BY()
    {

        return $this->model->SEARCH_BY();
    }

    /**
     * DELETE()
     * This method is used to delete data from the database.
     * @return void
     */
    function DELETE()
    {

        return $this->model->DELETE();
    }

    /**
     * ejecutarPersonalizedQuery($query)
     * This method executes a personalized query on the database.
     * @param string $query The personalized query.
     * @return string The current action.
     */
    function ejecutarPersonalizedQuery($query)
    {
        return $this->model->personalized_query($query);
    }
}
