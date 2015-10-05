#!/usr/bin/env python

import re, sys, json, time, datetime, os, glob, errno, gzip, sqlite3, csv
'''
Costanti dei percorsi assoluti dei file, mi permette di eseguire lo script da tutte le directory
'''
CURRENT_DIR = os.path.dirname(__file__) 
CONFIG_FILE = os.path.join(CURRENT_DIR, "config.json")
LOG_FILE = os.path.join(CURRENT_DIR, "macro.log")
DB_FILE = os.path.join(CURRENT_DIR, "macro.db")
'''CSV_VISITATORI_FILE = os.path.abspath(".") + "/public/csv"
if not os.path.exists(CSV_VISITATORI_FILE):
    os.makedirs(CSV_VISITATORI_FILE)
CSV_VISITATORI_FILE = CSV_VISITATORI_FILE + "/conteggio_visitatori.csv"
print CSV_VISITATORI_FILE'''
'''
Regex per individuare le linee utili di un access log
'''
pat_get = (r'' #individua tutti i GET
            '(\d+.\d+.\d+.\d+)\s-\s-\s' #IP address: 0
            '\[(.+)\]\s' #datetime: 1
            '"GET\s(.+)\s\w+/.+"\s' #requested file: 2
            '(\d+)\s' #status protocollo: 3
            '(\d+)\s' #bandwidth dimensione: 4
            '"(.+)"\s' #referrer: 5
            '"(.+)"' #user agent: 6
        )

pat_post = (r'' #individua tutti i POST
            '(\d+.\d+.\d+.\d+)\s-\s-\s' #IP address: 0
            '\[(.+)\]\s' #datetime: 1
            '"POST\s(.+)\s\w+/.+"\s' #requested file: 2
            '(\d+)\s' #status protocollo: 3
            '(\d+)\s' #bandwidth dimensione: 4
            '"(.+)"\s' #referrer: 5
            '"(.+)"' #user agent: 6
        )


'''
leggiamo le variabili settate dall'utente in config.json
'''
with open(CONFIG_FILE, 'r') as data_file: #loads configuration
    config = json.load(data_file)
    print "Sto leggendo il file di configurazione..."
log_dir = config["access_log_location"] #path(percorso) dell'access log
filters = config["whitelist_extensions"] #tutte le pagine da loggare (php, html, asp....)
profondita_cartella = config["folder_level"] #mi restituisce la profondita' della cartella che l'utente desidera analizzare.
dove_salvare_html = config["public_html"] #cartella dove l'utente vuole pubblicare i grafici.

def check_bot(request):
    '''
    funzione che controlla se un user agent puo' essere di un bot come google o bing o crawler/spider...
    '''
    if("robots.txt" in request[0]):
        return True
    bots = ["bot","crawl","spider"] 
    if any(bot in request[6] for bot in bots): # search for substring that could represent a spider
        return True
    return False

def search_in_list(name, _list):
    '''
    method that returns the first element which has the property "name" equal to the parameter name
    '''
    for p in _list:
        if p['name'] == name:
            return p


def get_folder(url):
    '''
    if depth==1 get_folder("/atleta/profilo/index.php")==/atleta
    metodo che estrae da un URL la cartella nella quale la pagina si trova.
    '''
    token = url.split("/")
    folder = ""
    #print token
    if "." in token[-1]:
        token[-1] = ''
    if (len(token) <= profondita_cartella) and (profondita_cartella>0):
        current_depth = len(token) - 1
    else:
        current_depth = profondita_cartella
    for count in range(1,current_depth+1):
        folder += "/" + token[count]
    return folder

month_map = {'Jan': 1, 'Feb': 2, 'Mar':3, 'Apr':4, 'May':5, 'Jun':6, 'Jul':7, 
    'Aug':8,  'Sep': 9, 'Oct':10, 'Nov': 11, 'Dec': 12}

def apachetime(s):
    '''
    method that parses 10 times faster dates using slicing instead of regexs
    '''
    return [(int(s[7:11]), month_map[s[3:6]], int(s[0:2]), \
         int(s[12:14]), int(s[15:17]), int(s[18:20]))]

def leggi_log(nome_log, connection, cursor, log_file, modo):
    try:
        if modo == "gzip":
            f = gzip.open(nome_log, 'r')
        elif modo == "text":
            f = open(nome_log, 'r')
        ip_precedente = "0.0.0.0"
        pagina_richiesta_precedentemente = "0"
        for line in f: # per ogni linea del file
            compiled_line = find(pat_get, line, None) #controlla se soddisfa la regex di nome "pat"
            if compiled_line:
                compiled_line = compiled_line[0] # converte la lista di una tupla [("","","")] nella singola tupla ("","","")
                if ( any(x in compiled_line[2] for x in filters) or (compiled_line[2].endswith('/')) or (('.') not in compiled_line[2]) ): #controllo se solo file html e php oppure che finisca con "/" opure senza estensione(non prende css...)
                    request_time = apachetime(compiled_line[1])
                    #if ( not any(black in compiled_line[2] for black in black_folders ) ) and ( start_point <= request_time <= end_point ):
                    #print line
                    request_time = request_time[0] #tengo solamente la tupla
                    if not (ip_precedente == compiled_line[0] and pagina_richiesta_precedentemente == compiled_line[2][:-1]): #controllo per capire per esempio se ho /atletica.me e /atletica.me/ consecutivamente, sono la stessa pagina non effettuo l'inserimento
                        cursor.execute('''INSERT INTO get(anno, mese, giorno, ora, minuti, secondi, fuso, ip, pagina_richiesta, protocollo, dimensione, refferrer, user_agent, browser, cartella_pagina)
                            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)''', (request_time[0], request_time[1], request_time[2], \
                                request_time[3], request_time[4], request_time[5], \
                                "0", compiled_line[0], compiled_line[2], compiled_line[3], compiled_line[4], compiled_line[5], compiled_line[6], "0", get_folder(compiled_line[2]) ))
                        connection.commit()
                    ip_precedente = compiled_line[0]
                    pagina_richiesta_precedentemente = compiled_line[2]
        log_file.write(datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S') + " Ho letto con successo " + nome_log + "\n")
    except:
        log_file.write(datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S') + " Non ho letto con successo " + nome_log + "\n")

def leggi_vecchi_gz():
    '''
    Funzione che legge e inserisce in un db tutti i file .gz presenti. I .gz sono file di log compressi dei giorni/mesi precedenti.
    Crea inoltre un suo file di testo dove tiene traccia di tutti i file .gz letti.
    '''
    print "Sto leggendo i vecchi file di log..."
    if not os.path.isfile(DB_FILE):
        connection = sqlite3.connect(DB_FILE) #si connette e se non esiste crea un database
        cursor = connection.cursor() #cursore del database

        cursor.execute('''CREATE TABLE IF NOT EXISTS get 
            (anno integer, mese integer, giorno integer,
             ora integer, minuti integer, secondi integer,
             fuso text, ip text, pagina_richiesta text, protocollo text,
             dimensione integer, refferrer text, user_agent text, browser text, cartella_pagina text)''')
        connection.commit()

        if not os.path.isfile(LOG_FILE):
            with open(LOG_FILE, 'w') as log_file: # w, crea e scrive
                log_file.write(datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S') + " File di log creato\n")

        path = log_dir.rsplit('/',1)[0] + "/access.log.*.gz" #e' una stringa che identifica tutti i file .gz
        files = glob.glob(path) #crea un array di tutti i file .gz presenti
        with open(LOG_FILE, 'a') as log_file: # a, appende a file gia' esistente

            for name in files: #per ogni file .gz
                leggi_log(name, connection, cursor, log_file, "gzip")
               
            connection.close()

def leggi_piu_recente_gz():
    '''
    Funzione che legge e inserisce in un db l'access log piu' recente appena ruotato.
    '''
    try:
        if os.path.isfile(DB_FILE):
            connection = sqlite3.connect(DB_FILE) #connesione al database
            cursor = connection.cursor() #cursore del database

            path = log_dir.rsplit('/',1)[0] + "/access.log.1" #legge l'ultimo log ruotato
            files = glob.glob(path) #crea un array di tutti i file .gz presenti
            with open(LOG_FILE, 'a') as log_file:
                leggi_log(files[0], connection, cursor, log_file, "text")
                connection.close()
    except: 
        print "Non sono riuscito a leggere il file: ", sys.exc_info()[0]

def conta_ip_giornalieri_csv():
    '''
    funzione che legge dal database (tabella get) il numero di visitatori giornalieri tramite ip e li salva in un file .csv per velocita' di lettura
    '''
    try:
        print "Sto creando un file csv"
        if not os.path.exists(dove_salvare_html + "/csv"):
                os.makedirs(dove_salvare_html + "/csv")
        if os.path.isfile(DB_FILE):
            connection = sqlite3.connect(DB_FILE) #connesione al database
            cursor = connection.cursor() #cursore del database
            cursor.execute("SELECT anno, mese, giorno, COUNT (DISTINCT ip) AS visitatori FROM get \
                GROUP BY anno, mese, giorno")
            with open(CSV_VISITATORI_FILE, "wb") as csv_file:
                os.chmod(CSV_VISITATORI_FILE,0747)
                csv_writer = csv.writer(csv_file)
                csv_writer.writerow([i[0] for i in cursor.description]) #scrive solo intestazioni
                csv_writer.writerows(cursor) #scrive il resto del file
        with open(LOG_FILE, 'a') as log_file:
            log_file.write(datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S') + " Ho creato un file csv che mostra i visitatori totali del giorno")

    except Exception, e:
        print "Non sono riuscito a creare il file CSV: ", sys.exc_info()[0]
        with open(LOG_FILE, 'a') as log_file:
            log_file.write(datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S') + " Non ho creato un file csv che mostra i visitatori totali del giorno")
        raise e

def aggiorna_ip_giornalieri_csv():
    '''
    funzione che legge e aggiorna dal database (tabella get) il numero di visitatori giornalieri tramite ip e li salva in un file .csv per velocita' di lettura
    '''
    try:
        if os.path.isfile(CSV_VISITATORI_FILE):
            if os.path.isfile(DB_FILE):
                connection = sqlite3.connect(DB_FILE) #connesione al database
                cursor = connection.cursor() #cursore del database
                cursor.execute("SELECT anno, mese, giorno, COUNT (DISTINCT ip) AS visitatori FROM get \
                    GROUP BY anno, mese, giorno")
                print CSV_VISITATORI_FILE
                with open(CSV_VISITATORI_FILE, "wb") as csv_file:
                    csv_writer = csv.writer(csv_file)
                    csv_writer.writerow([i[0] for i in cursor.description]) #scrive solo intestazioni
                    csv_writer.writerows(cursor) #scrive il resto del file
            with open(LOG_FILE, 'a') as log_file:
                log_file.write(datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S') + " Ho aggiornato un file csv che mostra i visitatori totali del giorno")

    except Exception, e:
        print "Non sono riuscito a creare il file CSV: ", sys.exc_info()[0]
        with open(LOG_FILE, 'a') as log_file:
            log_file.write(datetime.datetime.now().strftime('%d/%m/%Y %H:%M:%S') + " Non sono riuscito ad aggiornare un file csv che mostra i visitatori totali del giorno")
        raise e


#@profile
def start_parser():
    '''
    Se e' la prima volta analizza tutti i log gz precedenti.
    Se non e' la prima volta lo script viene eseguito dopo una log rotation quindi e' possibile leggere l'ultimo log in .gz
    '''
    if not os.path.isfile(LOG_FILE): #questo viene eseguito solamente al primo avvio
        leggi_vecchi_gz()
        #conta_ip_giornalieri_csv()
    else:
        leggi_piu_recente_gz() #in realta' non si tratta di un .gz, ma di un documento di testo
        #aggiorna_ip_giornalieri_csv()

def find(pat, text, match_item):
    '''
    metodo che confronta una stringa con una regular expression (regex)
    '''
    match = re.findall(pat, text)
    if match:
        return match
    else:
        return False

def checkUrl(url):
    '''
    metodo che controlla se un url ricercato da un utente esiste veramente
    '''
    p = urlparse(url)
    conn = httplib.HTTPConnection(p.netloc)
    conn.request('HEAD', p.path)
    resp = conn.getresponse()
    return resp.status < 400 and resp.status != 302

#NOT USED
def get_entry(requests,index):
    #get requested entry with req
    requested_entries = []
    for req in requests:
        #req[2] for req file match, change to
        #data you want to count totals
        requested_entries.append(req[index])
    return requested_entries #select only one feature from all, index is the feature we want

#NOT USED
def file_occur(entry):
    #number of occurrences over requested entry with related entry
    d = {}
    for file in entry:
        d[file] = d.get(file,0)+1
    return d

if __name__ == '__main__':
    #avvia la funzione per eseguire l'intero file
    start_parser()