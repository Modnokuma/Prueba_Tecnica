<?php

/**
 * Base_Validations
 * This class combines data and action validations to ensure that all operations comply with the defined rules.
 *
 * @var array $estructura structure of the entity.
 * @var array $valores Key-value pairs representing the values of the entity's attributes.
 * @var array $listaAtributos List of attributes of the entity.
 * @var string $controlador Name of the entity.
 * @package Beta_TFG
 * @subpackage Base
 */

include_once '../Base/Base_Action_Validations.php';
include_once '../Base/Base_Data_Validations.php';

class Base_Validations
{

    protected $estructura;
    protected $valores;
    protected $listaAtributos;
    protected $controlador;

    /**
     * validations()
     * This method performs validations on the data and actions based on the provided structure and values.
     *
     * @return boolean|array Returns true if all validations pass, or an array with feedback if any validation fails.
     */
    public function validations()
    {
        $respuesta = true;

        $data_validations = new Base_Data_Validations($this->estructura, $this->valores, $this->listaAtributos, $this, $this->controlador);
        $respuesta_data_validations = $data_validations->data_validations();

        if ($respuesta_data_validations !== true) {
            return $respuesta_data_validations;
        }

        $action_validations = new Base_Action_Validations($this->estructura, $this->valores, $this->listaAtributos, $this->controlador);
        $respuesta_action_validations = $action_validations->action_validations();

        if ($respuesta_action_validations !== true) {
            return $respuesta_action_validations;
        }

        return $respuesta;
    }
}
