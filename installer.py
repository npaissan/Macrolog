import os, json
'''
installer.py viene eseguito una sola volta per aggiornare il file logrotate del server web, dicendo di eseguire parser.py ad ogni rotazione di log
'''

CURRENT_DIR = os.path.dirname(__file__)
CONFIG_FILE = os.path.join(CURRENT_DIR, "config.json")
PARSER_FILE = os.path.abspath("parser.py") #uso abspath() perche' sono nella cartella in cui parser.py risiede, quindi mi permette di ricavare il percorso assoluto
LOGROTATE_PATH = "/etc/logrotate.d/" #percorso dove risiedono i file di cinfigurazione di Logrotate
contenuto_logrotate = [] #lista con contenuto del file del server web usato dall'utente (nginx, apache...)

with open(CONFIG_FILE, 'r') as data_file: #carica file di configurazione
    config = json.load(data_file) #assegna il file di configurazione ad una var
server_usato = config["web_server"] #nome server web utilizzato dal sito in questione

logrotate_server_file = LOGROTATE_PATH + server_usato

try:
	with open(logrotate_server_file, "r") as logrotate_file_r: #leggo come read e salvo il contenuto in una lista.
		for linea in logrotate_file_r:
			contenuto_logrotate.append(linea)
			if "create" in linea:
				linea_permessi = "\tcreate 0647 www-data adm \n"
				del contenuto_logrotate[-1]
				contenuto_logrotate.append(linea_permessi)
			if "postrotate" in linea:
				contenuto_da_scrivere = "		python " + PARSER_FILE + "\n"
				contenuto_logrotate.append(contenuto_da_scrivere)
	with open(logrotate_server_file, "w") as logrotate_file_w: #visto che l'apertura in write cancella il contenuto del file, lo riscrivo attingendo dalla lista.
		for linea in contenuto_logrotate:
			logrotate_file_w.write(linea)

except IOError as e:
    print "I/O error({0}): {1}".format(e.errno, e.strerror)