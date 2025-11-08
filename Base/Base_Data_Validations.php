<?php

/**
 * Base_Data_Validations
 * This class is responsible for checking the data against the structure and rules of an entity.
 *
 * @var array $estructura Structure of the entity.
 * @var array $valores Key-value pairs representing the values of the entity's attributes.
 * @var array $listaAtributos List of attributes of the entity.
 * @var object $objetoentidad Object representing the entity being validated.
 * @var string $controlador Name for the entity.
 * @package Beta_TFG
 * @subpackage Base
 */
class Base_Data_Validations
{
    protected $estructura;
    protected $valores;
    protected $listaAtributos;
    protected $objetoentidad;
    protected $controlador;

    /**
     * __construct($estructura, $valores, $listaAtributos, $entidad, $controlador)
     *
     * Initializes a new instance of the class with the provided parameters.
     *
     * @param array $estructura Structure of the entity.
     * @param array $valores Values to be validated against the entity.
     * @param array $listaAtributos List of attributes of the entity.
     * @param object $entidad Object representing the entity being validated.
     * @param string $controlador Name of the entity.
     */
    public function __construct($estructura, $valores, $listaAtributos, $entidad, $controlador)
    {
        $this->estructura = $estructura;
        $this->valores = $valores;
        $this->listaAtributos = $listaAtributos;
        $this->objetoentidad = $entidad;
        $this->controlador = $controlador;
    }

    /**
     * data_validations()
     * Executes all data validation checks. Including: null checks, search validations, and custom rules.
     *
     * @return boolean|array True if all validations are passed, an array with feedback details if not.
     */
    public function data_validations()
    {

        $respuesta = true;

        // $controller_exist = $this->controller_exists();
        // if ($controller_exist !== true) {
        //     return $controller_exist;
        // }

        $nulos = $this->null_test();

        if (!is_bool($nulos)) {
            return $nulos;
        }

        $search_by = $this->null_search_by();

        if ($search_by !== true) {
            return $search_by;
        }

        $validaciones = $this->validations();

        if ($validaciones !== true) {
            return $validaciones;
        }

        return $respuesta;
    }

    // public function controller_exists(){
    //     if($this->controlador == ''){
    //         $feedback['ok'] = false;
    //         $feedback['code'] = 'CONTROLLER_NOT_EXISTS';
    //         $feedback['resources'] = false;
    //         return $feedback;
    //     }
    //     return true;
    // }

    /**
     * null_test()
     * Checks if any attribute that is marked as 'not null' is actually null or empty.
     *
     * @return boolean|array True if all 'not null' attributes are valid, or a feedback object with details if not.
     */
    public function null_test()
    {

        foreach ($this->listaAtributos as $atributo) {

            if (isset($this->estructura['attributes'][$atributo]['not_null'][action])) {

                if (!($this->estructura['attributes'][$atributo]['not_null'][action])) {
                    continue;
                } else {
                    if ((!(isset($this->valores[$atributo]))) || ($this->valores[$atributo] == '')) {

                        if (!isset($this->estructura['attributes'][$atributo]['default_value'])) {
                            $feedback['ok'] = false;
                            $feedback['code'] = $atributo . '_is_null_KO';
                            $feedback['resources'] = $atributo . 'es nulo, debe tener un valor.';
                            return $feedback;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * null_search_by()
     * Checks if at least one attribute is provided for a SEARCH_BY action.
     *
     * @return boolean|array True if at least one attribute is provided, or a feedback object with details if not.
     */
    public function null_search_by()
    {
        if (action == "SEARCH_BY") {

            foreach ($this->listaAtributos as $atributo) {
                if (isset($this->valores[$atributo])) {
                    return true;
                }
            }

            $feedback['ok'] = false;
            $feedback['code'] = 'search_by_null_KO';
            $feedback['resources'] = 'No se ha proporcionado ningun atributo para buscar.';
            return $feedback;
        }
        return true;
    }

    /**
     * validations()
     * Applies all validation rules defined for the entity's attributes.
     *
     * @return boolean|array True if all validations pass, or a feedback object with details if not.
     */
    public function validations()
    {
        if (action != 'DELETE') {

            foreach ($this->listaAtributos as $atributo) {

                if (isset($this->valores[$atributo])) {

                    if (isset($this->estructura['attributes'][$atributo]['rules']['validations'][action])) {
                        $nomval = $this->estructura['attributes'][$atributo]['rules']['validations'][action];
                        foreach ($nomval as $key => $value) {
                            $test = 'validate_' . $key;

                            $resultado = $this->$test($atributo, $this->valores[$atributo]);

                            if ($resultado !== true) {
                                return $resultado;
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * validate_min_size($atributo, $valor)
     * Validates that the length of a given attribute's value meets the minimum size requirement.
     *
     * @param string $atributo Name of the attribute to validate.
     * @param mixed $valor Value of the attribute to validate.
     * @return boolean|array True if the value meets the minimum size, or a feedback object with details if not.
     */
    public function validate_min_size($atributo, $valor)
    {
        if (isset($this->estructura['attributes'][$atributo]['rules']['validations'][action])) {
            if (isset($this->estructura['attributes'][$atributo]['rules']['validations'][action]['min_size'])) {
                $minSize = $this->estructura['attributes'][$atributo]['rules']['validations'][action]['min_size'];

                if (strlen($valor) < $minSize) {
                    $feedback['ok'] = false;
                    $feedback['code'] = 'min_size_' . $atributo . '_KO';
                    $feedback['resources'] = $atributo . ' debe tener al menos ' . $minSize . ' caracteres.';
                    return $feedback;
                }
            }
        }
        return true;
    }

    /**
     * validate_max_size($atributo, $valor)
     * Validates that the length of a given attribute's value does not exceed the maximum size requirement.
     *
     * @param string $atributo Name of the attribute to validate.
     * @param mixed $valor Value of the attribute to validate.
     * @return boolean|array True if the value meets the maximum size, or a feedback object with details if not.
     */
    public function validate_max_size($atributo, $valor)
    {
        if (isset($this->estructura['attributes'][$atributo]['rules']['validations'][action])) {
            if (isset($this->estructura['attributes'][$atributo]['rules']['validations'][action]['max_size'])) {
                $maxSize = $this->estructura['attributes'][$atributo]['rules']['validations'][action]['max_size'];

                if (strlen($valor) > $maxSize) {
                    $feedback['ok'] = false;
                    $feedback['code'] = 'max_size_' . $atributo . '_KO';
                    $feedback['resources'] = $atributo . ' no puede tener más de ' . $maxSize . ' caracteres.';
                    return $feedback;
                }
            }
        }
        return true;
    }

    /**
     * validate_exp_reg($atributo, $valor)
     * Validates that a given attribute's value matches a specified regular expression.
     *
     * @param string $atributo Name of the attribute to validate.
     * @param mixed $valor Value of the attribute to validate.
     * @return boolean|array True if the value matches the regular expression, or a feedback object with details if not.
     */
    public function validate_exp_reg($atributo, $valor)
    {
        if (isset($this->estructura['attributes'][$atributo]['rules']['validations'][action])) {
            if (isset($this->estructura['attributes'][$atributo]['rules']['validations'][action]['exp_reg'])) {
                $expReg = $this->estructura['attributes'][$atributo]['rules']['validations'][action]['exp_reg'];

                if (!preg_match($expReg, $valor)) {
                    $feedback['ok'] = false;
                    $feedback['code'] = 'exp_reg_' . $atributo . '_KO';
                    $feedback['resources'] = $atributo . ' no cumple con el formato requerido.';
                    return $feedback;
                }
            }
        }
        return true;
    }

    /**
     * validate_personalized($atributo, $valor)
     * Executes a custom validation method defined in the entity for a specific attribute.
     *
     * @param string $atributo Name of the attribute to validate.
     * @param mixed $valor Value of the attribute to validate.
     * @return boolean|array True if the custom validation passes, or a feedback object with details if not.
     */
    public function validate_personalized($atributo, $valor)
    {
        if (isset($this->estructura['attributes'][$atributo]['rules']['validations'][action]['personalized'])) {
            $method = $this->estructura['attributes'][$atributo]['rules']['validations'][action]['personalized'];

            $exists = $this->function_exists($method);

            if ($exists == true) {
                $personalized = eval('return $this->objetoentidad->' . $method . ';');

                if ($personalized !== true) {

                    return $personalized;
                }
            } else {
                return $exists; // Si no existe el método, devuelve el feedback de error
            }

            return true;
        }
    }

    /**
     * function_exists($method)
     * Checks if a method exists in the entity object.
     *
     * @param string $method Name of the method to check with atributes.
     * @return boolean|array True if the method exists, or a feedback object with details if not.
     */
    public function function_exists($method)
    {
        if (preg_match('/^([a-zA-Z0-9_]+)\s*\(/', $method, $matches)) {
            $methodName = $matches[1];
        }
        if (method_exists($this->objetoentidad, $methodName)) {
            return true;
        } else {
            $feedback['ok'] = false;
            $feedback['code'] = 'personalized_method_not_exists_' . $method . '_KO';
            $feedback['resources'] = 'El método personalizado ' . $method . ' no existe';
            return $feedback;
        }
    }
}


//var_dump($method); // ¿Qué devuelve?
//var_dump(get_class($this->objetoentidad)); // ¿Es usuario_CONTROLLER?
//var_dump(method_exists($this->objetoentidad, 'personalized_correo_usuario')); // ¿Devuelve true?
