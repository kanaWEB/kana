#!/usr/bin/python
import serial
import sys
import threading
import time
import socket
import os
import sqlite3
import subprocess

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

def listen_socket(server_socket,ser):
	# Wait for input on socket
	while 1:
		connection, address = server_socket.accept()
		buffer_socket = connection.recv(64)
		#If buffer receive stop close serial/socket connection and exit application
		if buffer_socket == "stop":
			print "Closing collector : Web Server Interrupt"
			ser.close()
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
def send_data(response,path,dataname):
	# Execute every action related to this trigger
	cmd = "php-cgi "+path+"/data.php type="+dataname+" data="+response
	#print "command"+str(cmd)
	subprocess.call([cmd], shell=True)

""" Main Program """

"""
Arguments Ex Serial_Listener.py "/dev/ttyAMA0" 9600 9060 "/var/www/yana" "radioDevices"
"""
serial_port = sys.argv[1] #Get Serial Port (ex:/dev/ttyAMA0)
serial_speed = sys.argv[2] #Get Serial speed (ex:9600)
socket_port = sys.argv[3] #Get Socket port (ex:9060)
path = sys.argv[4] #Get the path of yana (ex /var/www/yana)
dataname = sys.argv[5]  #Get the path of database (ex /etc/yana/database.db)

"""
Serial
"""
try:
	ser = serial.Serial(serial_port,serial_speed,timeout=1)
except:
	print "Serial connection failed"
	os._exit(12)
#Flush Buffer
ser.flushInput()
response = ""
print "Serial:"+str(serial_port)

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
thread_socket = threading.Thread(target=listen_socket,args=(server_socket,ser))
thread_socket.start()

timeout = 1
time_end = 0
last_response = ""
lock = False

try:
	while True:
		try:
			response = ser.readline().strip()
		except:
			print "Close Communication : Serial Communication failure"
			os._exit(2)

		if response:
			if response == last_response:
				lock = check_lock(timeout,time_end)
				time_end = lock[0]
				lock = lock[1]
			#print "LAST:"+ str(last_response)
			#print "NEW:" + str(response)
			else:
				lock = False
			
			if lock==False:
				last_response = response
				print "Serial:"+str(response)
				thread_triggers = threading.Thread(target=send_data,args=(response,path,dataname))
				thread_triggers.start()

except KeyboardInterrupt:
	print "Closing Collector : Keyboard Interrupt"
	#server_socket.close()
	ser.close()
	os._exit(13)
