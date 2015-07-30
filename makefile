all:
	apt-get install build-essential python-dev
	pip install virtualenv
	virtualenv env
	chmod -R a+rx /var/log
	chmod +x parser.py
	env/bin/python parser.py
	