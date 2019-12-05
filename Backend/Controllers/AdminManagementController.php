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

    public function getUserActions($user_id){
        return parent::getResultSetAsArray("SELECT actionPerformed, actionPerformedAt FROM UserActionLog WHERE user_id = '$user_id'");
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

    public function banUser($user_id, $message, $banned_by_id){
        $rs = parent::checkBannedUsers($user_id);
        $temp = parent::getResultSetAsArray("SELECT adminId FROM Administrator WHERE user_id = '$banned_by_id'");

        $tempUserToBanId = parent::getResultSetAsArray("SELECT adminId FROM Administrator WHERE user_id = '$user_id'");

        if (count($tempUserToBanId) > 0){
            echo "Error : You cannot deactivate the account of an adminstrator";
            return;
        }

        $adminId = 1;
        if (count($temp) > 0){
            $adminId = $temp[0]['adminId'];
        }
        if(count($rs) == 0){
            $description = parent::getEscaped($message);
            $query = "INSERT INTO BannedUsers(user_id, description, imposedBy_admin_id) VALUES('$user_id', '$description', '$adminId')";
            parent::executeSqlQuery($query);
        }else{
            echo "Error : This user is already deactivated from this site";
        }
    }

    public function unbanUser($user_id){
        $query = "DELETE FROM BannedUsers WHERE user_id = '$user_id'";
        return parent::executeSqlQuery($query);
    }
}
?>