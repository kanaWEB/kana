#!/usr/bin/python
from lib import Socket
from lib import Gpio
from lib import PhpRequest
from lib import Lock
import sys
import time
import os
import serial
import json
import string

""" Main Program """

""" Variables """
timeout = 3
time_end = 0
last_data = ""
lock = False
data = ""

"""
Arguments Ex serial2jsonphp.py "/dev/ttyAMA0" 9600 "/var/www/kana" "radio" 9060
"""
serial_port = sys.argv[1]  # Get Serial Port (ex:/dev/ttyAMA0)
serial_speed = sys.argv[2]  # Get Serial speed (ex:9600)
path = sys.argv[3]  # Get the path of kana (ex /var/www/kana)
# dataname = sys.argv[4]  # Data type
socket_port = sys.argv[5]  # Get Socket port (ex:9060)
Socket.Start(socket_port)


"""
Json (to move to JsonParser)
"""


def is_json(jsonLine):
    try:
        json_object = json.loads(jsonLine)
    except ValueError, e:
        return False
    return True

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
                message = Socket.Listen()
        # print message
        except:
            print "Close Communication : Serial Communication failure"
            os._exit(2)

        if message is not False:
            # print "Founded something to tell to the arduino"
            # print message
            ser.write(message + "\n")

        if data:
            if is_json(data):
                json_object = json.loads(data)
                # print json_object
                if 'data' in json_object:
                    dataRaw = json_object['data']
                    if dataRaw == last_data:
                        lock = Lock.check(timeout, time_end)
                        time_end = lock[0]
                        lock = lock[1]
                        print "BLOCKED------"
                        print "LAST:" + str(last_data)
                        print "NEW:" + str(dataRaw)
                        print "BLOCKED------"
                    else:
                        lock = False

                    if lock is False:
                        last_data = dataRaw
                        print "SENDING-----"
                        print "Serial:"+str(dataRaw)
                        PhpRequest.send(path, dataRaw)
                        Socket.Send(dataRaw)
                        data = False
                        print "SENDING-----"
except KeyboardInterrupt:
    print "Closing Collector : Keyboard Interrupt"
    # server_socket.close()
    ser.close()
    os._exit(13)
