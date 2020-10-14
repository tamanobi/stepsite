dockercompose := /usr/local/bin/docker-compose


.PHONY: bash
bash:
	${dockercompose} run --rm -v /etc/group:/etc/group:ro -v /etc/passwd:/etc/passwd:ro -u $(shell id -u ${USER}):$(shell id -g ${USER}) app bash

.PHONY: tinker
tinker:
	${dockercompose} run --rm app bash -c "php artisan tinker"

.PHONY: stop
stop:
	${dockercompose} stop

.PHONY: start
start:
	${dockercompose} start

.PHONY: deploy
	git push heroku main
