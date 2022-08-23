<?php include("./header.php");


//importing classes and creating objects 
include "contactsClass.php";
$contactObj = new Contacts();

include "clients.php";
$clientObj = new Clients();


// gets the values from the multi-select dropdown and adds those clients to the specified contact
$code = $_GET["id"];
if ($_POST['submit']) {
    if (isset($_POST["contacts"])) {
        $contacts = $_POST['contacts'];
        $contactList = [];
        foreach ($_POST['contacts'] as $contact) {

            array_push($contactList, $contact);
        }

        foreach ($contactList as $contact) {
            $link = $clientObj->checkLinks($contact, $code);
            if ($link == null) {
                $linkContacts = $clientObj->linkContacts($code,  $contact);
            } else {
                $error =  "One of your Contacts are already linked";
            }
        }
    } else {
        $error =  "Select at least one Contact";
    }
}

?>


<div class="card card-style ">
    <div class="row">
        <form class="col s12" action="linkContacts.php?id=<?php echo $code ?>" id="add-client" method="POST">
            <div class="row">

                <h4 class="grey-text center link-title">Link Contacts</h4>
                <div class="row grey-text ">
                    <select class="col s12 " name="contacts[]" multiple>
                        <option value="" disabled selected>Link Contacts</option>

                        <?php $contacts = $contactObj->displayContacts();
                        foreach ($contacts as $contact) { ?>
                            <option value="<?php echo $contact["Contact_Id"] ?>"><?php echo  $contact["Surname"] . " " . $contact["Name"] ?></option>

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