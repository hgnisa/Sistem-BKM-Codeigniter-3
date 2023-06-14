<?php
    session_start();
    include '../model/class-user.php';
    $user = new user(); 

    $data = $user->show_user_detail($_GET['id']);
?>
<div class="col-lg-12 text-center">
    <img class="img-fluid" style="width: 15rem; height: 15rem; padding: 10px 10px 10px 10px; border-radius: 50%" src="../../img/profile/<?php print $data['user_profile'];?>">
</div>


