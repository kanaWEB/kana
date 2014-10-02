#!/usr/bin/python
# use standard Python logging
import logging
logging.basicConfig(level=logging.INFO)
import gntp.notifier
import sys

if len(sys.argv) > 3:
	growl = gntp.notifier.GrowlNotifier(
		applicationName = sys.argv[2],
		notifications = ["New Updates","New Messages"],
		defaultNotifications = ["New Messages"],
	hostname = sys.argv[1], # Here enter your IP address
	password = sys.argv[2] # Here enter your growl password
	)
	growl.register()
	# Send one message
	#image = open('/var/www/tmp/image.jpg', 'rb').read()
	growl.notify(
		noteType = "New Messages",
		title = sys.argv[3],
		description = sys.argv[4],
 		#icon = image, #you can optionally define an image icon to appear with the notification
 		sticky = True,
 		priority = 1,
	)
else:
	print "You didn't provide enough argument"
	print "-----------------------------------------------"
	print "Usage: growl.py IP PASSWORD Title Description"
	print "-----------------------------------------------"