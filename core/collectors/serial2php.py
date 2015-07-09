#!/usr/bin/python
from lib import Socket
from lib import Gpio
from lib import PhpRequest
from lib import Lock
import sys
import time
import os
import serial

""" Main Program """

""" Variables """
timeout = 3
time_end = 0
last_data = ""
lock = False
data = ""

"""
Arguments Ex serial2php.py "/dev/ttyAMA0" 9600 "/var/www/kana" "radio" 9060
"""
serial_port = sys.argv[1]  # Get Serial Port (ex:/dev/ttyAMA0)
serial_speed = sys.argv[2]  # Get Serial speed (ex:9600)
path = sys.argv[3]  # Get the path of kana (ex /var/www/kana)
dataname = sys.argv[4]  # Data type
socket_port = sys.argv[5]  # Get Socket port (ex:9060)
Socket.Start(socket_port)


"""
Serial
"""
try:
    ser = serial.Serial(serial_port, serial_speed, timeout=1)
except:
    print "Serial connection failed"
    print "speed:" + serial_speed
    print "port:" + serial_port
    os._exit(12)
# Flush Buffer
ser.flushInput()

print "Serial:"+str(serial_port)

try:
    while True:
        data = False
        try:
            data = ser.readline().strip()
        except:
            print "Close Communication : Serial Communication failure"
            os._exit(2)

        if data:
            if data == last_data:
                lock = Lock.check(timeout, time_end)
                time_end = lock[0]
                lock = lock[1]
                print "BLOCKED------"
                print "LAST:" + str(last_data)
                print "NEW:" + str(data)
                print "BLOCKED------"
            else:
                lock = False

            if lock is False:
                last_data = data
                print "SENDING-----"
                print "Serial:"+str(data)
                PhpRequest.send(path, dataname, data)
                Socket.Send(data)
                data = False
                print "SENDING-----"

except KeyboardInterrupt:
    print "Closing Collector : Keyboard Interrupt"
    # server_socket.close()
    ser.close()
    os._exit(13)
