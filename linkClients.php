<?php include("./header.php");
//importing classes and creating objects 
include "contactsClass.php";
$contactObj = new Contacts();

include "clients.php";
$clientObj = new Clients();


$id = $_GET["id"];

// gets the values from the multi-select dropdown and adds those clients to the specified contact
if ($_POST['submit']) {
    if (isset($_POST["clients"])) {
        $clients = $_POST['clients'];
        $clientsList = [];
        foreach ($_POST['clients'] as $client) {
            array_push($clientsList, $client);
        }

        foreach ($clientsList as $client) {

            $link = $clientObj->checkLinks($id, $client);
            if ($link == null) {
                $linkContacts = $contactObj->linkClients($client,  $id);
            } else {
                $error =  "One of your Clients are already linked";
            }
        }
    } else {
        $error =  "Select at least one Client";
    }
}


?>
<div class="card card-style ">
    <div class="row">
        <form class="col s12" action="" id="add-client" method="POST">
            <div class="row">

                <h4 class="grey-text center link-title">Link Clients</h4>
                <div class="row grey-text ">
                    <select class="col s12 " name="clients[]" multiple>
                        <option value="" disabled selected>Link Clients</option>
                        <?php $clients = $clientObj->displayClients();
                        foreach ($clients as $client) { ?>
                            <option value="<?php echo $client["Client_Code"] ?>"><?php echo $client["Name"]  ?></option>

                        <?php  } ?>

                    </select>
                    <div class="red-text"><?php echo $error ?></div>
                </div>

            </div>
            <button class="btn red lighten-3" name="submit" type="submit" value="submit">Submit</button>
        </form>
    </div>
</div>


<?php include("./footer.php");
?>