<?php
class Contacts
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

    public function addContact()
    {

        $name =  $this->con->real_escape_string($_POST['name']);
        $surname =  $this->con->real_escape_string($_POST['surname']);
        $email =  $this->con->real_escape_string($_POST['email']);
        $add_contact_query = "INSERT INTO Contacts(Email,Surname,Name) VALUES ('$email','$surname','$name')";
        $result = $this->con->query($add_contact_query);
        if ($result) {
            echo "Successfully added new contact";
        } else {
            echo mysqli_error($this->con);
        }
    }
    //Checks if a Contact with the same credentitals exists
    public function check()
    {
        $email =  htmlspecialchars($_POST['email']);
        $contact_check_query = "SELECT * FROM Contacts WHERE Email = '$email' LIMIT 1";
        $result = $this->con->query($contact_check_query);
        $contact = mysqli_fetch_assoc($result);
        if ($contact) {
            return "Contact already exists";
        } else {
            return null;
        }
    }


    // Gets the total number of Clients linked to the specified Contact
    public function getTotalLinks($id)
    {
        $total_query = "SELECT COUNT(Contact_id) as `total` from Assessment.Clients_Contacts WHERE `Contact_Id` = '$id'";
        $client = $this->con->query($total_query);

        $row = $client->fetch_assoc();
        if ($row['total'] >= 1) {
            return $row['total'];
        } else {
            return 0;
        }
    }
    public function displayContacts()
    {
        $get_clients_query = "SELECT * FROM Contacts ORDER BY Name ASC";
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

    //Gets all the Contacts linked to the specified Client using their Client Code 
    public function getContactByCode($code)
    {
        $contact_query = "SELECT Contacts.Contact_Id,Contacts.Name,Surname,Email from Clients_Contacts join
        Contacts WHERE Contacts.Contact_Id = Clients_Contacts.Contact_Id and Client_Code = '$code' ";
        $contact = $this->con->query($contact_query);
        if ($contact->num_rows == 0) {
            return null;
        } else {
            $data = array();
            while ($row = $contact->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    // links the selected Clients to the specified Contact 
    public function linkClients($code, $id)
    {
        $link_query = "INSERT INTO Clients_Contacts(Client_Code,Contact_Id) 
        VALUES (
            (SELECT Client_Code from Clients where Client_Code = '$code'),
            (SELECT Contact_Id from Contacts where Contact_Id = '$id'));";
        $link_result = $this->con->query($link_query);
        if ($link_result) {
            echo "Successfully linked to client(s)";
        } else {
            echo mysqli_error($this->con);
        }
    }

    // unlinks the selected Contacts from the specified Client 
    public function unlinkContacts($code, $id)
    {
        $unlink_query = "DELETE FROM Clients_Contacts WHERE Client_Code = '$code' AND Contact_id = '$id'";
        $unlink_result = $this->con->query($unlink_query);
        if ($unlink_result) {
            return null;
        } else {
            echo mysqli_error($this->con);
        }
    }
}
