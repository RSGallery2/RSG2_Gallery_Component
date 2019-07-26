#!/usr/bin/python

import os
#import re
import getopt
import sys
import time
#from os import listdir
#from os.path import isfile, join

#from datetime import *
from datetime import datetime, timedelta, date

import webbrowser






def openHtmlPage (pageName):
	global ie

	print ('\tPage:' + pageName)
	# For later use
	HtmlPages.append (pageName)

	webbrowser.open_new_tab(pageName)
#	time.sleep(3)
#	time.sleep(1)

	return








#-------------------------------------------------------------------------------
def print_header(start):
	print ('====================================================================================================')
	print ('Command line:',)
	for s in sys.argv:
		print (s,)
	print ('')
	print ('--- Starttime:  ' + start.ctime())
	print (' ')
	print ('====================================================================================================')

#-------------------------------------------------------------------------------
def print_tail_end(start):
	now = datetime.today()
	print ('')
	print ('End time:  ' + now.ctime())
	difference = now-start
	print ('Time of run: ', difference)

#-------------------------------------------------------------------------------
if __name__ == '__main__':
	optlist, args = getopt.getopt(sys.argv[1:], 'i:p:t:12345h')
#	dstPath = ''
#	versionId  = ''
#	versionHeaderText = ''


	for i, j in optlist:
#		if i == "-p":
#			dstPath = j
#		if i == "-i":
#			versionId = j
#		if i == "-t":
#			versionHeaderText = j

		if i == "-1":
			LeaveOutStartExcelFiles = True
		if i == "-2":
			LeaveOutStartSap = True
		if i == "-3":
			LeaveOutStartOutlook = True
		if i == "-4":
			LeaveOutStartInternetExplorer = True
		if i == "-5":
			LeaveOutStartMenu = True
		#if i == "-6":
		#	LeaveOutStartMenu = True

		if i == "-h":
			print (HELP_MSG)
			sys.exit(0)

#	if versionId == '' :
#		versionId = os.getenv('BUILD_ID')
#	#else:
#	# os.environ['BUILD_ID'] = versionId

	start = datetime.today()

	try:
		print_header(start)

		openHtmlPage('http://127.0.0.1/Joomla3xNextRelease/administrator/index.php?option=com_rsgallery2')

		print_tail_end(start)

	except:
		print ('WtLoginCalls main failed')
