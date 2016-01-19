'''
Fetch the $db_user, $db_pass, and $db_name PHP
strings from config.php. 

Place them in the global namespace as db_user, db_pass,
and db_name, respectively. 
'''

def run():
    for line in open("config.php", "r"):
        line = line.split("#")[0].strip()
        if len(line) == 0 or line.count("=") != 1:
            continue;
        left, right = line.split("=")
        right = right.strip().split(";")[0].strip()
        if not ((right[0] == "'" and right[-1] == "'") or (right[0] == '"' and right[-1] == '"')):
            continue
        right = right[1:-1]
        left = left.strip()
        if left[0] != "$":
            continue
        left = left[1:]
        if left == "db_user":
            db_user = right
        elif left == "db_pass":
            db_pass = right
        elif left == "db_name":
            db_name = right
    return db_user, db_pass, db_name

db_user, db_pass, db_name = run()
