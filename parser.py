#!/usr/bin/env python

import re, sys, json, csv, socket, time, datetime, os, httplib, glob, errno, gzip
from urlparse import urlparse
from dateutil import parser
from collections import OrderedDict

with open('config.json') as data_file: #loads configuration
    config = json.load(data_file)
log_dir = config["access_log_location"]

#@profile
def get_user_story():
    requests = get_requests() #list with all lines of the access log
        
def check_bot(request):
    '''
    method that checks if a UA string could be a spider
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
    method that cut an URL on a specific folder depth
    if depth==1 get_folder("/atleta/profilo/index.php")==/atleta
    '''
    token = url.split("/")
    folder = ""
    #print token
    if "." in token[-1]:
        token[-1] = ''
    if (len(token) <= depth) and (depth>0):
        current_depth = len(token) - 1
    else:
        current_depth = depth
    for count in range(1,current_depth+1):
        folder += "/" + token[count]
    return folder

month_map = {'Jan': 1, 'Feb': 2, 'Mar':3, 'Apr':4, 'May':5, 'Jun':6, 'Jul':7, 
    'Aug':8,  'Sep': 9, 'Oct':10, 'Nov': 11, 'Dec': 12}

def apachetime(s):
    '''
    method that parses 10 times faster dates using slicing instead of regexs
    '''
    return datetime.datetime(int(s[7:11]), month_map[s[3:6]], int(s[0:2]), \
         int(s[12:14]), int(s[15:17]), int(s[18:20]))

#@profile
def get_requests():
    '''
    method that creates a list containing all requests done on a site
    '''
    pat = (r''
            '(\d+.\d+.\d+.\d+)\s-\s-\s' #IP address: 0
            '\[(.+)\]\s' #datetime: 1
            '"GET\s(.+)\s\w+/.+"\s' #requested file: 2
            '(\d+)\s' #status: 3
            '(\d+)\s' #bandwidth: 4
            '"(.+)"\s' #referrer: 5
            '"(.+)"' #user agent: 6
        )
    log_gz_number = 1
    path = log_dir.rsplit('/',1)[0] + "/access.log.*.gz"
    print path
    files = glob.glob(path)
    print files
    for name in files:
        print name
        try:
            f = gzip.open(name, 'r') 
            for line in f:
                #print line
                '''compiled_line = find(pat, line, None)
                if compiled_line:
                    compiled_line = compiled_line[0] # convert our [("","","")] to ("","","")
                    if ( any(x in compiled_line[2] for x in filters) or (compiled_line[2].endswith('/')) or (('.') not in compiled_line[2]) ):
                        request_time = apachetime(compiled_line[1])
                        #request_time_ = time.strptime(compiled_line[1][:-6], '%d/%b/%Y:%H:%M:%S')
                        if ( not any(black in compiled_line[2] for black in black_folders ) ) and ( start_point <= request_time <= end_point ):
                            requests.append(compiled_line)
                        if request_time > end_point:
                            return requests'''
        except IOError as exc:
            if exc.errno != errno.EISDIR:
                raise
    
    

    '''with open(log_dir, "r") as access_log_file:
        requests = []
        for line in access_log_file:
            compiled_line = find(pat, line, None)
            if compiled_line:
                compiled_line = compiled_line[0] # convert our [("","","")] to ("","","")
                if ( any(x in compiled_line[2] for x in filters) or (compiled_line[2].endswith('/')) or (('.') not in compiled_line[2]) ):
                    request_time = apachetime(compiled_line[1])
                    #request_time_ = time.strptime(compiled_line[1][:-6], '%d/%b/%Y:%H:%M:%S')
                    if ( not any(black in compiled_line[2] for black in black_folders ) ) and ( start_point <= request_time <= end_point ):
                        requests.append(compiled_line)
                    if request_time > end_point:
                        return requests
    return requests #list of all access log lines'''

def find(pat, text, match_item):
    '''
    method that parses log lines using regexs
    '''
    match = re.findall(pat, text)
    if match:
        return match
    else:
        return False

def checkUrl(url):
    '''
    method that check if a webpage exists or not
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
    #return dict of entry and total requests
    ret = get_user_story()
    

