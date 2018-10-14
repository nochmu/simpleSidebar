
test_container:
	-docker rm -f sidebar-test
	docker run -d -p 80:80 --name sidebar-test -v "$(PWD)":/var/www/html php:7.2-apache
