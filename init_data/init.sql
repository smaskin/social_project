USE mysql;
UPDATE user SET plugin='mysql_native_password' WHERE User='root';
FLUSH PRIVILEGES;

CREATE DATABASE IF NOT EXISTS social DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
