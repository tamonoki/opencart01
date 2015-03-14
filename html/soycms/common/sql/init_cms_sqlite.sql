
CREATE TABLE Site (
  id INTEGER primary key AUTOINCREMENT,
  site_id VARCHAR unique,
  site_type number default 1,
  site_name VARCHAR,
  isDomainRoot number default 0,
  url VARCHAR,
  path VARCHAR,
  data_source_name VARCHAR(255)
);

CREATE TABLE Administrator (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id VARCHAR NULL unique,
  user_password VARCHAR NULL,
  default_user integer default 0,
  name VARCHAR,
  email VARCHAR,
  token VARCHAR UNIQUE,
  token_issued_date integer
);

CREATE TABLE SiteRole (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER,
  site_id INTEGER,
  is_limit INTEGER default 0,
  unique(user_id,site_id),
  FOREIGN KEY(user_id)
    REFERENCES Administrator(id),
  FOREIGN KEY(site_id)
    REFERENCES Site(id)
);

CREATE TABLE AppRole (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  app_id VARCHAR,
  user_id INTEGER,
  app_role INTEGER,
  app_role_config VARCHAR,
  unique(user_id,app_id),
  FOREIGN KEY(user_id)
    REFERENCES Administrator(id)  
);

CREATE TABLE soycms_admin_data_sets(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  class_name VARCHAR UNIQUE,
  object_data TEXT
);

CREATE TABLE CookieLogin (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  token VARCHAR UNIQUE NOT NULL,
  expire INTEGER
);
