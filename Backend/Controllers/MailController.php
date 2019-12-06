<?php
class MailController extends UserController{
	public function checkConversation($user_one, $user_two){
		$query = "SELECT conversationId FROM Conversation WHERE (one_user_id='$user_one' AND second_user_id='$user_two') 
												   OR (one_user_id='$user_two' AND second_user_id='$user_one')";
		return parent::getResultSetAsArray($query);
	}

	public function createConversation($user_one, $user_two){
		$c_id_array = $this->checkConversation($user_one, $user_two);
		if(count($c_id_array) === 0){
			$query = "INSERT INTO conversation(one_user_id, second_user_id) VALUES ('$user_one','$user_two')";
			parent::executeSqlQuery($query);
		}
	}

	public function getUsernameForConvo($rsArrayWithIds, $user_id){
		$user_id_one = $rsArrayWithIds[0]['one_user_id'];
		$user_id_two = $rsArrayWithIds[0]['second_user_id'];
		$userId = null;
		if ($user_id === $user_id_one) {
			$userId = $user_id_two;
		}else{
			$userId = $user_id_one;
		}
        $query = "SELECT username FROM User WHERE userId = '$userId'";
        $username = parent::getResultSetAsArray($query);    
		if (count($username) > 0) {
			return $username[0]['username'];
        }
        return "Unknown";
	}

	public function getUsernamesForConvo($rsArrayWithIds){
		$user_id_one = $rsArrayWithIds[0]['user_one'];
		$user_id_two = $rsArrayWithIds[0]['user_two'];

		$userName_one = parent::getUsername($user_id_one);
		$userName_two = parent::getUsername($user_id_two);

		$arrayWithNames = array($userName_one, $userName_two);
		return $arrayWithNames;
	}

	public function getUserIdsForConvo($conversationId){
		$query = "SELECT one_user_id, second_user_id FROM Conversation WHERE conversationId = '$conversationId'";
		return parent::getResultSetAsArray($query);
	}

    public function deleteConversation($conversationId){
		self::deleteAllMessages($conversationId);
		$query = "DELETE FROM Conversation WHERE conversationId = '$conversationId'";
		parent::executeSqlQuery($query);
	}

	public function deleteMessage($id){
		$query = "DELETE FROM Message WHERE messageId = '$id'";
		parent::executeSqlQuery($query);
	}

	public function sendMessage($conversation_id, $message, $user_id){
		$description = parent::getEscaped($message);
		$query = "INSERT INTO Message(conversationId, sender_user_id, content) VALUES('$conversation_id', '$user_id', '$description')";
		parent::executeSqlQuery($query);
	}

	public function displayMessages($conversation_id){
		$query = "SELECT * FROM Message WHERE conversationId = '$conversation_id'";
		return parent::getResultSetAsArray($query);
	}

	public function displayConversations($user_id){
        $query = "SELECT * FROM Conversation WHERE one_user_id = '$user_id' OR second_user_id = '$user_id'";
        return parent::getResultSetAsArray($query);
	}

	public function deleteAllMessages($conversation_id){
		$query = "DELETE FROM Message WHERE conversationId = '$conversation_id'";
		parent::executeSqlQuery($query);
	}

	public function displayAllConversations(){
		$query = "SELECT * FROM Conversation";
		return parent::getResultSetAsArray($query);
	}

	public function getSender($conversation_id){
		$query = "SELECT sender_user_id FROM WHERE conversationId = '$conversation_id'";
		$senderId = parent::getResultSetAsArray($query);
		for ($row=0; $row < count($senderId); $row++) { 
			 return $senderId[$row]['sender_user_id'];
		}
	}
}
 ?>