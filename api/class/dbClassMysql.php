<?php
class dbClassMysql
{
    public function __construct()
    {        
        //$db_host = getenv('MYSQL_HOST', true) ?: getenv('MYSQL_HOST');
        $db_host = '127.0.0.1';
        //$db_user = getenv('MYSQL_USER', true) ?: getenv('MYSQL_USER');
        $db_user = 'root';
        //$db_pwd  = getenv('MYSQL_PASSWORD', true) ?: getenv('MYSQL_PASSWORD');
        $db_pwd  = '';
        //$db_name = getenv('MYSQL_DATABASE', true) ?: getenv('MYSQL_DATABASE'); 
        $db_name = 'crm_avalia'; 

        //echo $db_host. ' - '.$db_user. ' - '.$db_pwd. ' - '.$db_name;
        $this->conexion = new mysqli($db_host, $db_user, $db_pwd , $db_name);
        if ($this->conexion->connect_error) {
            die('Connect Error(' . $this->conexion->connect_errno . ') ' . $this->conexion->connect_error);
        }
        $this->conexion->set_charset("utf8");
    }

    /** CONSULTA */
    public function db_query($strQuery)
    {
        $resultado = $this->conexion->query($strQuery);
        if (!$resultado) {
            print "<pre>Ha ocurrido un error intente nuevamente:  <br> Query:  <br>" . $strQuery . " <br> Error: <br>" . $this->conexion->error . "</pre>";
            return null;
        } else {
            return $resultado;
        }
    }

    /** RETORNA UN ARRAY ASOCIATIVO CORRESPONDIENTE A LA FILA OBTENIDA O NULL SI NO HUBIRA MAS FILAS */
    public function db_fetch_assoc($qTMP)
    {
        if ($qTMP != null) {
            return $qTMP->fetch_assoc();
        } else {
            return null;
        }
    }

    /** DEVUELVE LA FILA ACTUAL DE UN CONJUNTO DE RESULTADOS COMO UN OBJETO */
    public function db_fetch_object($qTMP)
    {
        if ($qTMP != null) {
            return $qTMP->fetch_object();
        } else {
            return null;
        }
    }

    /** LIBERA LA MEMORIA DEL RESULTADO */
    public function db_free_result($qTMP)
    {
        if ($qTMP != null) {
            return $qTMP->free();
        }
    }

    /** CIERRA LA CONEXION */
    public function db_close()
    {
        return $this->conexion->close();
    }

    /** OBTIENE LA ULTIMA IDENTIFICACION DE LA INSERCION QUE SE HA GENERADO */
    public function db_last_id()
    {
        $strQuery = "SELECT LAST_INSERT_ID() id";
        $qTMP = $this->db_fetch_assoc($this->db_query($strQuery));
        return intval($qTMP["id"]);
    }

    /** OBTIENE EL NUMERO DE FILAS DE UN RESULTADO */
    public function db_num_rows($qTMP)
    {
        return mysqli_num_rows($qTMP);
    }
}
