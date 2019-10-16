<?php


class utils_database
{

    private $con;

    private $bind_res = [];
    private $bind_req = [];

    public $num_rows;

    public function __construct(mysqli &$con) {

        $this->con = $con;

        return $this;
    }

    public function bind_res(&...$bind_res) {

        $this->bind_res = $bind_res;

        return $this;
    }

    public function bind_req(&...$bind_req) {

        $this->bind_req = $bind_req;

        return $this;
    }

    public function exec_db($stmt) {

        try {
            $var_types = "";

            foreach ($this->bind_req as $var) {

                if(is_int($var)) {

                    $var_types = $var_types . "i";
                } elseif (is_double($var)) {

                    $var_types = $var_types . "d";
                } else {

                    $var_types = $var_types . "s";
                }
            }

            $run = $this->con->prepare($stmt);

            call_user_func_array(array($run, "bind_param"), array_merge(array($var_types), $this->bind_req));

            $run->execute();
            $p = $run->get_result();

            $this->num_rows = $p->num_rows;

            if($this->num_rows > 1) {

                for($i = 0; $i < count($this->bind_res); $i++) {

                    $this->bind_res[$i] = [];
                }
            }

            while($row = $p->fetch_row()) {

                for($column = 0; $column < count($row); $column++) {

                    ($this->num_rows > 1) ? array_push($this->bind_res[$column], $row[$column]) : $this->bind_res[$column] = $row[$column];
                }
            }
        } catch (\Exception $e) {

            echo $e->getMessage();
        } finally {

            return $this;
        }
    }

    public static function new_connection() {
        $db_servername = "localhost";
        $db_username = "";
        $db_password = "";
        $db_database = "sandbox";
        return new mysqli($db_servername, $db_username, $db_password, $db_database);
    }

    public static function new_connection_events() {
        $db_servername = "localhost";
        $db_username = "";
        $db_password = "";
        $db_database = "events_ongoing_rooms";
        return new mysqli($db_servername, $db_username, $db_password, $db_database);
    }
}