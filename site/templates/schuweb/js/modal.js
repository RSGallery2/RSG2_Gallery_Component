window.addEvent('domready', function () {
    this.frame		= window.frames['imageframe'];
    var SchuWebGallery = this.SchuWebGallery = {
        setFolder:function (folder, asset, author) {
            //this.showMessage('Loading');

            for (var i = 0; i < this.folderlist.length; i++) {
                if (folder == this.folderlist.options[i].value) {
                    this.folderlist.selectedIndex = i;
                    break;
                }
            }
            this.frame.location.href = 'index.php?option=com_media&view=imagesList&tmpl=component&folder=' + folder + '&asset=' + asset + '&author=' + author;
        }
    }
});