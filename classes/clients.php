<?php
class Clients
{
    private $server = "localhost";
    private $db_user = "admin";
    private $db_password = "test1234";
    private $db = "Assessment";
    public  $con;




    // Database connection 
    public function __construct()
    {

        $this->con = mysqli_connect($this->server, $this->db_user, $this->db_password, $this->db);
        if (mysqli_connect_error()) {
            trigger_error("Failed to connect to Database: " . mysqli_connect_error());
        } else {
            return $this->con;
        }
    }
    //Creates 3 numeric characters for client code 
    public function generateCode($name)
    {
        if (!empty($name)) {
            $last_digit_check = "SELECT Client_Code FROM Clients ORDER BY Time_Stamp DESC LIMIT 1";
            $code = $this->con->query($last_digit_check);
            $code = $code->fetch_assoc();
            $digits =  substr($code["Client_Code"], 3, strlen($code["Client_Code"]));

            //gets the new number
            $new_val = intval($digits) + 1;
            if (empty($new_val)) {
                $new_val = '001';
            }


            $double_digit = '0' . $new_val;
            $single_digit = '00' . $new_val;

            //sets the new client code 
            if (str_word_count($name) == 1 && strlen($name) >= 3) {

                if ($new_val >= 10) {
                    return $client_code =  strtoupper(substr($name, 0, 3)) . $double_digit;
                } else if ($new_val >= 100) {
                    return $client_code =  strtoupper(substr($name, 0, 3)) . $new_val;
                } else {
                    return $client_code =  strtoupper(substr($name, 0, 3)) . $single_digit;
                }
            } else if (str_word_count($name) == 3) {
                $client_code = '';
                foreach (explode(' ', $name) as $char) {
                    $client_code .= strtoupper($char[0]);
                }
                if ($new_val >= 10) {
                    return $client_code = $client_code . $double_digit;
                } else if ($new_val >= 100) {
                    return $client_code = $client_code .  $new_val;
                } else {
                    return $client_code =  $client_code .  $single_digit;
                }
            } else if (str_word_count($name) == 1 && strlen($name) < 3) {
                $str = 'abcdefghijklmnopqrstuvwxyz';
                $randomChar = $str[rand(0, strlen($str) - 1)];
                if ($new_val >= 10) {
                    return $client_code = strtoupper($name)  . strtoupper($randomChar) . $double_digit;
                } else if ($new_val >= 100) {
                    return $client_code = strtoupper($name) . strtoupper($randomChar) . $new_val;
                } else {
                    return $client_code = strtoupper($name) . strtoupper($randomChar) . $single_digit;
                }
            }
        }
    }
    //Checks if a client with the same name already exists
    public function check()
    {
        $name =  htmlspecialchars($_POST['name']);
        $client_check_query = "SELECT * FROM Clients WHERE UPPER(Name) LIKE UPPER('$name') LIMIT 1";
        $result = $this->con->query($client_check_query);
        $client = mysqli_fetch_assoc($result);
        if ($client) {
            return "Client already exists";
        } else {
            return null;
        }
    }
    public function addClient()
    {
        $name =  $this->con->real_escape_string($_POST['name']);

        //gets the client code from the generate code function 
        $client_code = $this->generateCode($name);
        $add_client_query = "INSERT INTO Clients(Client_Code, Name,Time_Stamp) VALUES ('$client_code','$name',DEFAULT)";
        $result = $this->con->query($add_client_query);
        if ($result) {
            echo "Successfully added new client";
        } else {
            echo mysqli_error($this->con);
        }
    }

    // gets the total number of contacts linked to the specified client
    public function getTotalLinks($code)
    {
        $total_query = "SELECT COUNT(Contact_id) as `total` from Assessment.Clients_Contacts WHERE `Client_Code` = '$code'";
        $client = $this->con->query($total_query);

        $row = $client->fetch_assoc();
        if ($row['total'] >= 1) {
            return $row['total'];
        } else {
            return 0;
        }
    }
    public function displayClients()
    {
        $get_clients_query = "SELECT * FROM Clients ORDER BY Name ASC";
        $result = $this->con->query($get_clients_query);
        if ($result) {
            if ($result->num_rows == 0) {
                return null;
            } else {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($data, $row);
                }
                return $data;
            }
        } else {
            echo mysqli_error($this->con);
        }
    }

    //Gets all the clients linked to the specified contact using their email 
    public function getClientByEmail($email)
    {
        $email_query = "SELECT Contact_Id FROM Contacts WHERE Email = '$email' LIMIT 1";
        $result = $this->con->query($email_query);
        $contact_email = mysqli_fetch_assoc($result);
        $contact = $contact_email["Contact_Id"];
        $client_query = "SELECT Clients.Client_Code,Clients.Name from Clients_Contacts join 
        Clients WHERE Clients.Client_Code = Clients_Contacts.Client_Code and Contact_Id = '$contact'";
        $client = $this->con->query($client_query);
        if ($client->num_rows == 0) {
            echo null;
        } else {
            $data = array();
            while ($row = $client->fetch_assoc()) {
                array_push($data, $row);
            }
            return $data;
        }
    }

    //Checks a link already exists
    public function checkLinks($id, $code)
    {

        $check_links_query = "SELECT * FROM Clients_Contacts WHERE Contact_Id = '$id'  and Client_Code = '$code'  LIMIT 1";
        $result = $this->con->query($check_links_query);
        $link = mysqli_fetch_assoc($result);
        if ($link) {
            if ($link['Contact_Id'] === $id && $link['Client_Code'] == $code) {
                return "Contact is already linked";
            } else {
                return null;
            }
        }
    }
    // links the selected Contacts to the specified Client 
    public function linkContacts($code, $id)
    {
        $link_query = "INSERT INTO Clients_Contacts(Client_Code,Contact_Id) 
        VALUES (
            (SELECT Client_Code from Clients where Client_Code = '$code'),
            (SELECT Contact_Id from Contacts where Contact_Id = '$id'));";
        $link_result = $this->con->query($link_query);
        if ($link_result) {
            echo "Successfully linked to Contact(s)";
        } else {
            echo mysqli_error($this->con);
        }
    }

    //unlinks the selected Contacts from the specified Client 
    public function unlinkClients($code, $email)
    {
        $email_query = "SELECT Contact_Id FROM Contacts WHERE Email = '$email' LIMIT 1";
        $result = $this->con->query($email_query);
        $contact_email = mysqli_fetch_assoc($result);
        $contact = $contact_email["Contact_Id"];
        $unlink_query = "DELETE FROM Clients_Contacts WHERE Client_Code = '$code' AND Contact_id = '$contact'";
        $unlink_result = $this->con->query($unlink_query);
        if ($unlink_result) {
            echo "Successfully unlinked to client";
        } else {
            echo mysqli_error($this->con);
        }
    }
}
