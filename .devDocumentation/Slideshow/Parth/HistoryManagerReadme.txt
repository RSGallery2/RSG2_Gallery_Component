 * @author        Harald Kirschner <mail [at] digitarald.de>
 * @copyright    2007 Author

http://digitarald.de/project/history-manager/



HistoryManager - The Ajax Back-Button (v1.0)

HistoryManager is an unobtrusive MooTools plugin to allow history handling for multiple modules. It solves the typical “Back-Button” and “Ajax Bookmarks” problems for accessible browsing and a better usability in Rich Internet applications.

Showcases

    Multiple Widgets Complex example with multiple widgets and implementations.

Compatibility

Fully compatible with all A-Grade Browsers (Internet Explorer 6+, Opera 9, Firefox 1.5+ and Safari 2+)
Features

    Allow users to bookmark your ajaxified application in the current state
    Forward/backward history browsing for your Accordion and every other
    Save states of One-page-applications over sessions
    Change easily default options and customize the behaviour with Events

More specific: Modules can be everything from classes to closures. The modules register callbacks to allow the communication between the HistoryManager and the modules. The callbacks are fired when the history changes (from back/forward navigation or entering the site with a given location hash) or when a module confirms an internal state change. The script checks periodically for a change in the location hash, so the user can also change the hash manually. For IE an hidden iframe (with blank.html as src, which must exist!) is injected into the document body. For safari an hidden form is injected.
Getting started
Basic workflow

HistoryManager.initialize();

You can change the default options here, if you don’t call it yourself, initialize is called when the first module is registered.

Inside your class (taken from Pagination class in the example), this looks complicated the first time but is easy to use:

this.history = HistoryManager.register( // arguments are (key, defaults, onMatch, onGenerate, regexp, options)
// the unique key of the registered module
this.historyKey,
// Array with default values, here index 0 is the current page index
[1],
// onMatch, callback when the state changed
function(values) {
    this.to(parseInt(values[0]));
}.bind(this),
// onGenerate, callback that returns the string for the hash
function(values) {
    return [this.historyKey, '(', values[0], ')'].join('');
}.bind(this),
// RegExp for the hash value, works
this.historyKey + '\\((\\d+)\\)');

this.history is now an object with these functions: setValues, setValue, generate and unregister. There are also default values for default, onMatch, onGenerate and the regexp argument, take a look in the documented source.

This is called when the state in the class changed:

this.history.setValue(0, this.page);
// is the same thing as
HistoryManager.setValue(this.historyKey, 0, this.page);
// or, using setValues
HistoryManager.setValues(this.historyKey, [0]);

Index 0 is the page index, setValue updates now the location hash with the new state.

After registering all modules, call:

HistoryManager.start();

This starts the periodical check for the location hash and calls the affected modules when the location hash already contains state information.

For more information take a look at the example code and the documented source!
Sites using it

    JonDesign’s SmoothGallery - Included in Version 1.2

Requirements
MooTools JavaScript Framework 1.1

Download MooTools 1.11 with at least these modules:

    Class.Extras
    Hash
    Element
    Window.DomReady (facultative)

Download

    HistoryManager.js

Changelog

1.0 rc2 (2007-05-31)

        FIX: Added check for undefined modules in setValue/setValues
        CHG: Cleaned up code a little bit

1.0 rc1 (2007-05-23)

    -Initial official Release (still experimental!)

License

All files are © 2008-2009 by Harald Kirschner and available under the MIT License.
Contact & Discussion

For asking questions, requesting features, filing bugs or contributing other thoughts on this project use the support forum below. Before posting a new question, read through the documentation and the contributed notes for an answer. For problem reports make sure to add enough details like browser, version and a link or the relevant code.

You can contact me for non-support related notes.
Professional Support

I am available for hire if you look for professional and swift help. I can come to your rescue for installation, customization or extending the existing source for your needs.
Keywords:

    MooTools History Ajax Backbutton HistoryManager Hash 

Share it: Stumble it! • Digg This! • del.icio.us (No Posts)
discussion by DISQUS
No Comments