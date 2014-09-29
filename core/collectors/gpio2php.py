#!/usr/bin/python
import serial
import sys
import threading
import time
import socket
import os
import sqlite3
import subprocess
import string

""" Functions """

"""
Socket (Socket are used to send commands with PHP)
"""
def start_socket(port):
	#  Create and open the socket
	server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	server_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1) #Avoid Address is already used error
	server_socket.bind(("0.0.0.0",int(port))) #The socket is only accessible from localhost
	server_socket.listen(4) #Only one connection is possible to the socket
	return server_socket

def listen_socket(server_socket):
	# Wait for input on socket
	while 1:
		connection, address = server_socket.accept()
		buffer_socket = connection.recv(64)
		#If buffer receive stop close serial/socket connection and exit application
		if buffer_socket == "stop":
			print "Closing collector : Web Server Interrupt"
			server_socket.close()
			os._exit(0)
		if buffer_socket == "ping":
			try:
				server_socket.sendall("socket:pong\n")
			except socket.error, msg:
				print msg
		print buffer_socket

	print "ERROR:Socket has been closed"
	os._exit(1)


def check_lock(timeout,time_end):
	if time_end == 0:
		time_end = time.time() + float(timeout)
		lock = False
	else:
		print "Timer:Lock - Time left:"+str(time_end - time.time())
		if time.time() < time_end:
			lock = True
			#print "Timer:Locked"
		else:
			time_end = time.time() + float(timeout)
			lock = False
			#print "Timer:Unlocked"
	return (time_end,lock)

"""
Web Request
"""
def send_data(response,path,dataname,timestamp):
	# Execute every action related to this trigger
	cmd = "php-cgi "+path+"/data.php type="+dataname+" data="+str(response)+" time="+str(timestamp)
	#print "command"+str(cmd)
	os.system(cmd)

""" Main Program """

#if os.geteuid() != 0:
#    exit("You need to have root privileges to run this script.")


"""
Arguments Ex Serial_Listener.py 6;2;3 "/var/www/kana" "button" 9061
"""
gpios = sys.argv[1].split(";") #Get Serial Port (ex:/dev/ttyAMA0)
socket_port = sys.argv[4]
path = sys.argv[2] #Get the path of yana (ex /var/www/yana)
dataname = sys.argv[3]  #Get the path of database (ex /etc/yana/database.db)

#Socket

#Start Socket (for php commands)
try:
	server_socket = start_socket(socket_port)
	print "Socket:" + str(socket_port)
except:
	print "Socket:Error Socket not closed gracefully"
	print "Killing Socket"
	print "--------------"
	cmd = "fuser -kn tcp "+str(socket_port)
	subprocess.call([cmd],shell=True)
	print "---------"
	server_socket = start_socket(socket_port)
	print "Socket:" + str(socket_port)

#Listen to command from socket
thread_socket = threading.Thread(target=listen_socket,args=(server_socket,))
thread_socket.start()

"""
GPIO
"""
#Bcm = {8:2,9:3,7:4,15:14,16:15,0:17,1:18,2:27,3:22,4:23,5:24,12:10,13:9,14:11,10:8,11:7,6:25}
#Wpi = {2:8,3:9,4:7,14:15,15:16,17:0,18:1,27:2,22:3,23:4,24:5,10:12,9:13,11:14,8:10,7:11,25:6}

buttons = []
for gpio in gpios:
	#button_pin_wpi = int(gpio)
	#button_pin_bcm = Bcm[button_pin_wpi]
	#print "Buttons plugged in Broadcom: " + str(button_pin_bcm) + " Wiring Pi:"+ str(button_pin_wpi)
	#buttons.append(button_pin_bcm)
	buttons.append(int(gpio))
	os.system("/usr/local/bin/gpio mode " + gpio + " in")
	os.system("/usr/local/bin/gpio mode " + gpio + " up")
	#io.setup(button_pin_bcm, io.IN,pull_up_down=io.PUD_UP)

print buttons
timeout = 3
time_end = 0
last_response = ""
lock = False
response = False
try:
	while True:
		for button in buttons:
			gpio_cmd = "/usr/local/bin/gpio read "+ str(button)
			result = subprocess.check_output(gpio_cmd, shell=True)
			result = result.strip()
			if(result)=="0":
				response = button
				time.sleep(0.5)
				
		if response:
			if response == last_response:
				lock = check_lock(timeout,time_end)
				time_end = lock[0]
				lock = lock[1]
				print "LAST:"+ str(last_response)
				print "NEW:" + str(response)
			else:
				lock = False
			
			if lock==False:
				last_response = response
				print "Gpio:"+str(response)
				timestamp = int(time.time())
				thread_triggers = threading.Thread(target=send_data,args=(response,path,dataname,timestamp))
				thread_triggers.start()
				response = False

except KeyboardInterrupt:
	print "Closing Collector : Keyboard Interrupt"
	#server_socket.close()
	os._exit(13)
