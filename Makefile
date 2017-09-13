define fmt
	bin/php-cs-fixer fix --config=.php_cs.dist $(1) || true
endef

define install_composer
	-[ ! -e bin/composer ] && wget https://getcomposer.org/composer.phar -O bin/composer && chmod +x bin/composer || true
endef

fmt:
	$(call fmt,)

setup-dev:
	$(call install_composer)
	bin/composer install
	if [ ! -e bin/git-hooks ]; then \
		wget https://raw.githubusercontent.com/sasanrose/git-hooks/master/git-hooks -O bin/git-hooks && chmod u+x bin/git-hooks && bin/git-hooks --install; \
	else \
		bin/git-hooks --uninstall && bin/git-hooks --install bin; \
	fi;

install:
	$(call install_composer,) \
	bin/composer install --no-dev
