
CREATE TABLE Site (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  site_id VARCHAR(255) UNIQUE,
  site_type INTEGER default 1,
  site_name VARCHAR(255),
  isDomainRoot INTEGER default 0,
  url VARCHAR(255),
  path VARCHAR(255),
  data_source_name VARCHAR(255) UNIQUE
)ENGINE = InnoDB;

CREATE TABLE Administrator (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  user_id VARCHAR(255) NULL unique,
  user_password VARCHAR(255) NULL,
  default_user INTEGER default 0,
  name VARCHAR(255),
  email VARCHAR(255),
  token VARCHAR(255) UNIQUE,
  token_issued_date INTEGER
)ENGINE = InnoDB;

CREATE TABLE SiteRole (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  user_id INTEGER,
  site_id INTEGER,
  is_limit INTEGER DEFAULT 0,
  UNIQUE (user_id,site_id)
)ENGINE = InnoDB;

CREATE TABLE AppRole (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  app_id VARCHAR(255),
  user_id INTEGER,
  app_role INTEGER,
  app_role_config TEXT,
  unique(user_id,app_id),
  FOREIGN KEY(user_id)
    REFERENCES Administrator(id)  
)ENGINE = InnoDB;

CREATE TABLE soycms_admin_data_sets(
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  class_name VARCHAR(255) unique,
  object_data LONGTEXT
)ENGINE = InnoDB;

CREATE TABLE CookieLogin (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  user_id INTEGER NOT NULL,
  token VARCHAR(255) UNIQUE NOT NULL,
  expire INTEGER
)ENGINE = InnoDB;
