define fmt
	bin/php-cs-fixer fix --config=.php_cs.dist $(1) || true
endef

define install_composer
	-[ ! -e bin/composer ] && wget https://getcomposer.org/composer.phar -O bin/composer && chmod +x bin/composer || true
endef

define run_in_docker
	$(eval randname = $(shell cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1))
	docker run -it --name $(randname) -e USER=$(USER) -v "$(shell pwd -P):/phpredmin" -w "/phpredmin" --user "$(3)" $(1) $(2)
	docker rm $(randname)
endef

docker:
	docker build -t sasanrose/phpredmin:2.0 -f .docker/Dockerfile .

docker-dev:
	docker build -t sasanrose/phpredmin:2.0-dev -f .docker/Dockerfile-dev .

docker-fa-ir:
	docker build -t sasanrose/phpredmin:2.0-fa -f .docker/Dockerfile-fa_IR .

fmt:
	$(call fmt,)

gen-test-coverage:
	rm cover/ -rf
	mkdir -m 0755 cover/
	$(call run_in_docker,sasanrose/phpredmin:2.0-dev,phpdbg -dmemory_limit=512M -qrr ./bin/phpunit -c .phpunit.cover.xml,$(shell id -u):www-data)

install:
	$(call install_composer) \
	bin/composer install --no-dev

install-assets:
	./bin/install-assets

setup-dev:
	$(call install_composer)
	bin/composer install
	if [ ! -e bin/git-hooks ]; then \
		wget https://raw.githubusercontent.com/sasanrose/git-hooks/master/git-hooks -O bin/git-hooks && chmod u+x bin/git-hooks && bin/git-hooks --install; \
	else \
		bin/git-hooks --uninstall && bin/git-hooks --install bin; \
	fi;
	docker build -t sasanrose/phpredmin:2.0-dev -f .docker/Dockerfile-dev .
