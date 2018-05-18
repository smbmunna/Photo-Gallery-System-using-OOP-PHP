<?php  include("includes/init.php");

    if(!$session_object->is_signed_in()){

        redirect("login.php");

    }

?>

<?php 

    if(empty($_GET['id'])){

        redirect("users.php");
    }


    $user = User::find_by_id($_GET['id']);

    if($user){

        $user-> delete_user_and_userimage();

        $message = $session_object->message("User with id {$user->id} has been deleted successfully!");

        redirect("users.php");

    }else{

        redirect("users.php");
    }







 ?>


       