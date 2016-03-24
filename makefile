SHELL := /bin/bash
CONFIG := config.json
WEB_SERVER := $(shell grep -Po '(?<="web_server": ")[^"]*' ${CONFIG} )

all:
	pip install virtualenv
	@echo "Creo un nuovo ambiente virtuale"
	virtualenv env
	@echo "Do' i permessi necessari"
	chmod -R a+rx /var/log
	chmod +x parser.py
	chmod +x installer.py
	@echo "Stai utilizzando: " $(WEB_SERVER)
	@echo "Installo il software"
	env/bin/python installer.py
	@echo "eseguo il software"
	env/bin/python parser.py
#linkare cartella public nel public html
