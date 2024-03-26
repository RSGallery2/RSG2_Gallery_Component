##
# upgrade db to RSGallery2 4.5.4
###

# datetime: add fix missing default value
ALTER TABLE #__rsgallery2_comments ALTER `datetime` SET DEFAULT '0000-00-00 00:00:00';
