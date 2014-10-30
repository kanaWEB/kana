#!/usr/bin/python
from lib import Socket
from lib import Gpio
from lib import PhpRequest
from lib import Lock
import sys
import time
import os

"""Main"""

"""Variables"""
timeout = 1
time_end = 0
last_data = ""
lock = False
data = False

"""
Arguments
gpio2php.py 6;2;3 "/var/www/kana" "buttons" 9061
"""
buttons = sys.argv[1].split(";")
global path
path = sys.argv[2]

dataname = sys.argv[3]
socket_port = sys.argv[4]
Socket.Start(socket_port)

print "Socket Started"

for button in buttons:
	Gpio.pull_up(button)

print buttons
try: 
	while True:
		data = False
		for button in buttons:
			result = Gpio.read(button)
			if(result)=="0":
				data = button
		
		if data:
			if data == last_data:
				lock = Lock.check(timeout,time_end)
				time_end = lock[0]
				lock = lock[1]
				print "BLOCKED------"
				print "LAST:" + str(last_data)
				print "NEW:" + str(data)
				print "BLOCKED------"
			else:
				lock = False

			if lock==False:
				last_data = data
				print "SENDING-----"
				print "Gpio:"+str(data)
				PhpRequest.send(path,dataname,data)
				data = False
				print "SENDING-----"
except KeyboardInterrupt:
	Socket.Close()
	os._exit(13)