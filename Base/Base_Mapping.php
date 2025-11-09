<?php
include '../common/credentialsDB.php';

/**
 * Base_Mapping
 * This class provides the base functionality for database operations such as ADD, EDIT, DELETE, and SEARCH.
 * It handles the connection to the database, executes SQL queries, and constructs responses based on the results.
 *
 * @package Beta_TFG
 * @subpackage Base
 * @var mysqli $conn Database connection instance.
 * @var string $query SQL query to be executed.
 * @var boolean $ok Indicates if the operation was successful.
 * @var string $code Response code for the operation.
 * @var array $feedback Feedback object containing operation details.
 * @var string $resource Resource affected by the operation.
 * @var string $host Database host.
 * @var string $userbd Database user for production.
 * @var string $passuserbd Database password for production.
 * @var string $bd Database name for production.
 * @var array $rows Array to hold rows fetched from the database.
 */

class Base_Mapping
{

    private $conn;
    public $query;
    public $ok = true;
    public $code = '00000';
    public $feedback = array();
    public $resource = '';
    private $host = host;
    private $userbd = userbd;
    private $passuserbd = passuserbd;
    private $bd = bd;
    public $rows = array();

    /**
     * connection()
     * This method establishes a connection to the database
     * @return boolean Returns true if the connection is successful, false otherwise.
     */
    function connection()
    {
        try {
                $this->conn = new mysqli($this->host, $this->userbd, $this->passuserbd, $this->bd) or die('fallo conexion');
                return true;
        } catch (Exception $e) {
                return false;
        }
    
    }

    /**
     * close_connection()
     * This method closes the database connection.
     */
    private function close_connection()
    {
        $this->conn->close();
    }

    /**
     * execute_simple_query()
     * This method executes a SQL query (ADD, EDIT, DELETE).
     * It checks the connection, executes the query, and constructs a response based on the result.
     * @return array Returns an array with the operation details, it doesn't matter if it was successful or not.
     */
    public function execute_simple_query()
    {

        if (!($this->connection())) {

            $this->ok = false;
            $this->code = 'CONEXION_KO';
            //llama a la funcion que construye el mensaje respuesta
            $this->construct_response();
            return $this->feedback;
        } else {

            //Ejecutamos la query
            $result_query = $this->conn->query($this->query);

            if ($result_query != true) {
                //Ha sucedido un error
                $this->ok = false;
                $this->code = 'SQL_KO';
                $this->resource = $this->query;
                //llamamos al metodo que construye el mensaje
                $this->construct_response();
                return $this->feedback;
            } else {
                //La operacion tiene exito
                $this->ok = true;
                $this->code = 'SQL_OK';
                if (action == 'ADD') {
                    //$lastid = $this->conn->query(SELECT LAST_INSERT_ID());
                    $this->resource = mysqli_insert_id($this->conn);
                } else {
                    $this->resource = $this->query;
                }
                //llamamos al metodo que construye el mensaje
                $this->construct_response();
                return $this->feedback;
            }
        }

        $this->close_connection();
    }

    /**
     * get_results_from_query()
     * This method executes a SQL query using the SEARCH action.
     * It checks the connection, executes the query, and constructs a response based on the result.
     * @return array Returns an array with the operation details, it doesn't matter if it was successful or not.
     */
    public function get_results_from_query()
    {

        if (!($this->connection())) {

            $this->ok = false;
            $this->code = 'CONEXION_KO';
            //llama a la funcion que construye el mensaje respuesta
            $this->construct_response();
            return $this->feedback;
        } else {
            $result_query = $this->conn->query($this->query);

            if ($result_query != true) {

                $this->ok = false;
                $this->code = 'SQL_KO';
                $this->resource = $this->query;
                //llamamos al metodo que construye el mensaje
                $this->construct_response();
                return $this->feedback;
            } else {

                $numFilas = $result_query->num_rows;
                if ($numFilas == 0) {

                    //esta vacio
                    $this->ok = true;
                    $this->code = 'RECORDSET_VACIO';
                    $this->construct_response();
                    return $this->feedback;
                } else {

                    //devuelves el contenido
                    for ($i = 0; $i < $numFilas; $i++) {
                        $this->rows[] = $result_query->fetch_assoc();
                        $this->resource = $this->rows;
                    }
                    $this->ok = true;
                    $this->code = 'RECORDSET_DATOS';
                    $this->construct_response();
                    return $this->feedback;
                }
            }

            $this->close_connection();
        }
    }


    /**
     * personalized_query()
     * This method executes a personalized SQL query.
     * @param string $queryPrueba
     * @return mixed Returns the result of the query if successful, or an array with feedback if it fails.
     */
    public function personalized_query($queryPrueba)
    {

        if (!($this->connection())) {

            $this->ok = false;
            $this->code = 'CONEXION_KO';
            $this->construct_response();
            return $this->feedback;
        } else {

            $result_query = $this->conn->query($queryPrueba);
            if ($result_query != true) {

                $this->ok = false;
                $this->code = 'SQL_KO';
                $this->resource = $queryPrueba;
                $this->construct_response();
                return $this->feedback;
            }
            return $result_query;
        }
    }

    /**
     * personalized_prepared_query()
     * Executes a prepared statement with bound parameters. Safer alternative to personalized_query when using user input.
     * @param string $sql SQL with ? placeholders
     * @param array $params Array of values to bind
     * @return mixed mysqli_result on success or feedback array on error
     */
    public function personalized_prepared_query($sql, $params = array())
    {
        if (!($this->connection())) {
            $this->ok = false;
            $this->code = 'CONEXION_KO';
            $this->construct_response();
            return $this->feedback;
        }

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            $this->ok = false;
            $this->code = 'SQL_PREPARE_KO';
            $this->resource = $this->conn->error;
            $this->construct_response();
            return $this->feedback;
        }

        if (!empty($params)) {
            // determine types
            $types = '';
            foreach ($params as $p) {
                if (is_int($p)) {
                    $types .= 'i';
                } elseif (is_double($p) || is_float($p)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }

            // bind params - call_user_func_array requires references
            $bind_names = array();
            $bind_names[] = $types;
            for ($i = 0; $i < count($params); $i++) {
                $bind_names[] = &$params[$i];
            }

            call_user_func_array(array($stmt, 'bind_param'), $bind_names);
        }

        $exec = $stmt->execute();
        if ($exec === false) {
            $this->ok = false;
            $this->code = 'SQL_EXECUTE_KO';
            $this->resource = $stmt->error;
            $this->construct_response();
            return $this->feedback;
        }

        // try to get result (for SELECT queries)
        $result = $stmt->get_result();
        if ($result === false) {
            // query succeeded but no resultset (e.g., UPDATE/DELETE)
            return true;
        }

        return $result;
    }

    /**
     * construct_response()
     * This method constructs the the feedback array with the operation details.
     */
    public function construct_response()
    {
        $this->feedback['ok'] = $this->ok;
        $this->feedback['code'] = $this->code;
        $this->feedback['resources'] = $this->resource;
    }
}
