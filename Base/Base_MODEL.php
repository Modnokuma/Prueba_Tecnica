<?php

include_once '../Base/MappingMysqli.php';

/**
 * Base_MODEL
 * This class extends the `Mapping` class and provides specific implementations for CRUD operations.
 *
 * @var array $valores Key-value pairs representing the values of the entity's attributes.
 * @var array $listaAtributos List of attributes of the entity.
 * @var array $clavesPrimarias Primary keys of the entity.
 * @var array $estructura Structure of the entity.
 * @package Beta_TFG
 * @subpackage Base
 */

class Base_MODEL extends Mapping
{

    public $valores = array();
    public $listaAtributos = array();
    public $clavesPrimarias = array();
    public $estructura = array();

    /**
     * EDIT()
     * This method is used to edit data from the database.
     * It constructs an SQL UPDATE query based on the provided values and attributes.
     * @return void
     */
    function EDIT()
    {
        return $this->mapping_EDIT();
    }
    /**
     * ADD()
     * This method is used to add new data to the database.
     * It constructs an SQL INSERT query based on the provided values and attributes.
     * @return void
     */
    function ADD()
    {
        return $this->mapping_ADD();
    }
   
    /**
     * SEARCH()
     * This method is used to search for data in the database.
     * It constructs an SQL SELECT query based on the provided attributes and values.
     * @return void
     */
    function SEARCH()
    {

        return $this->mapping_SEARCH();
    }
    /**
     * SEARCH_BY()
     * This method is used to search for data in the database based on specific values.
     * It constructs an SQL SELECT query with a WHERE clause based on the provided attributes and values.
     * @return void
     */
    function SEARCH_BY()
    {

        return $this->mapping_SEARCH_BY();
    }
    /**
     * DELETE()
     * This method is used to delete data from the database.
     * It constructs an SQL DELETE query based on the primary keys of the record to be deleted.
     * @return void
     */
    function DELETE()
    {

        return $this->mapping_DELETE();
    }
}
