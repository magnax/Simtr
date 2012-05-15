#!/usr/bin/env python
import sys
import time
from datetime import datetime

from daemon import Daemon

#files:
simtr_dir = '/usr/local/lib/simtr/'
counter_file =  simtr_dir + 'counter'
time_file = simtr_dir + 'ctime'
pid_file = simtr_dir + '.d.py.pid'

class MyDaemon(Daemon):
  def say(self):
    
    try:
      p = file(self.pidfile)
    except IOError:
      p = None
    
    if not p:
      print 'Error: daemon not running!! last time:'
    print self.get()

    return 1
    
  def get(self):
    pf = file(counter_file, 'r')
    ls_czas = long(pf.read()) + 1
    pf.close()
    return ls_czas
    
  def run(self):
    while True:
      pf = file(time_file,'r')
      czas = pf.read()
      pf.close()
      now = str(datetime.now())[0:19]
      if now != czas:
        ls_czas = self.get()
        #pf.close()
        pf = file(counter_file, 'w')
        pf.write(str(ls_czas))
        pf.close()
        pf = file(time_file, 'w')
        pf.write(str(now))
        pf.close()
      time.sleep(0.1)

if __name__ == "__main__":
  daemon = MyDaemon(pid_file)
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