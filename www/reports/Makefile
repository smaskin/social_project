test:
	wrk -c1 -t1 -d60s 'http://social.local/index.php?api=1&query=%D0%9B%D0%B5'
	wrk -c10 -t1 -d60s 'http://social.local/index.php?api=1&query=%D0%9B%D0%B5'
	wrk -c1 -t1 -d60s 'http://social.local/index.php?api=1&query=%D0%BB%D0%B5%D0%BD%D0%B8'
	wrk -c10 -t1 -d60s 'http://social.local/index.php?api=1&query=%D0%BB%D0%B5%D0%BD%D0%B8'
