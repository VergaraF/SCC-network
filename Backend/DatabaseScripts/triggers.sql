USE SCCNetwork;
DROP TRIGGER IF EXISTS after_user_insert;

DELIMITER $$
CREATE TRIGGER after_user_insert AFTER INSERT ON `User` FOR EACH ROW
BEGIN
DECLARE role_name_in_scc varchar(255);

	set role_name_in_scc = (SELECT role_name 
    FROM RoleInSCC
    WHERE id = NEW.roleInSCC_id);

    IF( role_name_in_scc like '%admin%') THEN
        INSERT INTO `Administrator`(`user_id`) VALUES (NEW.userId);
    ELSEIF (role_name_in_scc like '%control%') THEN
        INSERT INTO `Controller`(`user_id`) VALUES (NEW.userId);
    END IF;
END;

$$
DELIMITER ;