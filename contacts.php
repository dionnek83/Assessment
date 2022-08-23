<?php

include("./header.php");

//importing classes and creating objects 
include "contactsClass.php";
$contactObj = new Contacts();
include "clients.php";
$clientObj = new Clients();



$name = $surname = $email = '';

//creates a new contact and checks if a contact with that email already exists
if (isset($_POST['submit'])) {
    $errors = array('name' => '', 'surname' => '', 'email' => '');
    $name =  htmlspecialchars($_POST['name']);
    $surname =  htmlspecialchars($_POST['surname']);
    $email =  htmlspecialchars($_POST['email']);

    $contact = $contactObj->check();
    if ($contact != null) {
        $errors['email'] = "Contact Creation failed, Email address already exists";
    } else if ($contact == null) {
        $contactObj->addContact($_POST);
    }
}

?>


<div class="popup hide" id="popup">
    <div class="card card-style">
        <div class="row">
            <form class="col s12" id="form" action="contacts.php" method="POST">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" value="<?php echo $name ?>" required class="validate ">
                        <label for="name">Name</label>

                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="surname" name="surname" type="text" value="<?php echo $surname ?>" required class="validate ">
                        <label for="surname">Surname</label>

                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="email" name="email" type="email" value="<?php echo $email ?>" required class="validate ">
                        <label for="email">Email</label>

                    </div>
                </div>

                <button class="btn red lighten-3" name="submit" type="submit" value="submit">Submit</button>

            </form>
        </div>
    </div>

</div>
<div class="row">
    <div id="client" class="container client col s6  ">
        <h3 class="center grey-text ">View Contacts</h3>
        <div>
            <?php $contacts = $contactObj->displayContacts();
            if ($contacts == null) { ?>

                <h5 class=""> No Client(s) found</h5>
            <?php } else { ?>
                <table class="striped bordered">
                    <thead>

                        <tr>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>Email Address</th>
                            <th class="center">No. of linked Clients</th>
                            <th class="center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        foreach ($contacts as $contact) {

                            $links = $contactObj->getTotalLinks($contact["Contact_Id"]);
                        ?>

                            <tr onclick='getContact()' id="<?php echo $client["Contact_Id"] ?>" style='cursor:pointer'>
                                <td><?php echo $contact["Name"] ?></td>
                                <td><?php echo $contact["Surname"] ?></td>
                                <td><?php echo $contact["Email"] ?></td>
                                <td class='center'> <?php echo $links ?></td>
                                <td><button class="btn red lighten-3 ">
                                        <a href="linkClients.php?id=<?php echo $contact["Contact_Id"] ?>" style="color:white">Link</a>
                                    </button></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            <?php }  ?>
        </div>
        <button onclick="createClient()" class="btn red lighten-3" style="margin-top: 2rem;">Add new Contact </button>
        <div class=" red-text"><?php echo $errors['email'] ?></div>

    </div>
    <div class="col s6 " style="margin-top:3.5rem; ">
        <div class="col s12">
            <ul class="tabs ">
                <li class="tab col s3"><a class="active" href="#general">General</a></li>
                <li class="tab col s3"><a href="#contact">Clients(s)</a></li>

            </ul>
        </div>
        <div id="general" class="col s12">

            <div class="row">
                <form class="col s12" id="contact-form" action="contacts.php" method="POST">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="name" placeholder="" name="name" type="text" class="validate input2">
                            <label for="name">Name</label>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="surname" placeholder="" name="surname" type="text" class="validate input2">
                            <label for="surname">Surname</label>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="email" name="email" placeholder="" type="email" class="validate input2">
                            <label for="email">Email</label>

                        </div>
                    </div>

                    <button class="btn red lighten-3" name="viewContact" type="submit" value="viewContact">View Cients</button>

                </form>
            </div>


        </div>
        <div id="contact" class="col s12">

            <?php $clients = $clientObj->getClientByEmail($_POST['email']);
            if ($clients == null) { ?>

                <p class="no-client"> No Client(s) found</p>
            <?php } else { ?>

                <table class="striped bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Client code</th>

                        </tr>
                    </thead>
                    <tbody>



                        <?php
                        foreach ($clients as $client) { ?>

                            <tr>
                                <td><?php echo $client["Name"]  ?></td>
                                <td><?php echo $client["Client_Code"] ?></td>
                                <td> <a href="UnlinkClients.php?email=<?php echo $_POST['email'] ?>&&code=<?php echo $client["Client_Code"] ?>">Un-Link</a></td>
                            </tr>
                        <?php } ?>



                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>

<?php include("./footer.php") ?>