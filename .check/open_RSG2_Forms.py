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
#from selenium import webdriver

# from .urls4RSG2 import *
#from .urls4RSG2 import urls4RSG2
from urls4RSG2 import urls4RSG2
#import urls4RSG2

HELP_MSG="""
usage: openAllForms.backend.py -g develop generator folder -e develop erosion commander folder -G install generator folder path -E develop erosion commander folder -f fanuc dll path -u version id   -u version id
	-j joomla base url path
	-x x

	Select Web browser and Joomla installtion ...

	
	-h shows this message
	

	
Example: python openAllForms.backend.py.py 
	-s c:\pr004\entwickl\ProfileEditor -b c:\pr004\entwickl\obj\Release path -i c:\WWM_INSTALL_MEDIA\ProfilEditorNoWWM
"""

adminLoginUrl = 'administrator/index.php'


leaveOut1 = False
leaveOut2 = False
leaveOut3 = False
leaveOut4 = False
leaveOut5 = False
leaveOut6 = False
leaveOut7 = False
leaveOut8 = False
leaveOut9 = False

def openForms(j_base_url, browserName, urlListName, bIsWait4Admin, restTime):

	print ('*********************************************************')
	print ('openForms')
	print ('\tj_base_url: ' + j_base_url)
	print ('\tbrowserName: ' + browserName)
	print ('\turlListName: ' + urlListName)
	print ('\tbIsWait4Admin: ' + str(bIsWait4Admin))
	print ('\trestTime: ' + str(restTime))

	#--- get url lists ------------------
	
##	urlLists = new urls4RSG2 ()
##	urlLists = new urls4RSG2.urls4RSG2 ()
	urlLists = urls4RSG2 ()
#
	urlList = urlLists.urlList (urlListName)
	
#	urlList = [
#		'/administrator/index.php?option=com_rsgallery2',
#		'/administrator/index.php?option=com_rsgallery2&view=config&layout=edit',
#		'/administrator/index.php?option=com_rsgallery2&view=upload',  # ToDo: Batch ...
#		''
#	]
	
	
	#--- select browser -------------------------
	
#	print ("01: Browser found")
#	# print(str(webbrowser._browsers))
#	for browserFound in webbrowser._browsers:
#		print ('\tbrowserFound: ' + browserFound)
#	print ("02")
	
	browser = getBrowserDriver(browserName)

	#--- open admin form on request -------------------------
	
	if (bIsWait4Admin):
		pass


	#--- open urls -------------------------

#	print ("11")
#	print ("urlList: " + str(urlList))
#	print ("12")
	
	for url in urlList:
		if (len(url) > 0):
			page = 'http://127.0.0.1/' + j_base_url + url
			print ('\t>>page: ' + page)
			
			browser.open_new_tab(page)
		
		if restTime > 0:
			time.sleep(restTime)

	return


def getBrowserDriver (browserName):
	
	basePath = 'C:/Program Files (x86)'
	if not os.path.isdir (basePath):
		basePath = 'C:/Program Files'

	if (browserName == 'chrome'):
		try:
			browser = webbrowser.get(basePath + "/Google/Chrome/Application/chrome.exe %s")
		except Exception as ex:
			print ('open browser ' + browserName + ' failed')
			print(ex)
			sys.exit (-11)

	if (browserName == 'firefox'):
		try:
			browser = webbrowser.get(basePath + "/Mozilla Firefox/firefox.exe %s")
		except Exception as ex:
			print ('open browser ' + browserName + ' failed')
			print(ex)
			sys.exit (-11)

	if (browserName == 'edge'):
		try:
			browser = webbrowser.get("c:/Program Files/MicrosoftWebDriver/MicrosoftWebDriver.exe %s")
		except Exception as ex:
			print ('open browser ' + browserName + ' failed')
			print(ex)
			sys.exit (-11)

	if (browserName == 'iexplorer'): #
		try:
			browser = webbrowser.get("c:/program files/internet explorer/iexplore.exe %s")
		except Exception as ex:
			print ('open browser ' + browserName + ' failed')
			print(ex)
			sys.exit (-11)

	if (browserName == 'default'): #
		try:
			#browser = webbrowser.get("windows - default")
			browser = webbrowser
		except Exception as ex:
			print ('open browser ' + browserName + ' failed')
			print(ex)
			sys.exit (-11)

	return browser

def autoRestTime ():
	
	restTime = 0

	if os.path.isdir ("c:/xampp/htdocs/Joomla3xNextRelease"):
		restTime = 3
		
	return restTime

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
	optlist, args = getopt.getopt(sys.argv[1:], 'j:b:u:r:ah')

	j_base_url = 'Joomla3xNextRelease'
	
	#browserName =  'default'
	browserName =  'chrome'
	#browserName =  'firefox'
	#browserName =  'edge'
	#browserName =  'iexplorer'
	#browserName =  'GenericBrowser'
	# chromium ?
	#browserName =  ''
	#browserName =  ''
	
	restTime = autoRestTime ()
	
	urlListName  = 'backend_all'
	bIsWait4Admin = False

	for i, j in optlist:
		if i == "-b":
			browserName = j
		if i == "-j":
			j_base_url = j
		if i == "-u":
			urlListName = j
		if i == "-a":
			bIsWait4Admin = True
		if i == "-r":
			restTime = j
#		if i == "-t":
#			versionHeaderText = j

		if i == "-1":
			leaveOut1 = False
		if i == "-2":
			leaveOut2 = False
		if i == "-3":
			leaveOut3 = False
		if i == "-4":
			leaveOut4 = False
		if i == "-5":
			leaveOut5 = False
		if i == "-6":
			leaveOut6 = False
		if i == "-7":
			leaveOut7 = False
		if i == "-8":
			leaveOut8 = False
		if i == "-9":
			leaveOut9 = False

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

		openForms(j_base_url, browserName, urlListName, bIsWait4Admin, restTime)

		print_tail_end(start)


	except Exception as ex:
		print ('open_RSG2_Forms main failed')
		print(ex)

