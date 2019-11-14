#!/bin/bash
sleep 20

declare -a slaves=("slave" "serf")

mysql --host master -uroot -p$MYSQL_MASTER_PASSWORD -AN -e "CREATE USER IF NOT EXISTS '$MYSQL_REPLICATION_USER'@'%';"
mysql --host master -uroot -p$MYSQL_MASTER_PASSWORD -AN -e "GRANT REPLICATION SLAVE ON *.* TO '$MYSQL_REPLICATION_USER'@'%' IDENTIFIED BY '$MYSQL_REPLICATION_PASSWORD';"
mysql --host master -uroot -p$MYSQL_MASTER_PASSWORD -AN -e 'FLUSH PRIVILEGES;'
mysql --host master -uroot -p$MYSQL_MASTER_PASSWORD -AN -e "INSTALL PLUGIN rpl_semi_sync_master SONAME 'semisync_master.so';";
mysql --host master -uroot -p$MYSQL_MASTER_PASSWORD -AN -e "SET GLOBAL rpl_semi_sync_master_enabled = 1;";

MASTER_POSITION=$(eval "mysql --host master -uroot -p$MYSQL_MASTER_PASSWORD -e 'show master status \G' | grep Position | sed -n -e 's/^.*: //p'")
MASTER_FILE=$(eval "mysql --host master -uroot -p$MYSQL_MASTER_PASSWORD -e 'show master status \G'     | grep File     | sed -n -e 's/^.*: //p'")

for name in "${slaves[@]}"
do
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "CREATE USER IF NOT EXISTS '$MYSQL_REPLICATION_USER'@'%';"
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "GRANT REPLICATION SLAVE ON *.* TO '$MYSQL_REPLICATION_USER'@'%' IDENTIFIED BY '$MYSQL_REPLICATION_PASSWORD';"
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e 'FLUSH PRIVILEGES;'
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "INSTALL PLUGIN rpl_semi_sync_slave SONAME 'semisync_slave.so';";
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "INSTALL PLUGIN rpl_semi_sync_master SONAME 'semisync_master.so';";
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "SET GLOBAL rpl_semi_sync_slave_enabled = 1";
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e 'STOP SLAVE;';
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e 'RESET SLAVE ALL;';
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "CHANGE MASTER TO master_host='master', master_port=3306, \
            master_user='$MYSQL_REPLICATION_USER', master_password='$MYSQL_REPLICATION_PASSWORD', master_log_file='$MASTER_FILE', \
            master_log_pos=$MASTER_POSITION;"
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "START SLAVE;"
    mysql --host "$name" -uroot -p$MYSQL_SLAVE_PASSWORD -e "SHOW SLAVE STATUS \G"
done
