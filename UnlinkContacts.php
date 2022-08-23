<?php include("./header.php");

//importing class and creating object
include "contactsClass.php";
$contactObj = new Contacts();

//sets the values passed to pass it into the unlink contacts function
$id = $_GET["id"];
$code = $_GET["code"];
$contactObj->unlinkContacts($code, $id);


?>
<div class="card card-style ">
    <div class="row">
        <form class="col s12" action="" method="POST">
            <div class="row">

                <h4 class="grey-text center link-title">Successfully Unlinked Contact</h4>

            </div>

        </form>
    </div>
</div>


<?php include("./footer.php");
?>