#!/bin/bash

mysql --host slave -uroot -p$MYSQL_MASTER_PASSWORD -AN -e "STOP SLAVE;";
mysql --host slave -uroot -p$MYSQL_MASTER_PASSWORD -AN -e "RESET MASTER;";
mysql --host slave -uroot -p$MYSQL_MASTER_PASSWORD -AN -e "SET GLOBAL rpl_semi_sync_master_enabled = 1;";

MASTER_POSITION=$(eval "mysql --host slave -uroot -p$MYSQL_MASTER_PASSWORD -e 'show master status \G' | grep Position | sed -n -e 's/^.*: //p'")
MASTER_FILE=$(eval "mysql --host slave -uroot -p$MYSQL_MASTER_PASSWORD -e 'show master status \G'     | grep File     | sed -n -e 's/^.*: //p'")

mysql --host serf -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e 'STOP SLAVE;';
mysql --host serf -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "CHANGE MASTER TO master_host='slave', master_port=3306, \
        master_user='$MYSQL_REPLICATION_USER', master_password='$MYSQL_REPLICATION_PASSWORD', master_log_file='$MASTER_FILE', \
        master_log_pos=$MASTER_POSITION;"
mysql --host serf -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "RESET SLAVE;"
mysql --host serf -uroot -p$MYSQL_SLAVE_PASSWORD -AN -e "START SLAVE;"
mysql --host serf -uroot -p$MYSQL_SLAVE_PASSWORD -e "SHOW SLAVE STATUS \G"
