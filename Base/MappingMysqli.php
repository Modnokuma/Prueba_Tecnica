<?php
include_once '../Base/Base_Mapping.php';

/**
 * MappingMysqli
 * This class extends `Base_Mapping` and provides specific implementations for database operations using MySQLi.
 *
 * @package Beta_TFG
 * @subpackage Base
 * @var string $query SQL query to be executed.
 * @var string $tabla Name of the database table associated with the entity.
 * @var array $valores Key-value pairs representing the values of the entity's attributes.
 * @var array $clavesPrimarias Primary keys of the entity.
 * @var string $aux Auxiliary variable for temporary storage.
 * @var boolean $existeFK Indicates if a foreign key exists.
 * @var array $listaAtributos List of attributes of the entity.
 * 
 */

class Mapping extends Base_Mapping
{

    public $query;
    public $tabla;
    public $valores = [];
    public $clavesPrimarias = [];
    public $aux;
    public $existeFK;
    public $listaAtributos = [];

    /**
     * __construct()
     * Constructor for the Mapping class.
     * 
     */
    function __construct() {}

    /**
     * mapping_ADD()
     * This method constructs an SQL INSERT query to add new data to the database.
     * @return void
     */
    function mapping_ADD()
    {

        $this->query = "INSERT INTO " . $this->tabla . " ( ";
        $total = count($this->listaAtributos);
        $i = 0;

        foreach ($this->listaAtributos as $atributo) {

            if (isset($this->estructura['attributes'][$atributo]['autoincrement'])) {
                $i++;
                continue;
            }
            
            $this->query = $this->query . $atributo;

            if (++$i !== $total) {
                $this->query .= ", ";
            }
        }

        $this->query = $this->query . ") VALUES (";
        $i = 0;

        foreach ($this->listaAtributos as $atributo) {

            if (isset($this->estructura['attributes'][$atributo]['autoincrement'])) {
                $i++;
                continue;
            }
            

            if ((isset($this->estructura['attributes'][$atributo]['type']))) {
                if ($this->estructura['attributes'][$atributo]['type'] != "integer") {
                    $this->query = $this->query . "'" . $this->valores[$atributo] . "'";
                } else {
                    $this->query = $this->query . $this->valores[$atributo];
                }
            }

            if (++$i !== $total) {
                $this->query .= ", ";
            }
        }

        $this->query = $this->query . ")";
        return $this->execute_simple_query();
    }

    /**
     * mapping_EDIT()
     * This method constructs an SQL UPDATE query to edit existing data in the database.
     * @return void
     */
    function mapping_EDIT()
    {

        $this->query = "UPDATE " . $this->tabla . " SET ";

        $total = count($this->listaAtributos);
        $i = 0;
        foreach ($this->listaAtributos as $atributo) {
            $this->query = $this->query . $atributo . " = ";

            if ((isset($this->estructura['attributes'][$atributo]['type']))) {
                if ($this->estructura['attributes'][$atributo]['type'] != "integer") {
                    $this->query = $this->query . "'" . $this->valores[$atributo] . "'";
                } else {
                    $this->query = $this->query . $this->valores[$atributo];
                }
            }

            if (++$i !== $total) {
                $this->query .= ", ";
            }
        }

        $cadena = $this->construirWhereIgual($this->valores);
        $this->query = $this->query . " WHERE " . $cadena;

        return $this->execute_simple_query();
    }

    /**
     * mapping_SEARCH()
     * This method constructs an SQL SELECT query to search for data in the database.
     * It uses a WHERE clause to filter results based on the provided values.
     * @return void
     */
    function mapping_SEARCH()
    {
        $nuevos_valores = [];
        $this->query = "SELECT * FROM " . $this->tabla;
        $query = '';

        if (!empty($this->valores)) {

            // Para construir el where solo con los datos necesarios
            foreach ($this->valores as $clave => $valor) {
                if ($valor != '') {
                    $nuevos_valores[$clave] = $valor;
                }
            }

            // Si no tienes datos hace una busqueda vacia
            if (!empty($nuevos_valores)) {
                $query = $query . " WHERE (";
                // se añadiria a la cadena de busqueda los valores
                $query = $query . $this->construirWhereLike($nuevos_valores);
                $query = $query . ")";
                //$query = $query . " COLLATE latin1_general_ci";
            }
        }
        
        $this->query .= $query;

        return $this->get_results_from_query();
    }

    /**
     * construirWhereLike()
     * This method constructs a WHERE clause for an SQL query using LIKE.
     * @param array $valores Key-value pairs representing the attributes and their values.
     * @return string The constructed WHERE clause.
     */
    function construirWhereLike($valores)
    {
        $cadena = '';
        $primero = true;

        foreach ($valores as $clave => $valor) {

            if ($primero) {
                $primero = false;
            } else {
                $cadena = $cadena . " AND ";
            }

            $cadena = $cadena . "(" . $clave . " LIKE " .  "'%" . $valor . "%')";
        }

        return $cadena;
    }
    /**
     * mapping_DELETE()
     * This method constructs an SQL DELETE query to remove data from the database.
     * @return void
     */
    function mapping_DELETE()
    {
        $this->query = 'DELETE FROM ' . $this->tabla . " WHERE ";
        $this->query .= $this->construirWhereIgual($this->valores);
        return $this->execute_simple_query();
    }
    /**
     * mapping_SEARCH_BY()
     * This method constructs an SQL SELECT query to search for data in the database using specific attributes.
     * @return void
     */
    function mapping_SEARCH_BY()
    {
        $nuevos_valores = [];
        $this->query = "SELECT * FROM " . $this->tabla;
        $query = '';
        if (!empty($this->valores)) {
            // Para construir el where solo con los datos necesarios
            foreach ($this->valores as $clave => $valor) {
                if ($valor != '') {
                    $nuevos_valores[$clave] = $valor;
                }
            }

            $query = $query . " WHERE (";
            $query = $query . $this->construirWhereIgualSearchBy($nuevos_valores);
            $query = $query . ")";
        }

        $this->query .= $query;
        return $this->get_results_from_query();
    }
    /**
     * construirWhereIgual($valores)
     * This method constructs a WHERE clause for an SQL query using '='.
     * @param array $valores Key-value pairs representing the attributes and their values.
     * @return string The constructed WHERE clause.
     */
    function construirWhereIgual($valores)
    {
        $cadena = '';
        $primero = true;

        foreach ($valores as $clave => $valor) {
            if (array_key_exists($clave, $this->clavesPrimarias)) {

                if ($primero) {
                    $primero = false;
                } else {
                    $cadena = $cadena . " AND ";
                }

                ($this->estructura['attributes'][$clave]['type'] == 'integer') ? $valor  : $valor = "'$valor'";
                $cadena = $cadena . "( " . $clave . " = " . $valor . ")";
            }
        }

        return $cadena;
    }
    /**
     * construirWhereIgualSearchBy($valores)
     * This method constructs a WHERE clause for an SQL query using '=' for specific attributes.
     * @param array $valores Key-value pairs representing the attributes and their values.
     * @return string The constructed WHERE clause.
     */
    function construirWhereIgualSearchBy($valores)
    {
        $cadena = '';
        $primero = true;

        foreach ($valores as $clave => $valor) {
            if ($primero) {
                $primero = false;
            } else {
                $cadena = $cadena . " AND ";
            }

            $cadena = $cadena . "(" . $clave . " = " . (is_string($valor) ? "'$valor'" : $valor) . ")";
        }

        return $cadena;
    }

    //Borrar???
    /**
     * foreignKeyExists()
     * This method checks if a foreign key exists in the specified table.
     *
     * @param [string] $table
     * @param [string] $foreignKey
     * @param [mixed] $value
     * @return void
     */
    function foreignKeyExists($table, $foreignKey, $value)
    {

        $queryPrueba = "SELECT COUNT(*) as count FROM $table WHERE $foreignKey = $value";
        $result_query = $this->personalized_query($queryPrueba);

        $rows = $result_query->fetch_all(MYSQLI_ASSOC);
        $numApariciones = intval($rows[0]['count']);

        if ($numApariciones == 0) {
            // La clave foranea no existe
            return false;
        } else {

            return true;
        }

        return $result_query;
    }
}
