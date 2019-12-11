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
     
    }

    public function banUser($user_id, $message, $banned_by_id){
        $rs = parent::getDeactivatedUser($user_id);
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