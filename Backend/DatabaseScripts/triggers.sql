USE SCCNetwork;
DROP TRIGGER IF EXISTS after_user_insert_create_derived_admin_participant_or_controller;
DROP TRIGGER IF EXISTS before_deleting_administrator_prevent_deleting_root;
DROP TRIGGER IF EXISTS before_deleting_user_prevent_deleting_admin_or_root;
DROP TRIGGER IF EXISTS after_event_type_insert_insert_its_associated_fee;
DROP TRIGGER IF EXISTS after_manager_insert_create_participant_if_not_existing;
DROP TRIGGER IF EXISTS before_event_instance_insert_check_input_and_create_default_page;
DROP TRIGGER IF EXISTS after_event_instance_insert_create_event_participant_and_contnt;
DROP TRIGGER IF EXISTS after_event_manager_insert_create_event_participant;
DROP TRIGGER IF EXISTS before_group_insert_create_default_page;
DROP TRIGGER IF EXISTS after_event_group_insert;
DROP TRIGGER IF EXISTS before_deactivating_user;

DELIMITER $$
CREATE TRIGGER after_user_insert_create_derived_admin_participant_or_controller AFTER INSERT ON `User` FOR EACH ROW
BEGIN
	DECLARE role_name_in_scc varchar(255);

	SET role_name_in_scc = (SELECT role_name 
							FROM RoleInSCC
							WHERE id = NEW.roleInSCC_id);

    IF( role_name_in_scc like '%admin%') THEN
        INSERT INTO `Administrator`(`user_id`) VALUES (NEW.userId);
        INSERT INTO `Participant`(`user_id`) VALUES (NEW.userId);
        INSERT INTO `Member` (`user_id`) VALUEs (NEW.userId);
    ELSEIF (role_name_in_scc like '%control%') THEN
        INSERT INTO `Controller`(`user_id`) VALUES (NEW.userId);
    END IF;
    
    INSERT INTO `Newsfeed`(`userId`, `checkedAt`) VALUES (NEW.userId, NULL);
END;
$$

CREATE TRIGGER before_deleting_administrator_prevent_deleting_root BEFORE DELETE ON `Administrator` FOR EACH ROW
BEGIN
	IF (OLD.user_id = 1) THEN
    		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'You cannot delete the root user.';
    END IF;
END;
$$
    
CREATE TRIGGER before_deleting_user_prevent_deleting_admin_or_root BEFORE DELETE ON `User` FOR EACH ROW
BEGIN
	DECLARE admin_id int DEFAULT 0;
    
    SET admin_id = (SELECT adminId 
					FROM Administrator 
					WHERE user_id = OLD.userId);
                    
	IF (admin_id > 0 OR OLD.userId = 1)  THEN
    		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'You cannot delete the root user or any other that is admin. If ever needed, excluding the root user, remove the user admin privileges and attempt to delete him after';
    END IF;
END;
$$

CREATE TRIGGER after_event_type_insert_insert_its_associated_fee AFTER INSERT ON `EventType` FOR EACH ROW
BEGIN
	DECLARE flat_rate int DEFAULT 100;
    DECLARE rate_for_extra_storage_unit int DEFAULT 0.0125;
    DECLARE rate_for_extra_bandwidth_unit int DEFAULT 0.025;
    DECLARE rate_for_extra_day int DEFAULT 0.50;
    
	IF ( NEW.id = 2 ) THEN
		SET flat_rate = 0;
        SET rate_for_extra_storage_unit = 0;
        SET rate_for_extra_bandwidth_unit = 0;
        SET rate_for_extra_day = 0;
	END IF;
        
	INSERT INTO `Fee`(`eventType_id`,`rateForExtraStorageUnit`, `rateForExtraBandwithUnit`, `flatRate`, `rateForExtraExpirationDay`) VALUES
				(NEW.id, rate_for_extra_storage_unit, rate_for_extra_bandwidth_unit, flat_rate, rate_for_extra_day);
END;
$$

CREATE TRIGGER after_manager_insert_create_participant_if_not_existing AFTER INSERT ON `Manager` FOR EACH ROW
BEGIN
	DECLARE existing_participant_id int;
    
    SET existing_participant_id = (SELECT participantId
								   FROM Participant 
                                   WHERE user_id = NEW.user_ID);
								
	IF ( existing_participant_id IS NULL OR existing_participant_id = 0) THEN
		INSERT INTO `Participant`(`user_id`) VALUES (NEW.user_id);
	END IF;
END;
$$

CREATE TRIGGER before_group_insert_create_default_page BEFORE INSERT ON `Group` FOR EACH ROW
BEGIN
	DECLARE DEFAULT_PAGE_CONTENT text;
    
	SET default_page_content = ( SELECT content 
								FROM Page
								LIMIT 1);
                                
	INSERT INTO `Page`(`content`) VALUES (default_page_content);
    SET NEW.group_page_id = (SELECT LAST_INSERT_ID());
END;
$$

CREATE TRIGGER after_event_group_insert AFTER INSERT ON `event_groups` FOR EACH ROW
BEGIN
	INSERT INTO `group_member`(`event_group_id`, `member_id`) VALUES (NEW.event_group_id, 1);
    INSERT INTO `group_content`(`event_group_id`, `event_group_content_id`, `event_group_content_author_member_id`)
    VALUES (NEW.event_group_id, 2, 1);
END;
$$
	
CREATE TRIGGER before_event_instance_insert_check_input_and_create_default_page BEFORE INSERT ON `event_instance` FOR EACH ROW
BEGIN
	DECLARE	default_storage_limit int;
	DECLARE default_bandwidth_limit int;
	DECLARE default_initial_time_extension_from_present_plus_hours_added int;
	DECLARE default_page_content text;
    
    SELECT defaultStorageLimitInMb, defaultBandwidthLimitInMB, defaultInitialActiveEventConfigurationTimeInHours
    INTO default_storage_limit, default_bandwidth_limit, default_initial_time_extension_from_present_plus_hours_added
	FROM DefaultEventConfiguration LIMIT 1;
    
	SET default_page_content = ( SELECT content 
							 FROM Page
							 LIMIT 1);
                             
	IF( NEW.storage_limit < 1) THEN
		SET NEW.storage_limit = default_storage_limit;
	END IF;
	IF (NEW.bandwith_limit < 1) THEN
		SET NEW.bandwith_limit = default_bandwidth_limit;
	END IF;
	IF (NEW.eventStatus_id != 3) THEN
		SET NEW.eventStatus_id = 3;
	END IF;
	IF (NEW.lifetime IS NULL) THEN
		SET NEW.lifetime = DATE_ADD(NOW(), INTERVAL default_initial_time_extension_from_present_plus_hours_added HOUR);
	END IF;
    
    INSERT INTO `Page`(`content`) VALUES (default_page_content);
    
    SET NEW.page_id = (SELECT LAST_INSERT_ID());

END;
$$

CREATE TRIGGER after_event_instance_insert_create_event_participant_and_contnt AFTER INSERT ON `event_instance` FOR EACH ROW
BEGIN
	DECLARE pk_value_of_default_message int;

	SET pk_value_of_default_message = 1;
    
	INSERT INTO `event_participants`(`event_instance_id`, `event_participant_id`)
    SELECT NEW.event_instanceId, participantId FROM Participant as pa
    INNER JOIN Administrator as ad ON ad.user_id = pa.user_id;
    
    INSERT INTO `event_instance_content`(`event_instance_id`, `event_instance_contentId`, `event_instance_content_author_participant_id`)
    SELECT NEW.event_instanceId, pk_value_of_default_message, participantId FROM Participant as pa
    INNER JOIN Administrator as ad ON ad.user_id = pa.user_id
    WHERE pa.user_id = 1;
    
END;
$$

CREATE TRIGGER after_event_manager_insert_create_event_participant AFTER INSERT ON `event_manager`FOR EACH ROW
BEGIN
	DECLARE manager_event_participant_id int;
    
    SET manager_event_participant_id = (SELECT participantId FROM Participant AS pa
								INNER JOIN Manager AS ma ON pa.user_id = ma.user_id 
                                WHERE ma.managerId = NEW.manager_id);
    
    IF (manager_event_participant_id IS NOT NULL OR manager_event_participant_id != 0) THEN
		IF (manager_event_participant_id NOT IN (SELECT participantId FROM Participant AS pa INNER JOIN Administrator AS ad ON pa.user_id = ad.user_id)) THEN
			INSERT INTO `event_participants`(`event_instance_id`, `event_participant_id`) VALUES(NEW.event_instance_id, manager_event_participant_id);
      END IF;
	END IF;
    
END;
$$

CREATE TRIGGER before_deactivating_user BEFORE INSERT ON `BannedUsers` FOR EACH ROW
BEGIN
	DECLARE admin_id int DEFAULT 0;
    
    SET adminstrator_id = (SELECT adminId FROM Administrator WHERE user_id = NEW.user_id);
    
    IF (administrator_id > 0) THEN 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'You cannot deactive an admin account/user.';
    END IF;
END;

DELIMITER ;