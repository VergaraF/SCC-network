<?php
class ContentController{
    private $db_controller;
    private static $instance = null;

    public function __construct() {
        $this->db_controller = new DatabaseController();
    }
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new ContentController();
        }

        return self::$instance;
    }

    public function getEventsWhereUserIsManager($userId){
        $query = "SELECT ev_in.event_instanceId, ev_in.event_id, ev_in.eventStatus_id, ev_in.page_id, ev_in.lifetime, ev_ma.manager_id  
                 FROM event_instance AS ev_in 
                 INNER JOIN event_manager AS ev_ma ON ev_in.event_instanceId = ev_ma.event_instance_id
                 INNER JOIN Manager AS ma ON ev_ma.manager_id = ma.managerId
                 WHERE ma.user_id = '$userId'";

        $resultAsArray = $this->db_controller->getResultSetAsArray($query);
        if (count($resultAsArray) > 0 && !isset($_SESSION["PARTICIPANT_IDENTIFIER"])){
            $_SESSION["MANAGER"] = $resultAsArray[0]["manager_id"];
        }
        return $resultAsArray;
    }

    public function getEventsWhereUserIsParticipant($userId){
        $query = "SELECT ev_in.event_instanceId, ev_in.event_id, ev_in.eventStatus_id, ev_in.page_id, ev_in.lifetime, ev_pa.event_participant_id  
                  FROM event_instance AS ev_in 
                  INNER JOIN event_participants AS ev_pa ON ev_in.event_instanceId = ev_pa.event_instance_id
                  INNER JOIN Participant AS pa ON ev_pa.event_participant_id = pa.participantId
                  WHERE pa.user_id = '$userId'";

        $resultAsArray = $this->db_controller->getResultSetAsArray($query);
        if (count($resultAsArray) > 0 && !isset($_SESSION["PARTICIPANT_IDENTIFIER"])){
            $_SESSION["PARTICIPANT_IDENTIFIER"] = $resultAsArray[0]["event_participant_id"];
        }

        return $resultAsArray;
    }

    public function getNewContentForUser($userId){
        $previousTimestampQuery = "SELECT * FROM Newsfeed WHERE userId = '$userId'";
        $resultAsArray = $this->db_controller->getResultSetAsArray($previousTimestampQuery);
        if (count($resultAsArray) == 0){
            return Array();
        }
        $newsFeedId = $resultAsArray[0]['newsFeedId'];
        $prevTimestamp = $resultAsArray[0]['checkedAt'];

        $participantId = $_SESSION["PARTICIPANT_IDENTIFIER"];

        if ($prevTimestamp == null){
            $selectAllContentQueryUsingTimeStamp =  "SELECT ev_in_co.event_instance_id, ev_in_co.event_instance_content_author_participant_id, co.contentType, co.value, co.postedAt
                                                 FROM event_instance_content AS ev_in_co, event_participants as ev_pa, content as co
                                                 WHERE ev_in_co.event_instance_contentId = co.contentId AND ev_pa.event_instance_id = ev_in_co.event_instance_id 
                                                 AND ev_pa.event_participant_id = '$participantId' AND co.postedAt < NOW();";
        }else{
            $selectAllContentQueryUsingTimeStamp =  "SELECT ev_in_co.event_instance_id, ev_in_co.event_instance_content_author_participant_id, co.contentType, co.value, co.postedAt
                                                 FROM event_instance_content AS ev_in_co, event_participants as ev_pa, content as co
                                                 WHERE ev_in_co.event_instance_contentId = co.contentId AND ev_pa.event_instance_id = ev_in_co.event_instance_id 
                                                 AND ev_pa.event_participant_id = '$participantId' AND co.postedAt > '$prevTimestamp' AND co.postedAt < NOW();";
        }
        
        $contentResultAsArray = $this->db_controller->getResultSetAsArray($selectAllContentQueryUsingTimeStamp);

        $this->db_controller->executeSqlQuery("UPDATE Newsfeed SET checkedAt = NOW() WHERE newsFeedId = '$newsFeedId'");
        return $contentResultAsArray;        
    }

    public function getContentForEventInstance($event_instance_id){
        $query = "SELECT ev_in_co.event_instance_id, ev_in_co.event_instance_content_author_participant_id, co.contentType, co.value, co.postedAt
                  FROM event_instance_content AS ev_in_co
                  INNER JOIN  content AS co ON co.contentId = ev_in_co.event_instance_contentId
                  WHERE ev_in_co.event_instance_id = '$event_instance_id'";

        return $this->db_controller->getResultSetAsArray($query);        
    }
 
    public function getEventInstanceStatus($eventStatus_id){
        $query = "SELECT DISTINCT es.eventStatusId, es.name FROM EventStatus AS es
        INNER JOIN event_instance AS ev_in ON es.eventStatusId = ev_in.eventStatus_id
        WHERE es.eventStatusId = '$eventStatus_id'";

        return $this->db_controller->getResultSetAsArray($query);
    }

    public function getAllEvents(){
        $query =  "SELECT ev.event_name, ev_in.*, ev_ma.manager_id, ma.user_id, bankingInfo_id, us.username AS 'adminUsername'
                   FROM event_instance AS ev_in    
                   INNER JOIN event_manager AS ev_ma ON ev_in.event_instanceId = ev_ma.event_instance_id         
                   INNER JOIN Manager as ma ON ev_ma.manager_id = ma.managerId         
                   INNER JOIN Event AS ev ON ev_in.event_id = ev.eventId
                   LEFT JOIN User AS us ON ma.user_id = us.userId";

        return $this->db_controller->getResultSetAsArray($query);
    }


    public function getEventInfo($eventId){
        $query = "SELECT e.event_name, et.name FROM Event AS e
        INNER JOIN EventType AS et ON e.eventType_id = et.id
        WHERE e.eventId = '$eventId'";

        return $this->db_controller->getResultSetAsArray($query);
    }

    public function getParticipantIdsOfEvent($event_instance_id){
        $query = "SELECT * FROM event_participants where event_instance_id = '$event_instance_id'";

        return $this->db_controller->getResultSetAsArray($query);
    }

    public function getManagerUserIdOfEventInstance($event_instance_id){
        $query = "SELECT us.userId FROM User AS us
                  INNER JOIN Manager AS ma ON ma.user_id = us.userId
                  INNER JOIN event_manager AS ev_ma ON ev_ma.manager_id = ma.managerId
                 WHERE event_instance_id = '$event_instance_id'";

        return $this->db_controller->getResultSetAsArray($query);    
    }
}
?>