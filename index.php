<?php
include("./templates/header.php");
//importing classes and creating objects 
include "./classes/clients.php";
$clientObj = new Clients();

include "./classes/contactsClass.php";
$contactObj = new Contacts();



//creates the new client
if ($_POST['add_client_submit']) {
    $errors = array('name' => '');
    $client = $clientObj->check($_POST['name']);
    if ($client != null) {
        $errors['name'] = "Client Creation failed, Client already exists";
    } else {
        $clientObj->addClient($_POST);
    }
}


?>



<!DOCTYPE html>
<html>
<div class="popup hide" id="popup">
    <div class="card card-style">
        <div class="row">
            <form class="col s12" action="index.php" id="add-client" onChange="getClientData" method="POST">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" value="<?php echo $name ?>" required class="validate ">
                        <label for="name">Name</label>
                    </div>
                    <div class="error red-text"><?php echo $errors['name'] ?></div>
                </div>

                <button class="btn red lighten-3" name="add_client_submit" type="submit" value="submit">Submit</button>

            </form>
        </div>
    </div>

</div>







<div class="row">
    <div id="client" class="container client col s6 ">

        <h3 class="center grey-text ">View Clients</h3>
        <div>
            <?php $clients = $clientObj->displayClients();
            if ($clients == null) { ?>

                <h5 class=""> No Client(s) found</h5>
            <?php } else { ?>

                <table id="clients_table" class="striped bordered ">
                    <thead>
                        <tr>
                            <th class=" name">Name</th>
                            <th class="client_code">Client code</th>
                            <th class="center">No. of linked Contacts</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody class="">

                        <?php
                        foreach ($clients as $client) {
                            $links = $clientObj->getTotalLinks($client["Client_Code"]);
                        ?>

                            <tr onclick='getUser()' id="<?php echo $client["Client_Code"] ?>" style='cursor:pointer'>
                                <td><?php echo $client["Name"] ?></td>
                                <td><?php echo $client["Client_Code"] ?></td>
                                <td class="center"><?php echo $links ?></td>
                                <td><button class="btn red lighten-3 ">
                                        <a href="linkContacts.php?id=<?php echo $client["Client_Code"] ?>" style="color:white">Link</a>
                                    </button></td>



                            </tr>
                        <?php } ?>


                    </tbody>
                </table>
            <?php } ?>

            <button onclick="createClient()" class="btn red lighten-3" style="margin-top: 2rem;">Add new client</button>
            <div class="error red-text"><?php echo $errors['name'] ?></div>
        </div>

    </div>
    <div class="col s6" style="margin-top:3.5rem; ">

        <div class="col s12">
            <ul class="tabs ">
                <li class="tab col s3"><a class="active" href="#general">General</a></li>
                <li class="tab col s3"><a href="#contact">Contact(s)</a></li>

            </ul>
        </div>
        <div id="general" class="col s12">

            <div class="row">
                <form class="col s12" id="client-form" action="index.php" method="POST">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="name" placeholder="" name="name" type="text" value="" class="validate input">
                            <label for="name">Name</label>

                        </div>

                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="client_code" readonly placeholder="" value="" name="client_code" type="text" class="validate input grey-text">
                            <label for="client_code">Client Code</label>
                        </div>

                    </div>



                    <button class="btn red lighten-3 " name="display_contacts" type="submit" value="display_contacts">
                        View Contacts
                    </button>

                </form>
            </div>


        </div>
        <div id="contact" class="col s12">
            <?php $contacts = $contactObj->getContactByCode($_POST['client_code']);
            if ($contacts == null) { ?>
                <p class="no-contact"> No Contact(s) found</p>
            <?php } else { ?>

                <table class="striped bordered ">
                    <thead>

                        <tr>
                            <th>Full Name</th>
                            <th>Email Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($contacts as $contact) { ?>

                            <tr>
                                <td><?php echo $contact["Name"] . " " . $contact["Surname"]; ?></td>
                                <td><?php echo $contact["Email"] ?></td>
                                <td> <a href="UnlinkContacts.php?id=<?php echo $contact["Contact_Id"] ?>&&code=<?php echo $_POST['client_code'] ?>">Un-Link</a></td>

                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>

<?php include("./templates/footer.php") ?>