<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<head><title>CubicleSoft File Explorer Demo</title></head>
<body>
<style type="text/css">
html.embed, html.embed body { padding: 0; margin: 0; }
html.embed p { padding: 0; margin: 0; display: none; }
#filemanager { height: 50vh; max-height: 400px; position: relative; }
html.embed #filemanager { height: 100vh; }
</style>
<link rel="stylesheet" type="text/css" href="file-explorer/file-explorer.css">
<script type="text/javascript" src="file-explorer/file-explorer.js"></script>



<div id="filemanager"></div>

<script type="text/javascript">
(function() {
	// Handle iframe demo embed.
	if (window.location.href.indexOf('embed=true') > -1)  document.documentElement.classList.add('embed');

	// Back to regularly scheduled program.
	var elem = document.getElementById('filemanager');


	var FormatStr = function(format) {
		var args = Array.prototype.slice.call(arguments, 1);

		return format.replace(/{(\d+)}/g, function(match, number) {
			return (typeof args[number] != 'undefined' ? args[number] : match);
		});
	};

	

	var options = {
		// This allows drag-and-drop and cut/copy/paste to work between windows of the live demo.
		// Your application should either define the group uniquely for your application or not at all.
		group: 'demo_test_group',

		capturebrowser: true,

		initpath: [
			[ '', '(/)', { canmodify: false } ],
			[ 'data', 'Документы'],
		],

		onfocus: function(e) {
console.log('focus');
console.log(e);
		},

		onblur: function(e) {
console.log('blur');
console.log(e);
		},

		// See main documentation for the complete list of keys.
		// The only tool that won't show as a result of a handler being defined is 'item_checkboxes'.
		tools: {
			item_checkboxes: true
		},

		onrefresh: function(folder, required) {
			// Optional:  Ignore non-required refresh requests.  By default, folders are refreshed every 5 minutes so the widget has up-to-date information.
//			if (!required)  return;

			// Maybe notify a connected WebSocket here to watch the folder on the server for changes.
			if (folder === this.GetCurrentFolder())
			{
			}

			// This code randomly generates content for the demo.
			// See the documentation for a better onrefresh implementation.
			var newentries = [];

			

			newentries.push({ name: 'data1', type: 'file', id: 'data1', hash: 'data1' });

			if (this.IsMappedFolder(folder))  folder.SetEntries(newentries);
		},

		onrename: function(renamed, folder, entry, newname) {
console.log('onrename');
console.log(entry);
console.log(newname);

			// Simulate network delay.
			setTimeout(function() {
				// The entry is a copy of the original, so it is okay to modify any aspect of it, including id.
				// Passing in a completely new entry to the renamed() callback is also okay.
				entry.id = newname;
				entry.name = newname;

				renamed(entry);
			}, 250);
		},

		onopenfile: function(folder, entry) {
console.log('onopenfile');
console.log(entry);
		},

		onnewfolder: function(created, folder) {
console.log('onnewfolder');
			// Simulate network delay.
			setTimeout(function() {
				var entry = { name: 'New Folder', type: 'folder', id: 'asdfasdffolder123', hash: 'asdfasdffolder123' };

				created(entry);
			}, 250);
		},

		onnewfile: function(created, folder) {
console.log('onnewfile');
			// Simulate network delay.
			setTimeout(function() {
				var entry = { name: 'New File.txt', type: 'file', id: 'asdfasdffile123', hash: 'asdfasdffile123' };

				created(entry);
			}, 250);
		},

		oninitupload: function(startupload, fileinfo) {
console.log('oninitupload');
console.log(fileinfo.file);
console.log(JSON.stringify(fileinfo.folder.GetPathIDs()));

			if (fileinfo.type === 'dir')
			{
				// Create a directory.  This type only shows up if the directory is empty.

				// Simulate network delay.
				setTimeout(function() {

					// Passing false as the second parameter to startupload will remove the item from the queue.
					startupload(false);
				}, 250);
			}
			else
			{
				// For those who wish to handle file uploads via external libraries, fileinfo.file contains the File object.

				// Simulate network delay.
				setTimeout(function() {
					// Set a URL, headers, and params to send with the file data to the server.
					fileinfo.url = 'filemanager/';

					fileinfo.headers = {
					};

					fileinfo.params = {
						action: 'upload',
						id: 'temp-12345',
						path: JSON.stringify(fileinfo.folder.GetPathIDs()),
						name: fileinfo.fullPath,
						size: fileinfo.file.size,
						xsrftoken: 'asdfasdf'
					};

					fileinfo.fileparam = 'file';

					// Optional:  Send chunked uploads.  Requires the server to know how to put chunks back together.
					fileinfo.chunksize = 1000000;

					// Optional:  Automatic retry count for the file on failure.
					fileinfo.retries = 3;

					// Start the upload.
					startupload(true);
				}, 250);
			}
		},

		// Optional upload handler function to finalize an uploaded file on a server (e.g. move from a temporary directory to the final location).
		onfinishedupload: function(finalize, fileinfo) {
console.log('onfinishedupload');
console.log(fileinfo);
			// Simulate network delay.
			setTimeout(function() {
				finalize(true);
			}, 250);
		},

		// Optional upload handler function to receive permanent error notifications.
		onuploaderror: function(fileinfo, e) {
console.log('onuploaderror');
console.log(e);
console.log(fileinfo);
		},

		oninitdownload: function(startdownload, folder, ids, entries) {
console.log('oninitdownload');
console.log(ids);
console.log(entries);
			// Simulate network delay.
			setTimeout(function() {
				// Set a URL and params to send with the request to the server.
				var options = {};

				// Optional:  HTTP method to use.
//				options.method = 'POST';

				options.url = 'filemanager/';

				options.params = {
					action: 'download',
					path: JSON.stringify(folder.GetPathIDs()),
					ids: JSON.stringify(ids),
					xsrftoken: 'asdfasdf'
				};

				// Optional:  Control the download via an in-page iframe (default) vs. form only (new tab).
//				options.iframe = false;

				startdownload(options);
			}, 250);
		},

		ondownloadstarted: function(options) {
console.log('started');
console.log(options);
		},

		ondownloaderror: function(options) {
console.log('error');
console.log(options);
		},

		// Calculated information must be fully synchronous (i.e. no AJAX calls).  Chromium only.
		ondownloadurl: function(result, folder, ids, entry) {
console.log('ondownloadurl');
console.log(folder);
console.log(ids);
console.log(entry);
			result.name = (ids.length === 1 ? (entry.type === 'file' ? entry.name : entry.name + '.zip') : 'download-' + Date.now() + '.zip');
			result.url = 'http://127.0.0.1/file-explorer-fs/?action=download&xsrfdata=asdfasdfasdf&xsrftoken=asdfasdf&path=' + encodeURIComponent(JSON.stringify(folder.GetPathIDs())) + '&ids=' + encodeURIComponent(JSON.stringify(ids));
		},

		oncopy: function(copied, srcpath, srcids, destfolder) {
console.log('oncopy');
console.log(srcpath);
console.log(srcids);
console.log(destfolder.GetPathIDs());
			// Simulate network delay.
			setTimeout(function() {
				// Fill an array with copied destination folder entries from the server.
				var entries = [];

				copied(true, entries);
			}, 250);
		},

		onmove: function(moved, srcpath, srcids, destfolder) {
console.log('onmove');
console.log(srcpath);
console.log(srcids);
console.log(destfolder.GetPathIDs());
			// Simulate network delay.
			setTimeout(function() {
				// Fill an array with moved destination folder entries from the server.
				var entries = [];

				moved(true, entries);
			}, 250);
		},

		ondelete: function(deleted, folder, ids, entries, recycle) {
console.log('ondelete');
console.log(folder);
console.log(ids);
console.log(entries);
console.log(recycle);
			// Ask the user if they really want to delete/recycle the items.
			if (!recycle && !confirm('Are you sure you want to permanently delete ' + (entries.length == 1 ? '"' + entries[0].name + '"' : entries.length + ' items') +  '?'))  deleted('Cancelled deletion');
			else
			{
				// Simulate network delay.
				setTimeout(function() {
					deleted(true);
				}, 250);
			}
		},
	};

	var fe = new window.FileExplorer(elem, options);
console.log(fe);

//fe.Focus();

	// Verify that there aren't any leaked globals.
	setTimeout(function() {
		// Create an iframe and put it in the <body>.
		var iframe = document.createElement('iframe');
		document.body.appendChild(iframe);

		// We'll use this to get a "pristine" window object.
		var pristineWindow = iframe.contentWindow.window;

		// Go through every property on `window` and filter it out if
		// the iframe's `window` also has it.
		console.log(Object.keys(window).filter(function(key) {
			return !pristineWindow.hasOwnProperty(key)
		}));

		// Remove the iframe.
		document.body.removeChild(iframe)
	}, 15000);


})();
</script>


</body>
</html>