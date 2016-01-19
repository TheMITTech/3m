'''
Updates tech mailing lists, per the emailrules in the databases. 

Adds user -- and sends an email to notificationlist -- when the rules
call for the person to be added to a particular mailing list but this was not the
case last time this script was run. 

Mailing list names in the database may be the concatenation of multiple names,
separated by semicolons. Mailing lists are assumed to be tech mailman lists and
do not require the @tech.mit.edu portion of the address. 
The individual addresses may not contain semicolons, nor may they contain colons. 
In future iterations, the prefix "moira:" might indicate a Moira list. 

Notification addresses may be semicolon-separated lists of addresses. If the 
"@tech.mit.edu" portion of the address is not included, it is assumed. 
Other domains (e.g., "@mit.edu" may be specified. 
'''
import os
import sys
sys.path.insert(0, '/srv/www/internal/3m/feature-dev/3m')
import db_constants
import MySQLdb
import smtplib
import StringIO
import subprocess
from email.mime.text import MIMEText

default_domain = "the-tech.mit.edu"
db_server = "localhost"
db_name = db_constants.db_name
db_user = db_constants.db_user
db_pass = db_constants.db_pass

db = MySQLdb.connect(db_server, db_user, db_pass, db_name)

class Rule:
    def __init__(self, (dept, position, addlist, notificationlist)):
        self.dept = dept
        self.position = position
        self.addlist = addlist
        self.notificationlist = notificationlist
    def all_resulting_additions(self, (dept, position, username)):
        if (self.dept == dept) and (self.position == "*" or (self.position == position)):
            for mailinglist in self.addlist.split(";"):
                mailinglist = mailinglist.strip()
                yield (mailinglist, username, self.notificationlist)
    def __str__(self):
        return str((self.dept, self.position, self.addlist, self.notificationlist))
    def __repr__(self):
        return str(self)
    
def get_all_users():
    sql_query = "SELECT dept, position, athena_username FROM staff"
    cursor = db.cursor()
    cursor.execute(sql_query)
    return cursor.fetchall()
    
def get_all_rules():
    sql_query = "SELECT dept, position, addlist, notificationlist FROM emailrules"
    cursor = db.cursor()
    cursor.execute(sql_query)
    return [Rule(row) for row in cursor.fetchall()]

def update_last_time_records(this_time):
    sql_query = "DELETE FROM previousemailupdate"
    cursor = db.cursor()
    cursor.execute(sql_query)
    sql_query = "INSERT INTO previousemailupdate (addlist, athena_username) VALUES (%s, %s)"
    cursor.executemany(sql_query, ((entry[0], entry[1]) for entry in this_time))
    
def get_last_update_info():
    '''
    Fetch users who should have been on the lists last time
    the lists were updated by the script. 

    The string "username" is in the set result["listname"] if and only
    if, per the rules at the time, "username" should be a member of "listname"
    last time that the lists were updated by this script. 
    '''
    sql_query = "SELECT addlist, athena_username FROM previousemailupdate"
    cursor = db.cursor()
    cursor.execute(sql_query)
    out = {}
    for row in cursor.fetchall():
        mailing_list, username = row
        if mailing_list not in out:
            out[mailing_list] = set([])
        out[mailing_list].add(username)
    return out

def summarize(last_time, this_time):
    '''
    last_time: A dictionary in the format returned by get_last_update_info
    this_time: A list with records of the form (list, username, notificationlist)
    returns:
    -A dictionary of listnames mapping to sets of usernames to be added. 
    -A dictionary of notificationlists that map to dictionaries, where the keys
     are lists they monitor and the values sets of usernames being added to the list. 
 
    In all cases here, mailinglists must be single lists. 
    Mailing lists may be semicolon-separated values in the input, but will
    be broken down into single lists in the output. 
    '''
    to_be_added = {}
    to_be_emailed = {}
    for record in this_time:
        mailing_list, username, notificationlist = record
        if (mailing_list in last_time) and (username in last_time[mailing_list]):
            continue
        if mailing_list != "":
            if mailing_list not in to_be_added:
                to_be_added[mailing_list] = set([])
            to_be_added[mailing_list].add(username)
        for notificationaddress in notificationlist.split(";"):
            notificationaddress = notificationaddress.strip()
            if notificationaddress == "":
                continue
            if notificationaddress not in to_be_emailed:
                to_be_emailed[notificationaddress] = {}
            if mailing_list not in to_be_emailed[notificationaddress]:
                to_be_emailed[notificationaddress][mailing_list] = set([])
            # OBSERVE the schema on the following line... :)
            to_be_emailed[notificationaddress][mailing_list].add(username)
    return to_be_added, to_be_emailed

def send_email(to=None, sender=None, subject="", body=""):
    assert(to is not None)
    assert(sender is not None)
    msg = MIMEText(body)
    msg['Subject'] = subject
    msg['From'] = sender
    msg['To'] = to
    s = smtplib.SMTP('localhost')
    s.sendmail(sender, [to], msg.as_string())
    return
    
def send_notification(notificationaddress, addresses_added):
    notificationaddress = notificationaddress.strip()
    if notificationaddress == "":
        return
    pieces = notificationaddress.split("@")
    assert(len(pieces) <= 2)
    if len(pieces) == 1:
        notificationaddress = pieces[0] + "@" + default_domain
    lines = ["Note the following additions for mailing list you apparently care about:", ""]
    for mailinglist, subscribers in addresses_added.items():
        lines.append(mailinglist)
        for subscriber in subscribers:
            lines.append("   " + subscriber)
        lines.append("")
    send_email(to=notificationaddress, sender="general@the-tech.mit.edu", subject="TT Email List Update", body="\n".join(lines))
    
def add_to_mailinglist(listname, new_subscribers):
    def add_domain(address):
        if not "@" in address:
            return address.strip() + "@mit.edu"
        else:
            return address.strip()
    new_subscribers = map(add_domain, new_subscribers)
    command = ["/usr/lib/mailman/bin/add_members", "-r", "-", "--welcome-msg=no", listname]
    ps = subprocess.Popen(command, stdin=subprocess.PIPE, stderr=os.devnull, stdout=os.devnull, shell=False)
    ps.communicate("\n".join(new_subscribers) + "\n")

last_time = get_last_update_info()
users = get_all_users()
rules = get_all_rules()
this_time = []
for rule in rules:
    for user in users:
        this_time.extend(rule.all_resulting_additions(user))
additions, notifications = summarize(last_time, this_time)

for notificationaddress, addresses_added in notifications.items():
    send_notification(notificationaddress, addresses_added)
for mailing_list, subscribers in additions.items():
    add_to_mailinglist(mailing_list, subscribers)
update_last_time_records(this_time)
