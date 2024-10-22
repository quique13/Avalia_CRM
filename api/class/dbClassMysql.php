<?php
class dbClassMysql
{
    private $_CONN;
    public function __construct()
    {
        try {
            $_DIR = explode("class", __DIR__)[0];
            $path = $_DIR . '.env';

            if (file_exists($path)) {
                $json = file_get_contents($path);
                $_ENV = json_decode(trim($json), true);

                $host = $_ENV['host'] ?? 'localhost';
                $user = $_ENV['user'] ?? '';
                $pass = $_ENV['password'] ?? '';
                $db = $_ENV['db'] ?? 'avalia';

                // exit($host . ' - ' . $user . ' - ' . $pass . ' - ' . $db . '-' . $port . "  env: " . $_ENV['host']);

                $this->_CONN = new mysqli($host, $user, $pass, $db);
                if ($this->_CONN->connect_error) {
                    die('Connect Error(' . $this->_CONN->connect_errno . ') ' . $this->_CONN->connect_error);
                }
                $this->_CONN->set_charset("utf8");

            } else {
                throw new Exception("No existe el fichero: " . $path);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            print "¡Error DB!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /** CONSULTA */
    public function db_query($strQuery)
    {
        $resultado = $this->_CONN->query($strQuery);
        if (!$resultado) {
            print "<pre>Ha ocurrido un error intente nuevamente:  <br> Query:  <br>" . $strQuery . " <br> Error: <br>" . $this->_CONN->error . "</pre>";
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

    /** CIERRA LA _CONN */
    public function db_close()
    {
        return $this->_CONN->close();
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
