import os
import threading
import time
"""
Web Request
"""
def send_data(data,path,dataname,timestamp,state):
	if(state):
		cmd = path+"/core/collectors/bin/phpboost "+path+"/data.php type="+dataname+" data="+data.encode("utf8")+" time="+str(timestamp)+" state="+state.encode("utf8")
	else:
		# Execute every action related to this trigger
		cmd = path+"/core/collectors/bin/phpboost "+path+"/data.php type="+dataname+" data="+data.encode("utf8")+" time="+str(timestamp)
	#print "command"+str(cmd)
	os.system(cmd)

def send(path,dataname,data,state=False):
	timestamp = int(time.time())
	sending_thread = threading.Thread(target=send_data,args=(data,path,dataname,timestamp,state))
	sending_thread.start()