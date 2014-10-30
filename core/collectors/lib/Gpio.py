#!/usr/bin/python
#We use gpio from WiringPi to avoid issue with Root Right
import os
import subprocess

def pull_up(gpio):
	os.system("/usr/local/bin/gpio mode " + gpio + " in")
	os.system("/usr/local/bin/gpio mode " + gpio + " up")

def read(gpio):
	gpio_cmd = "/usr/local/bin/gpio read "+ str(gpio)
	result = subprocess.check_output(gpio_cmd, shell=True)
	result = result.strip()
	return result