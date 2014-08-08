#!/usr/bin/python
# playsound.py By Sarrailh Remi
#
# This code play a sound from /sounds
# You must have install mpg321 to use it
# apt-get install mpg321
# This was inspired by http://learn.adafruit.com/playing-sounds-and-using-buttons-with-raspberry-pi/code
# playsound takes two arguments
# Ex: playsound doorbell1 5
# will play 5 times ../sounds/doorbell1.mp3

import os
import time
import sys

if len(sys.argv) == 2:
	sound = sys.argv[1]
	if len(sys.argv) == 3:
		repeat = int(sys.argv[2])
	else:
		repeat = 1
	for x in range(0,repeat):
		os.system('mpg321 -q '+sound) 
		time.sleep(1.5) #We wait 1.5seconds
else:
	print "Wrong Number of Arguments"
	print "Exemple: doorbell1 10"
