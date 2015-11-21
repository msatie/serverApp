<?php

use \Jacwright\RestServer\RestException;

class TestController
{
    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /users
     */
    public function getUsers()
    {
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "estudiantes";

        // Create connection
        $array = array();

        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "SELECT id, nombre, correo, telefono FROM estudiante";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

                $res = array("id" => $row["id"], "nombre" => $row["nombre"], "telefono" => $row["telefono"], "correo" => $row["correo"]);
                array_push($array, $res); 
                //echo "id: " . $row["id"]. " - Name: " . $row["nombre"]. " " . $row["correo"]. "<br>";
            }
        }
        $conn->close();

        return $array;
    }

    /**
     * Gets the user by id or current user
     *
     * @url GET /users/$id
     */
    public function getUser($id = null)
    {
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "estudiantes";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = sprintf("SELECT id, nombre, correo, telefono FROM estudiante WHERE id='%s'",
        mysql_real_escape_string($id));

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

                $res =  array("id" => $id, "nombre" => $row["nombre"], "telefono" => $row["telefono"], "correo" => $row["correo"]); 
                //echo "id: " . $row["id"]. " - Name: " . $row["nombre"]. " " . $row["correo"]. "<br>";
            }
        } else {
            $res =  array();
        }
        $conn->close();

        return $res;
        // serializes object into JSON
    }

    /**
     * Gets the user by id or current user
     *
     * @url GET /users/responsables/$id
     */
    public function getResponsablesByUser($id = null)
    {
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "estudiantes";

        // Create connection
        $array = array();

        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = sprintf("SELECT responsables.* FROM estudiante INNER JOIN responsables ON estudiante.id = responsables.e_id WHERE id='%s'",
        mysql_real_escape_string($id));
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

                $res = array("nombre_responsable" => $row["nombre"], "correo" => $row["correo"], "telefono" => $row["telefono"]);
                array_push($array, $res); 
                //echo "id: " . $row["id"]. " - Name: " . $row["nombre"]. " " . $row["correo"]. "<br>";
            }
        }
        $conn->close();

        return $array;// serializes object into JSON
    }

    /**
     * Throws an error
     * 
     * @url GET /error
     */
    public function throwError() {
        throw new RestException(401, "Empty password not allowed");
    }
}