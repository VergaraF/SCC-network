USE SCCNetwork;
DROP EVENT IF EXISTS deactivate_expired_events_daily;
DROP EVENT IF EXISTS marked_expired_events_as_deleted_after_seven_years_period;

DELIMITER $$
CREATE EVENT deactivate_expired_events_daily
	ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 24 HOUR
    DO
		BEGIN
			DECLARE currentTimeStamp datetime DEFAULT CURRENT_TIMESTAMP;
            DECLARE i int DEFAULT 0;
            DECLARE max int DEFAULT 0;
            DECLARE event_instance_id int DEFAULT 0;
            DECLARE event_instance_lifetime datetime DEFAULT CURRENT_TIMESTAMP;
			DECLARE days_in_a_year int DEFAULT 365;
            DECLARE total_amount_of_days_in_7_years int DEFAULT 0;
            
            SET total_amount_of_days_in_7_years = days_in_a_year * 7;
			SET i = 0;
            SET max = (SELECT COUNT(*) FROM event_instance 
					  WHERE lifetime < CURRENT_TIMESTAMP AND eventStatus_id = 1);
            
            WHILE i < max DO
                SELECT event_instanceId, lifetime
					INTO event_instance_id, event_instance_lifetime 
                FROM event_instance
                WHERE lifetime < CURRENT_TIMESTAMP AND eventStatus_id = 1
                LIMIT i, 1;
                
				UPDATE event_instance 
					SET eventStatus_id = 2,
						lifetime = ADDDATE(event_instance_lifetime, total_amount_of_days_in_7_years)
                    WHERE event_instanceId = event_instance_id;
                SET i = i + 1;
			END WHILE;  
		END;
$$

CREATE EVENT marked_expired_events_as_deleted_after_seven_years_period
	ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 24 HOUR
    DO
		BEGIN
			DECLARE currentTimeStamp datetime DEFAULT CURRENT_TIMESTAMP;
            DECLARE i int DEFAULT 0;
            DECLARE max int DEFAULT 0;
            DECLARE event_instance_id int DEFAULT 0;

			SET i = 0;
            
            SET max = (SELECT COUNT(*) FROM event_instance 
					  WHERE lifetime < CURRENT_TIMESTAMP AND eventStatus_id = 2);
            
            WHILE i < max DO
				SET event_instance_id = (SELECT event_instanceId FROM event_instance
                                         WHERE lifetime < CURRENT_TIMESTAMP AND eventStatus_id = 2
                                         LIMIT i, 1);
                                         
                UPDATE event_instance SET eventStatus_id = 4 WHERE event_instanceId = event_instance_id;
                SET i = i + 1;
			END WHILE;  
		END;  
$$
DELIMITER ;
	 
      