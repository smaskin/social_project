DROP USER IF EXISTS 'slave_root'@'192.168.35.13';
CREATE USER 'slave_root'@'192.168.35.13';
GRANT REPLICATION SLAVE ON *.* TO 'slave_root'@'192.168.35.13';
UPDATE mysql.user SET plugin='mysql_native_password' WHERE User='slave_root';
FLUSH PRIVILEGES;
