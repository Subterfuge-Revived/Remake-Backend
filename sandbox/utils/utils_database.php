<?php


class utils_database
{

    private $con;

    private $bind_res = [];
    private $bind_req = [];

    private $error_if_num_row_zero;
    private $error_if_num_row_zero_msg;

    private $error_if_num_row_not_zero;
    private $error_if_num_row_not_zero_msg;

    private $num_rows;
    private $insert_id;

    public function __construct(mysqli $con) {

        $this->con = $con;
        $this->error_if_num_row_zero = false;

        return $this;
    }

    public function getNumRows()
    {
        return $this->num_rows;
    }

    public function getInsertId()
    {
        return $this->insert_id;
    }

    public function bind_res(&...$bind_res) {

        $this->bind_res = $bind_res;

        return $this;
    }

    public function bind_req(&...$bind_req) {

        $this->bind_req = $bind_req;

        return $this;
    }

    public function error_num_row_zero($error_message) {

        $this->error_if_num_row_zero = true;
        $this->error_if_num_row_zero_msg = $error_message;

        return $this;
    }

    public function error_num_row_not_zero($error_message) {

        $this->error_if_num_row_not_zero = true;
        $this->error_if_num_row_not_zero_msg = $error_message;

        return $this;
    }

    private function flush() {

        $this->bind_res = null;
        $this->bind_req = null;
        $this->error_if_num_row_zero = null;
        $this->error_if_num_row_zero_msg = null;
        $this->error_if_num_row_not_zero = null;
        $this->error_if_num_row_not_zero_msg = null;
    }

    public function exec_db($stmt) {

        $var_types = "";

        $occ = substr_count($stmt, "?");

        if( $occ < count($this->bind_req) ) {
            $this->bind_req = array_slice($this->bind_req, 0, $occ);
        }

        foreach ( $this->bind_req as $var ) {

            if(is_int($var)) {

                $var_types = $var_types . "i";
            } elseif (is_double($var)) {

                $var_types = $var_types . "d";
            } else {

                $var_types = $var_types . "s";
            }
        }

        $statement = $this->con->prepare($stmt);

        // Prepare statement can return false. If false, need to return early.
        // IF false, this usually means the query is incorrect and cannot bind. Look at the SQL to ensure it is correct.
        if(!$statement) {
            throw new \Exception("Malformed SQL Exception.");
        }

        call_user_func_array(array($statement, 'bind_param'), array_merge(array($var_types), $this->bind_req));

        $statement->execute();

        $p = $statement->get_result();

        if(!$p) {
            // No results from database.
            // Likely an update or insert.
            return;
        }
        $this->num_rows = $p->num_rows;
        $this->insert_id = $this->con->insert_id;

        if( $this->error_if_num_row_zero && $this->num_rows == 0 ) {

            throw new Exception($this->error_if_num_row_zero_msg);
        } else if( $this->error_if_num_row_not_zero && $this->num_rows > 0 ) {

            throw new Exception($this->error_if_num_row_not_zero_msg);
        } else if( $this->num_rows > 1 ) {

            for($i = 0; $i < count($this->bind_res); $i++) {

                $this->bind_res[$i] = [];
            }
        }

        while( !($p === false) && $row = $p->fetch_row() ) {  // !($p === false) because fetching will fail after INSERT query

            for( $column = 0; $column < count($row); $column++ ) {

                ($this->num_rows > 1) ? array_push($this->bind_res[$column], $row[$column]) : $this->bind_res[$column] = $row[$column];
            }
        }

        $this->flush();

        return $this;
    }

    public static function new_connection() {

        return self::connect("sandbox");
    }

    public static function new_connection_events() {

        return self::connect("events_ongoing_rooms");
    }

    /**
     * Connect to the database.
     *
     * @return mysqli
     */
    private static function connect(): mysqli {
        $mysqli = new mysqli(
            'db:3306',
            getenv('MYSQL_USER'),
            getenv('MYSQL_PASSWORD'),
            getenv('MYSQL_DATABASE')
        );
        if (!$mysqli) {
            echo "{ 'success': false, 'message': 'Unable to connect to mysql' }";
            die;
        }
        return $mysqli;
    }
}
