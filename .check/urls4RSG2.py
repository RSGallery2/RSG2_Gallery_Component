#!/usr/bin/python

HELP_MSG = """
	Prepares several URLs list to check visibly the results on different browser 

"""


class urls4RSG2:
	"""A container for URL lists used by function tests of RSG2"""
	
	def __init__(self):
		
#		self.Temp = "/temp"
		self.backend_all = [
			'/administrator/index.php?option=com_rsgallery2',
			'/administrator/index.php?option=com_rsgallery2&view=config&layout=edit',
			'/administrator/index.php?option=com_rsgallery2&view=upload',  # ToDo: Batch ...
			'',
			'',
			'/administrator/index.php?option=com_rsgallery2&view=galleries',
			'/administrator/index.php?option=com_rsgallery2&view=gallery&layout=edit&id=1',
			'',
			'',
			'/administrator/index.php?option=com_rsgallery2&view=images',  # ToDo: Batch ...
			'/administrator/index.php?option=com_rsgallery2&view=image&layout=edit&id=1',
			'',
			'',
			'/administrator/index.php?option=com_rsgallery2&view=maintenance',
			'/administrator/index.php?option=com_rsgallery2&view=maintConsolidateDB',
			'/administrator/index.php?option=com_rsgallery2&view=maintRegenerateImages',
			'/administrator/index.php?option=com_rsgallery2&view=config&layout=RawEdit',
			'/administrator/index.php?option=com_rsgallery2&view=maintRegenerateImages',
			'/administrator/index.php?option=com_rsgallery2&view=maintslideshows',
			'',
			'',
			'/administrator/index.php?option=com_rsgallery2&view=maintslideshows&maintain_slideshow=slideshow_description',
			'/administrator/index.php?option=com_rsgallery2&view=maintslideshows&maintain_slideshow=slideshow_parth',
			'/administrator/index.php?option=com_rsgallery2&view=maintslideshows&maintain_slideshow=slideshow_phatfusion',
			'/administrator/index.php?option=com_rsgallery2&view=maintslideshows&maintain_slideshow=slideshowone',
			'',
			'/administrator/index.php?option=com_rsgallery2&view=maintTemplates',
			'/administrator/index.php?option=com_rsgallery2&view=mainttemplates&maintain_template=schuweb',
			'/administrator/index.php?option=com_rsgallery2&view=mainttemplates&maintain_template=semantic',
			'',
			'/administrator/index.php?option=com_rsgallery2&rsgOption=installer',
			'',
			'/administrator/index.php?option=com_rsgallery2&view=comments',
			'',
			'/administrator/index.php?option=com_rsgallery2&view=config&layout=RawView',
			'/administrator/index.php?option=com_rsgallery2&view=images&layout=images_raw',
			'/administrator/index.php?option=com_rsgallery2&view=galleries&layout=galleries_raw',
			'/administrator/index.php?option=com_rsgallery2&view=comments&layout=comments_raw',
			'',
			'/administrator/index.php?option=com_rsgallery2&view=maintRemoveInstallLeftOvers',
			'/administrator/index.php?option=com_rsgallery2&view=develop&layout=InitUpgradeMessage',
			'/administrator/index.php?option=com_rsgallery2&view=develop&layout=DebugGalleryOrder',
			'',
			'/administrator/index.php?option=com_rsgallery2&view=acl_items',
			'',
			'/administrator/index.php?option=com_rsgallery2&rsgOption=config&task=showConfig',
			'/administrator/index.php?option=com_rsgallery2&rsgOption=galleries',
			'/administrator/index.php?option=com_rsgallery2&rsgOption=images&task=view_images',
			'/administrator/index.php?option=com_rsgallery2&rsgOption=images&task=upload',
			'/administrator/index.php?option=com_rsgallery2&rsgOption=maintenance&task=consolidateDB',
			'',
			'',
			
			''
		]
		
		# Last found errors to be fixed, URL should be removed if working again
		self.error = [
			'/administrator/index.php?option=com_rsgallery2&view=maintTemplates',
			'/administrator/index.php?option=com_rsgallery2&rsgOption=config&task=showConfig',
			'/administrator/index.php?option=com_rsgallery2&view=images&layout=images_raw',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			
			''
		]
		
		self.admin = [
			'/administrator/index.php',
		]
		
		self.siteAll = [
			'/',
			'/index.php/root-galleries',
			'/index.php/single-gallery',
			'/index.php/gallery-slideshow',
			
			'/index.php/single-gallery/item/1/asInline',
			'/index.php/single-gallery/gallery/1/itemPage/1/asInline',
			'/index.php/single-gallery/gallery/1/itemPage/9/asInline',
			'//images/rsgallery/original/DSC_5504.JPG',
			
			'/index.php/plugin-displaygallery-slideshow/plugin-displaygallery-slideshow-one',
			'/index.php/plugin-displaygallery-slideshow/plugin-displaygallery-slideshow-parth',
			'/index.php/plugin-displaygallery-slideshow/plugin-displaygallery-slideshow-pathfusion',
			
			'/index.php/plugin-displayimage',
			
			'/index.php/gallery-json-xml/gallery-survey-xml',
			'/index.php/gallery-json-xml/gallery-survey-json',
			'/index.php/gallery-json-xml/gallery-xml',
			'/index.php/gallery-json-xml/gallery-json',
			'/index.php/gallery-json-xml/gallery-single-slider-xml',
			'/index.php/gallery-json-xml/gallery-single-slider-json',
			'/index.php/gallery-json-xml/gallery-slideshow-xml',
			'/index.php/gallery-json-xml/gallery-slideshow-json',
			
			'/index.php/modules-views/module-latestgalleries',
			'/index.php/modules-views/module-latestimages',
			'/index.php/modules-views/module-randomimages'
			'',
			'',
			'',
			''
		]
	
	def urlList(self, urlListName):
		
		urls = []
		
		if (urlListName == 'backend_all'):
			urls = self.backend_all

		if (urlListName == 'error'):
			urls = self.error
			
		if (urlListName == 'siteAll'):
			urls = self.siteAll
		
		if (urlListName == 'admin'):
			urls = self.admin
		
		#if (urlListName == ''):
		#	urls = self.
		#
		#if (urlListName == ''):
		#	urls = self.
		#
		#if (urlListName == ''):
		#	urls = self.
		#
		#if (urlListName == ''):
		#	urls = self.
		#
		#if (urlListName == ''):
		#	urls = self.
		#
		
		return urls
