define fmt
	bin/php-cs-fixer fix --config=.php_cs_fmt.dist $(1) || true
endef

define install_composer
	-[ ! -e bin/composer ] && wget https://getcomposer.org/composer.phar -O bin/composer && chmod +x bin/composer || true
endef

define run_in_docker
	$(eval randname = $(shell cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1))
	docker run -it --name $(randname) -e USER=$(USER) -v "$(shell pwd -P):/phpredmin" -w "/phpredmin" --user "$(3)" $(1) $(2)
	docker rm $(randname)
endef

tagname = sasanrose/phpredmin:2.0
dockerfile = .docker/Dockerfile
ifdef DOCKERTAG
	tagname = sasanrose/phpredmin:2.0-$(DOCKERTAG)
	dockerfile = .docker/Dockerfile-$(DOCKERTAG)
endif

localeDockerfile = sasanrose/phpredmin:2.0-dev-$(LOCALE)

docker:
	docker build -t $(tagname) -f $(dockerfile) .

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

locale-files:
	$(call run_in_docker,$(localeDockerfile),./bin/gettext/extract && ./bin/gettext/locale -l $(LOCALE) && ./bin/gettext/compile -l $(LOCALE),$(shell id -u):$(shell id -u))

setup-dev:
	$(call install_composer)
	bin/composer install
	if [ ! -e bin/git-hooks ]; then \
		wget https://raw.githubusercontent.com/sasanrose/git-hooks/master/git-hooks -O bin/git-hooks && chmod u+x bin/git-hooks && bin/git-hooks --install; \
	else \
		bin/git-hooks --uninstall && bin/git-hooks --install bin; \
	fi;
	docker build -t sasanrose/phpredmin:2.0-dev -f .docker/Dockerfile-dev .
