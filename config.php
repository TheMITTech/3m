<?php
###############################################################################
# The Tech's Masthead and Maillist Maintenance System (3M)
#  (or Mast Maintenance and Mailing List System as it was originally 
#  envisioned)
#
# Author: Zachary Ozer ’07 <zach@theozer.com>
# Modifications by: Ricardo Ramirez <rram@the-tech.mit.edu>
# 
# $Id$
#
# Versions:
# 1.0 - Proof of concept. Shat out a mast, but not much else.
# 1.1 - Minor bug fixes, like sanity checks and string escaping.
# 1.9 - Rewrite major portions so it doesn't look like a ITT Tech student 
#       wrote it
###############################################################################

require_once 'MDB2.php';

### Database constants
$db_host = 'localhost';
$db_user = "3m";
$db_pass = "pleaseChangeMeS00n";
$db_name = "3m";

#$db_user = 'developer';
#$db_pass = 'devpass';
#$db_name = '3m_dev';

### Class year constants
$min_class_year = 1881;
$max_class_year = 2038; # If 3m isn't redone before Unix time rolls, FUCK YOU!
# For special people. Numbers map to relative position against other years
$class_year_map = array(0 => 'G', 9998 => 'CME', 9999 => '');
