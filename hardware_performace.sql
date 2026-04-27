CREATE DATABASE IF NOT EXISTS hardware_performance;
USE hardware_performance;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DELIMITER $$
CREATE PROCEDURE get_recommendations()
BEGIN
    SELECT * FROM system_recommendation;
END$$
DELIMITER ;

CREATE TABLE brands (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

INSERT INTO brands (id, name) VALUES
(1, 'NVIDIA'),
(2, 'AMD'),
(3, 'Intel');

CREATE TABLE cpus (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) DEFAULT NULL,
  overall_performance int(11) DEFAULT NULL,
  gaming_performance int(11) DEFAULT NULL,
  tier varchar(50) DEFAULT NULL,
  brand_id int(11) DEFAULT NULL,
  gaming_upgrade_class varchar(50) DEFAULT NULL,
  overall_upgrade_class varchar(50) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY brand_id (brand_id),
  CONSTRAINT cpus_ibfk_1 FOREIGN KEY (brand_id) REFERENCES brands (id)
) ENGINE=InnoDB;

DELIMITER $$
CREATE TRIGGER cpu_upgrade_trigger 
BEFORE INSERT ON cpus
FOR EACH ROW
BEGIN
    SET NEW.gaming_upgrade_class = CASE
        WHEN NEW.gaming_performance >= 110 THEN 'Upgrade Recommended'
        WHEN NEW.gaming_performance >= 102 THEN 'Marginal Upgrade'
        WHEN NEW.gaming_performance >= 95 THEN 'Sidegrade'
        ELSE 'Downgrade'
    END;

    SET NEW.overall_upgrade_class = CASE
        WHEN NEW.overall_performance >= 120 THEN 'Upgrade Recommended'
        WHEN NEW.overall_performance >= 105 THEN 'Marginal Upgrade'
        WHEN NEW.overall_performance >= 95 THEN 'Sidegrade'
        ELSE 'Downgrade'
    END;
END$$
DELIMITER ;

INSERT INTO cpus VALUES
(1,'Ryzen 9 9950X',155,108,'Enthusiast',2,NULL,NULL),
(2,'Ryzen 9 7950X',145,95,'Enthusiast',2,NULL,NULL),
(3,'Core i9-14900K',140,100,'Enthusiast',3,NULL,NULL),
(4,'Core i9-13900K',135,98,'Enthusiast',3,NULL,NULL),
(5,'Ryzen 7 7800X3D',100,100,'High-End Gaming',2,NULL,NULL),
(6,'Ryzen 9 7950X3D',130,104,'High-End Gaming',2,NULL,NULL),
(7,'Ryzen 7 5800X3D',75,90,'High-End Gaming',2,NULL,NULL),
(8,'Core i7-14700K',125,98,'High-End Balanced',3,NULL,NULL),
(9,'Ryzen 7 7700X',95,92,'High-End Balanced',2,NULL,NULL),
(10,'Core i5-14600K',105,95,'High-End Balanced',3,NULL,NULL),
(11,'Ryzen 5 7600X',85,90,'Upper Midrange',2,NULL,NULL),
(12,'Core i5-13600K',100,94,'Upper Midrange',3,NULL,NULL),
(13,'Ryzen 7 5800X',80,82,'Upper Midrange',2,NULL,NULL),
(14,'Ryzen 5 5600X',65,75,'Midrange',2,NULL,NULL),
(15,'Core i5-12400F',60,72,'Midrange',3,NULL,NULL),
(16,'Ryzen 5 3600',45,55,'Lower Tier',2,NULL,NULL),
(17,'Core i7-10700K',55,65,'Lower Tier',3,NULL,NULL);

UPDATE cpus
SET gaming_upgrade_class = CASE
    WHEN gaming_performance >= 110 THEN 'Upgrade Recommended'
    WHEN gaming_performance >= 102 THEN 'Marginal Upgrade'
    WHEN gaming_performance >= 95 THEN 'Sidegrade'
    ELSE 'Downgrade'
END,
overall_upgrade_class = CASE
    WHEN overall_performance >= 120 THEN 'Upgrade Recommended'
    WHEN overall_performance >= 105 THEN 'Marginal Upgrade'
    WHEN overall_performance >= 95 THEN 'Sidegrade'
    ELSE 'Downgrade'
END;

CREATE TABLE gpus (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) DEFAULT NULL,
  relative_performance int(11) DEFAULT NULL,
  tier varchar(50) DEFAULT NULL,
  brand_id int(11) DEFAULT NULL,
  upgrade_class varchar(50) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY brand_id (brand_id),
  CONSTRAINT gpus_ibfk_1 FOREIGN KEY (brand_id) REFERENCES brands (id)
) ENGINE=InnoDB;

DELIMITER $$
CREATE TRIGGER gpu_upgrade_trigger 
BEFORE INSERT ON gpus
FOR EACH ROW
BEGIN
    SET NEW.upgrade_class = CASE
        WHEN NEW.relative_performance >= 115 THEN 'Upgrade Recommended'
        WHEN NEW.relative_performance >= 105 THEN 'Marginal Upgrade'
        WHEN NEW.relative_performance >= 100 THEN 'Sidegrade'
        ELSE 'Downgrade'
    END;
END$$
DELIMITER ;

INSERT INTO gpus VALUES
(1,'RTX 5090',174,'Faster than Baseline',1,NULL),
(2,'RTX 4090',133,'Faster than Baseline',1,NULL),
(3,'RTX 5080',115,'Faster than Baseline',1,NULL),
(4,'RTX 4080 SUPER',102,'Faster than Baseline',1,NULL),
(5,'RTX 4080',102,'Faster than Baseline',1,NULL),
(6,'RX 7900 XTX',101,'Faster than Baseline',2,NULL),
(7,'RTX 5070 Ti',100,'Baseline',1,NULL),
(8,'RX 9070 XT',96,'Slightly Slower',2,NULL),
(9,'RX 7900 XT',87,'Slightly Slower',2,NULL),
(10,'RTX 3090 Ti',86,'Slightly Slower',1,NULL),
(11,'RTX 4070 Ti SUPER',86,'Slightly Slower',1,NULL),
(12,'RX 9070',86,'Slightly Slower',2,NULL),
(13,'RTX 4070 Ti',78,'Upper Midrange',1,NULL),
(14,'RTX 5070',78,'Upper Midrange',1,NULL),
(15,'RTX 3090',77,'Upper Midrange',1,NULL),
(16,'RTX 3080 Ti',75,'Upper Midrange',1,NULL),
(17,'RTX 4070 SUPER',73,'Upper Midrange',1,NULL),
(18,'RX 7900 GRE',72,'Upper Midrange',2,NULL),
(19,'RX 6950 XT',71,'Upper Midrange',2,NULL),
(20,'RTX 4070',70,'Upper Midrange',1,NULL),
(21,'RX 6900 XT',69,'Midrange',2,NULL),
(22,'RTX 3080',68,'Midrange',1,NULL),
(23,'RX 7800 XT',68,'Midrange',2,NULL),
(24,'RX 6800 XT',65,'Midrange',2,NULL),
(25,'RTX 5060 Ti (16GB)',61,'Midrange',1,NULL),
(26,'RTX 5060 Ti (8GB)',60,'Midrange',1,NULL),
(27,'RX 7700 XT',59,'Midrange',2,NULL),
(28,'RX 9060 XT (16GB)',58,'Midrange',2,NULL),
(29,'RTX 3070 Ti',58,'Midrange',1,NULL),
(30,'RX 6800',56,'Lower Tier',2,NULL),
(31,'RX 6750 XT',55,'Lower Tier',2,NULL),
(32,'RX 9060 XT (8GB)',54,'Lower Tier',2,NULL),
(33,'RTX 3070',54,'Lower Tier',1,NULL),
(34,'RTX 2080 Ti',53,'Lower Tier',1,NULL),
(35,'RTX 4060 Ti (8GB)',53,'Lower Tier',1,NULL),
(36,'RTX 5060',52,'Lower Tier',1,NULL),
(37,'RTX 3060 Ti',47,'Lower Tier',1,NULL);

UPDATE gpus
SET upgrade_class = CASE
    WHEN relative_performance >= 115 THEN 'Upgrade Recommended'
    WHEN relative_performance >= 105 THEN 'Marginal Upgrade'
    WHEN relative_performance >= 100 THEN 'Sidegrade'
    ELSE 'Downgrade'
END;

CREATE TABLE user_systems (
  id int(11) NOT NULL AUTO_INCREMENT,
  cpu_id int(11) DEFAULT NULL,
  gpu_id int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY cpu_id (cpu_id),
  KEY gpu_id (gpu_id),
  CONSTRAINT user_systems_ibfk_1 FOREIGN KEY (cpu_id) REFERENCES cpus (id),
  CONSTRAINT user_systems_ibfk_2 FOREIGN KEY (gpu_id) REFERENCES gpus (id)
) ENGINE=InnoDB;

INSERT INTO user_systems (cpu_id, gpu_id) VALUES
(5,7),
(10,20),
(14,30);

CREATE VIEW bottleneck_analysis AS
SELECT 
    c.name AS cpu,
    g.name AS gpu,
    g.relative_performance - c.gaming_performance AS gap,
    CASE 
        WHEN g.relative_performance - c.gaming_performance > 20 THEN 'CPU Bottleneck'
        WHEN g.relative_performance - c.gaming_performance < -20 THEN 'GPU Bottleneck'
        ELSE 'Balanced'
    END AS bottleneck
FROM user_systems u
JOIN cpus c ON u.cpu_id = c.id
JOIN gpus g ON u.gpu_id = g.id;

CREATE VIEW cpu_efficiency AS
SELECT 
    c.name,
    c.overall_performance,
    c.gaming_performance,
    c.gaming_performance - c.overall_performance AS gaming_bias,
    c.gaming_upgrade_class,
    c.overall_upgrade_class
FROM cpus c;

CREATE VIEW gpu_rankings AS
SELECT 
    g.name,
    g.relative_performance,
    b.name AS brand,
    g.upgrade_class
FROM gpus g
JOIN brands b ON g.brand_id = b.id
ORDER BY g.relative_performance DESC;

CREATE VIEW system_recommendation AS
SELECT 
    u.id,
    c.name AS cpu,
    g.name AS gpu,
    CASE 
        WHEN g.relative_performance < 100 THEN 'Upgrade GPU'
        WHEN c.gaming_performance < 100 THEN 'Upgrade CPU'
        ELSE 'System Balanced'
    END AS recommendation
FROM user_systems u
JOIN cpus c ON u.cpu_id = c.id
JOIN gpus g ON u.gpu_id = g.id;

COMMIT;
