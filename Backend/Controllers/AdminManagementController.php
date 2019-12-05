<?php
class AdminManagementController extends UserController{

    public function updateUser($post){
        $user_id 	= $post['hiddenUserId'];
        $first_name = $post['firstname'];
        $last_name  = $post['lastname'];
        $email 		= $post['email'];
        $username 	= $post['username'];
        $age 		= $post['age'];
        $profession = $post['profession'];
        $dob        = $post['dob'];
        $role_id    = $post['role'];
        
        $updateQuery = "UPDATE User SET username = '$username', firstname = '$first_name', lastname = '$last_name',
                                        email = '$email', age = '$age', profession = '$profession', dateOfBirth = '$dob', roleInSCC_Id = '$role_id'
                                WHERE userId = '$user_id'";

        parent::executeSqlQuery($updateQuery);
    }

    //this function is used by the admin to deactivate other users of the site
    public function deleteUser($user_id){
       // $allTheConversations = Conversation::displayConversations($user_id);

        //delete all the conversations with all their messages
      //  for ($row=0; $row < count($allTheConversations); $row++) { 
          //  Conversation::deleteConversation($allTheConversations[$row]['conversationId']);
       // }
        
        //get all the apartments of this user in an array
        // $apartmentArray = Product::displayOwnerProducts($user_id);

        // //delete all the selected apartments
        // for ($row=0; $row < count($apartmentArray); $row++) { 
        //     Product::deleteProduct($apartmentArray[$row]['dwelling_Id'], $user_id);
        // }

        // //delete the user from the database
        // parent::executeSqlQuery("DELETE FROM bannedusers WHERE user_id = '$user_id'");
        // parent::executeSqlQuery("DELETE FROM users WHERE user_id = '$user_id'");
        // header("location: adminPanel.php?action=user");
    }

    //this function is used to ban a user from the site
    public function banUser($user_id, $message){
        // $rs = parent::checkBannedUsers($user_id);
        // if(count($rs) == 0){
        //     $description = Database::getEscaped($message);
        //     $query = "INSERT INTO bannedUsers(banId, user_id, description, from_ ,to_) VALUES(DEFAULT, '$user_id', '$description', NOW() , ADDDATE(NOW(), INTERVAL 31 DAY))";
        //     Database::executeSqlQuery($query);
        //     header("location: adminPanel.php?action=user");
        // }else{
        //     echo "this user is already banned from this site";
        // }
    }

    //this function is used to un-ban the banned user
    public function unBanUser($user_id){
        $query = "DELETE FROM bannedUsers WHERE user_id = '$user_id'";
        DatabaseController::executeSqlQuery($query);
        header("location: adminPanel.php?action=user");
    }
}
?>