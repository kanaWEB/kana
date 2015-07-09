#!/usr/bin/python
from lib import Socket
from lib import PhpRequest
from lib import Ping
import sys
import time
import os

"""Main"""

"""Variables"""
sleep_between_ping = 3
last_data = ""
lock = False
data = False

"""
Arguments
ping2php.py "192.168.0.10;192.168.0.11" "/var/www/" "computers" 9062
"""
ips = sys.argv[1].split(";")
global path
path = sys.argv[2]

dataname = sys.argv[3]
socket_port = sys.argv[4]
Socket.Start(socket_port)
print "Socket Started"

ip_states = Ping.all(ips)

try:
    while True:
        time.sleep(sleep_between_ping)

        for i, ip_state in enumerate(ip_states):
            state, result = Ping.checkState(ip_state)
            ip_states[i] = state
            if(result):
                data = state["ip"]
                if state["state"]:
                    state = "on"
                else:
                    state = "off"
                print "SENDING-----"
                print "IP:" + data + " " + state
                PhpRequest.send(path, dataname, data, state)
                Socket.Send(data+";"+state)
                print "SENDING-----"

except KeyboardInterrupt:
    Socket.Close()
    os._exit(13)
