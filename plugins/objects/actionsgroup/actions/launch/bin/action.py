#!/usr/bin/python
import subprocess
import sys
import string
import os
import time

for arg in sys.argv:
	php_args = string.split(arg,";")
	nb_args = len(php_args)

	if nb_args == 5:
		object = php_args[0]
		id = php_args[1]
		action = php_args[2]
		action_nb = php_args[3]
		state = php_args[4]
		print "php-cgi "+ "/var/www/kana/actions.php type=action object="+object+" id="+id+" action="+action+" action_nb="+action_nb+" state="+state
		cmd = "php-cgi "+ "actions.php type=action object="+object+" id="+id+" action="+action+" action_nb="+action_nb+" state="+state
		#cmd_debug = "php-cgi "+path+"/action.php action="+radiocode[2]
		subprocess.call([cmd], shell=True)
	elif nb_args == 2:
		if php_args[0] == "wait_s":
			print "Waiting for "+php_args[1]+" seconds"
			time.sleep(int(php_args[1]))
		if php_args[0] == "wait_m":
			time_to_wait = float(php_args[1])*60
			print "Waiting for "+ str(time_to_wait) +" seconds"
			time.sleep(time_to_wait)

