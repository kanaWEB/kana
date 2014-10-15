#!/usr/bin/python
import serial
import sys
import threading
import time
import socket
import os
import sqlite3
import subprocess
import RPi.GPIO as io
import string

Wpi = {8:2,9:3,7:4,15:14,16:15,0:17,1:18,2:27,3:22,4:23,5:24,12:10,13:9,14:11,10:8,11:7,6:25,30:0,31:1,21:5,22:6,26:12,23:13,24:19,27:16,25:26,28:20,29:21}
gpios = sys.argv[1].split(";") 
print gpios

buttons = []
for gpio in gpios:
	button_pin_bcm = int(gpio)
	print "Buttons plugged in BCM: " + str(Wpi[button_pin_bcm]) + " Gpio:"+ str(button_pin_bcm)
	buttons.append(Wpi[button_pin_bcm])
	
for button in buttons:
	print button 
