#!/usr/bin/python

"""
Collection of joomla translation files (translation lines) from one translation type
"""

import os
#import re
import getopt
import sys

from datetime import datetime

from TransFile import TransFile

HELP_MSG = """
TranslationSet supports ...
Collection of files with translation types. 
The files will be loadesd in given directory
The set

usage: TranslationSet.py -? nnn -? xxxx -? yyyy  [-h]
	-? nnn
	-?


	-h shows this message

	-1
	-2
	-3
	-4
	-5


	example:


------------------------------------
ToDo:
  * 
  * 
  * 
  * 

"""

#-------------------------------------------------------------------------------
LeaveOut_01 = False
LeaveOut_02 = False
LeaveOut_03 = False
LeaveOut_04 = False
LeaveOut_05 = False

#-------------------------------------------------------------------------------

# ================================================================================
# TranslationSet
# ================================================================================

class TranslationSet:

	""

	#---------------------------------------------
	def __init__ (self, langDirectory='', langType=''):
		print( "Init TranslationSet: ")
		print ("langDirectory: " + langDirectory)
		print ("langType: " + langType)

		# ToDo: same init in translation file
		# parameter given, init inn load
		if (langDirectory != '' and langType != ''):

			self.load (langDirectory, langType)

		else:
			self.translations = {}
			self.doubles = {}

			if (langDirectory != ''):
				self.langDirectory = langDirectory
	
			if (langType != ''):
				self.langType = langType
	


	# find all type matching files in directory
	def load (self, langDirectory='', langType=''):
		
		#return
		
		try:
			print ('*********************************************************')
			print ('load')
			print("langDirectory: " + langDirectory)
			print("langType: " + langType)
			
			print ('---------------------------------------------------------')

			self.translations = {}
			self.doubles = {}
			
			if (langDirectory == '' or langType == ''):
				print ('!!! Missing information. Can not search for language files !!!')
				return
			
			#---------------------------------------------
			# Find files of type
			#---------------------------------------------

			fileQuery = '*.' + langType




			if fileName == '' :
				fileName = self.TranslationSet

			if (os.path.isfile(fileName)):
				print ('Found fileName: ' + fileName)
				#print ('fileName: ' + fileName)

				with open(fileName, encoding="utf8") as fp:
					for cnt, line in enumerate(fp):
						#if LookupString not in line:
						#	continue
						line = line.strip()

						idx = line.find ('=')

						#if '=' not in line:
						if (idx < 0):
							continue
						
						# comment
						if (line[0] == ';'):
							continue

						transId = line[:idx].strip ()

						transText = line[idx+1:].strip ()
						#print ('transText (1): ' + transText)
						# Remove ""
						transText = transText [1:-1]
						#print ('transText (2): ' + transText)
						
						# prepared lines in file : com... = ""
						if (len(transText) < 1):
							continue


						# Key does already exist
						if (transId in self.translations):
							# Save last info
							self.doubles [transId] = self.translations [transId]

						self.translations [transId] = transText



			return


			#--------------------------------------------------------------------
			#
			#--------------------------------------------------------------------



			#--------------------------------------------------------------------
			#
			#--------------------------------------------------------------------


			#--------------------------------------------------------------------
			#
			#--------------------------------------------------------------------




		finally:
			print ('exit TranslationSet')

	def save (self, fileName='', isTest=False):
		
		return
		
		try:
			print ('*********************************************************')
			print ('save')
			
			# use class filename
			if (fileName == ''):
				fileName = self.TranslationSet
				
			print ('fileName: ' + fileName)
			
			isTest = True # ToDo: remove later
			print ('isTest: ' + str(isTest))

			print ('---------------------------------------------------------')
			
			# --------------------------------------------------------------------
			# find files
			# --------------------------------------------------------------------
			
			Files = []
			
			
			
			#--------------------------------------------------------------------
			# open file
			#--------------------------------------------------------------------

			# Do test output only
			if (isTest):
				useFileName = fileName + ".new"
			else:
				useFileName = fileName

			# todo: check for no bom 
			with open(useFileName, mode="w", encoding="utf8") as fh:

				#--------------------------------------------------------------------
				# write header
				#--------------------------------------------------------------------
	
				"""
				; en-GB (english-United Kingdom) language file for RSGallery2
				; @version $Id: en-GB.com_rsgallery2.ini 1090 2012-07-09 18:52:20Z mirjam $
				; @package RSGallery2
				; @copyright (C) 2003-2018 RSGallery2 Team
				; @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
				; @author RSGallery2 Team
				;
				; Last updated: used en-GB.com_rsgallery2.ini from SVN 1078, translated till SVN 1079
				; Save in UTF-8 without BOM (with e.g. Notepad ++)
	
				; If the language file only shows the keys in Joomla, turn on Joomla's debug system and
				; debug language (global configuration) and check for 'Parsing errors in language files'.
				; This will also show a list of 'Untranslated Strings'.
	
				; ToDo: Prevent on install writing *.ini file into \administrator\language\ and delete existing translations there
				"""
				
#				datetime.datetime.now().strftime("%Y-%m-%d %H:%M")
				
				baseName = os.path.basename(fileName)
				dateFormat = datetime.now().strftime("%Y-%m-%d")
				dateYear = datetime.now().strftime("%Y")
				
				HeaderTxt = ''
				#HeaderTxt += "; " + baseName[:5] + ' (' + baseName + ')  language file for RSGallery2 ' + u'\n'
				HeaderTxt += "; " + baseName + '  language file for RSGallery2 ' + u'\n'
				HeaderTxt += "; " + '@version ' + dateFormat + u'\n'
				HeaderTxt += "; " + '@package RSGallery2 ' + u'\n'
				HeaderTxt += "; " + '@copyright (C) 2003-' + dateYear + ' RSGallery2 Team ' + u'\n'
				HeaderTxt += "; " + '@license http://www.gnu.org/copyleft/gpl.html GNU/GPL ' + u'\n'
				HeaderTxt += "; " + '@author RSGallery2 Team ' + u'\n'
	
				fh.write (HeaderTxt)
	
				#--------------------------------------------------------------------
				# write all lines
				#--------------------------------------------------------------------
	
				idx = 0
				
				TranslLines = ''
				
				print ("Translations: " + str(len (self.translations)))
				
				#for key, value in self.translations.items():
				for key in sorted(self.translations.keys()):
					
					value = self.translations [key]
					
					# separator each 5 lines
					if (idx % 5 == 0):
						TranslLines += "" + ' ' + u'\n'

					# mark each 50 lines
					if (idx % 50 == 0):
						TranslLines += "; ------------------------------------------" + u'\n'
					
					idx += 1
					print (idx, end=', ')
				
					#print ("   " + key + " = " + value)
					TranslLines += key + ' = '  + value + u'\n'
				
				TranslLines += "" + ' ' + u'\n'
				TranslLines += "" + ' ' + u'\n'
				TranslLines += "" + ' ' + u'\n'
				
				fh.write(TranslLines)
		
				#--------------------------------------------------------------------
				#
				#--------------------------------------------------------------------
	
	
	
				#--------------------------------------------------------------------
				#
				#--------------------------------------------------------------------
	
	
	
			#--------------------------------------------------------------------
			#
			#--------------------------------------------------------------------




		finally:
			print ('exit save')

	#-------------------------------------------------------------------------------
	# ToDo: Return string instead of print
	def Text (self):
		#print ('    >>> Enter yyy: ')
		#print ('       XXX: "' + XXX + '"')

		ZZZ = ""
		
		return
		
		try:
			print ("Translations: " + str(len (self.translations)))
			for key, value in self.translations.items():
				print ("   " + key + " = " + value)

			print ("Doubles: " + str(len (self.doubles)))
			for key, value in self.doubles.items():
				print ("   " + key + " = " + value)

		except Exception as ex:
			print(ex)

#	print ('    <<< Exit yyy: ' + ZZZ)
#	return ZZZ


##-------------------------------------------------------------------------------
##
#def yyy (XXX):
#	print ('    >>> Enter yyy: ')
#	print ('       XXX: "' + XXX + '"')
#
#	ZZZ = ""
#
#	try:
#
#
#	except Exception as ex:
#		print(ex)
#
#	print ('    <<< Exit yyy: ' + ZZZ)
#	return ZZZ


##-------------------------------------------------------------------------------
##
#def yyy (XXX):
#	print ('    >>> Enter yyy: ')
#	print ('       XXX: "' + XXX + '"')
#
#	ZZZ = ""
#
#	try:
#
#
#	except Exception as ex:
#		print(ex)
#
#	print ('    <<< Exit yyy: ' + ZZZ)
#	return ZZZ

##-------------------------------------------------------------------------------
##
#def yyy (XXX):
#	print ('    >>> Enter yyy: ')
#	print ('       XXX: "' + XXX + '"')
#
#	ZZZ = ""
#
#	try:
#
#
#	except Exception as ex:
#		print(ex)
#
#	print ('    <<< Exit yyy: ' + ZZZ)
#	return ZZZ


##-------------------------------------------------------------------------------

def dummyFunction():
	print ('    >>> Enter dummyFunction: ')
	#print ('       XXX: "' + XXX + '"')


##-------------------------------------------------------------------------------

def Wait4Key():
	try:
		input("Press enter to continue")
	except SyntaxError:
		pass


def testFile(file):
	exists = os.path.isfile(file)
	if not exists:
		print ("Error: File does not exist: " + file)
	return exists

def testDir(directory):
	exists = os.path.isdir(directory)
	if not exists:
		print ("Error: Directory does not exist: " + directory)
	return exists

def print_header(start):

	print ('------------------------------------------')
	print ('Command line:', end='')
	for s in sys.argv:
		print (s, end='')

	print ('')
	print ('Start time:   ' + start.ctime())
	print ('------------------------------------------')

def print_end(start):
	now = datetime.today()
	print ('')
	print ('End time:               ' + now.ctime())
	difference = now-start
	print ('Time of run:            ', difference)
	#print ('Time of run in seconds: ', difference.total_seconds())

# ================================================================================
#   main (used from command line)
# ================================================================================

if __name__ == '__main__':
	optlist, args = getopt.getopt(sys.argv[1:], 'd:t:12345h')

	langDirectory= '..\\..\\admin\language'
	langType= 'ini'
	#langType= 'sys.ini'


	for i, j in optlist:
		if i == "-d":
			langDirectory = j
		if i == "-t":
			langType = j

		if i == "-h":
			print (HELP_MSG)
			sys.exit(0)

		if i == "-1":
			LeaveOut_01 = True
			print ("LeaveOut_01")
		if i == "-2":
			LeaveOut_02 = True
			print ("LeaveOut__02")
		if i == "-3":
			LeaveOut_03 = True
			print ("LeaveOut__03")
		if i == "-4":
			LeaveOut_04 = True
			print ("LeaveOut__04")
		if i == "-5":
			LeaveOut_05 = True
			print ("LeaveOut__05")


	#print_header(start)

	TransSet01 = TranslationSet (langDirectory, langType)
	
	TransSet01.Text ()
	#print_end(start)
	
	TransSet01.save ('', True) # save on new name


