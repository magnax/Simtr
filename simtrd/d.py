#!/usr/bin/env python
import sys, time
from datetime import datetime

from daemon import Daemon

from socket import *

# Set the socket parameters
host = "localhost"
port = 21567
buf = 1024
addr = (host,port)

class MyDaemon(Daemon):
  def say(self):
    # Create socket and bind to address
    UDPSock = socket(AF_INET,SOCK_DGRAM)
    UDPSock.bind(addr)
    
    try:
      p = file(self.pidfile)
    except IOError:
      p = None
    
    if not p:
      print 'Error: daemon not running!! last time:'
      print self.get()
    else:
      UDPSock.sendto("asdfg", addr)
      print self.get()
    UDPSock.close()

    return 1
    
  def get(self):
    pf = file('/home/mn/licznik.txt', 'r')
    ls_czas = long(pf.read()) + 1
    pf.close()
    return ls_czas
    
  def run(self):
    while True:
      pf = file('/home/mn/czas.txt','r')
      czas = pf.read()
      pf.close()
      now = str(datetime.now())[0:19]
      if now != czas:
        #pf = file('/home/mn1/licznik.txt', 'r')
        ls_czas = self.get() #long(pf.read()) + 1
        #pf.close()
        pf = file('/home/mn/licznik.txt', 'w')
        pf.write(str(ls_czas))
        pf.close()
        pf = file('/home/mn/czas.txt', 'w')
        pf.write(str(now))
        pf.close()
      time.sleep(0.1)

if __name__ == "__main__":
  daemon = MyDaemon('/home/mn/tmp/daemon-example.pid')
  if len(sys.argv) == 2:
    if 'start' == sys.argv[1]:
      daemon.start()
    elif 'stop' == sys.argv[1]:
      daemon.stop()
    elif 'restart' == sys.argv[1]:
      daemon.restart()
    elif 'say' == sys.argv[1]:
      daemon.say()
    else:
      print "Unknown command"
      sys.exit(2)
    sys.exit(0)
  else:
    print "usage: %s start|stop|restart" % sys.argv[0]
    sys.exit(2)