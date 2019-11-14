restart:
	docker-compose down && docker-compose up -d
bash:
	docker exec -t -i social_web_1 /bin/bash
bash_master:
	docker exec -t -i social_master_1 /bin/bash
bash_slave:
	docker exec -t -i social_slave_1 /bin/bash
failover:
	docker kill --signal=SIGKILL social_master_1
	docker exec -t -i social_slave_1 /tmp/promote.sh
