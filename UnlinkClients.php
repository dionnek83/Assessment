<?php include("./header.php");

//importing class and creating object
include "clients.php";
$clientObj = new Clients();

//sets the values passed to pass it into the unlink clients function
$email = $_GET["email"];
$code = $_GET["code"];
$clientObj->unlinkClients($code, $email);

?>
<div class="card card-style ">
    <div class="row">
        <form class="col s12" action="" method="POST">
            <div class="row">

                <h4 class="grey-text center link-title">Successfully Unlinked Client</h4>

            </div>

        </form>
    </div>
</div>


<?php include("./footer.php");
?>