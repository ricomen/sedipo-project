<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<head><title>Manage Files</title></head>
<body>
<style type="text/css">
body { font-family: Verdana, Arial, Helvetica, sans-serif; position: relative; color: #222222; font-size: 1.0em; }
</style>

<link rel="stylesheet" type="text/css" href="file-explorer/file-explorer.css">
<script type="text/javascript" src="file-explorer/file-explorer.js"></script>

<div id="filemanager" style="height: 40vh; max-height: 300px; position: relative;"></div>

<script type="text/javascript">
var filemanager_data= 'Иванов Иван Иванович';
var filemanager_directory = '123';

(function() {
	var elem = document.getElementById('filemanager');

	var options = {
		initpath: [
			[ '', '/', { canmodify: false } ],
			[ 'data', 'Документы', { canmodify: false}  ],
			[ filemanager_directory, filemanager_data, { canmodify: true}  ]
		],

		onrefresh: function(folder, required) {
			// Optional:  Ignore non-required refresh requests.  By default, folders are refreshed every 5 minutes so the widget has up-to-date information.
//			if (!required)  return;

			// Maybe notify a connected WebSocket here to watch the folder on the server for changes.
			if (folder === this.GetCurrentFolder())
			{
			}

			var $this = this;

			var xhr = new this.PrepareXHR({
				//url: window.location.href,
				url: 'https://sed.ipo5.ru/file-explorer.php',
				params: {
					action: 'file_explorer_refresh',
					path: JSON.stringify(folder.GetPathIDs()),
					xsrftoken: 'asdfasdf'
				},
				onsuccess: function(e) {
					var data = JSON.parse(e.target.responseText);


					if (data.success)
					{
						if ($this.IsMappedFolder(folder))  folder.SetEntries(data.entries);
					}
					else if (required)
					{
						$this.SetNamedStatusBarText('folder', $this.EscapeHTML('Failed to load folder.  ' + data.error));
					}
				},
				onerror: function(e) {
					// Maybe output a nice message if the request fails for some reason.
//					if (required)  $this.SetNamedStatusBarText('folder', 'Failed to load folder.  Server error.');


				}
			});

			xhr.Send();
		},

		onrename: function(renamed, folder, entry, newname) {
			var xhr = new this.PrepareXHR({
				url: 'https://sed.ipo5.ru/file-explorer.php',
				params: {
					action: 'file_explorer_rename',
					path: JSON.stringify(folder.GetPathIDs()),
					id: entry.id,
					newname: newname,
					xsrftoken: 'asdfasdf'
				},
				onsuccess: function(e) {
					var data = JSON.parse(e.target.responseText);

					// Updating the existing entry or passing in a completely new entry to the renamed() callback are okay.
					if (data.success)  renamed(data.entry);
					else  renamed(data.error);
				},
				onerror: function(e) {

					renamed('Server/network error.');
				}
			});

			xhr.Send();
		},

		onopenfile: function(folder, entry) {

		},

		onnewfolder: function(created, folder) {
			var xhr = new this.PrepareXHR({
				url: 'https://sed.ipo5.ru/file-explorer.php',
				params: {
					action: 'file_explorer_new_folder',
					path: JSON.stringify(folder.GetPathIDs()),
					xsrftoken: 'asdfasdf'
				},
				onsuccess: function(e) {
					var data = JSON.parse(e.target.responseText);

					if (data.success)  created(data.entry);
					else  created(data.error);
				},
				onerror: function(e) {

					created('Server/network error.');
				}
			});

			xhr.Send();
		},

		onnewfile: function(created, folder) {
			var xhr = new this.PrepareXHR({
				url: 'https://sed.ipo5.ru/file-explorer.php',
				params: {
					action: 'file_explorer_new_file',
					path: JSON.stringify(folder.GetPathIDs()),
					xsrftoken: 'asdfasdf'
				},
				onsuccess: function(e) {
					var data = JSON.parse(e.target.responseText);


					if (data.success)  created(data.entry);
					else  created(data.error);
				},
				onerror: function(e) {

					created('Server/network error.');
				}
			});

			xhr.Send();
		},

		oninitupload: function(startupload, fileinfo, queuestarted) {


			var $this = this;

			if (fileinfo.type === 'dir')
			{
				// Create a directory.  This type only shows up if the directory is empty.
				// Set a URL, headers, and params to send to the server.
				fileinfo.url = 'https://sed.ipo5.ru/file-explorer.php';

				fileinfo.headers = {
				};

				fileinfo.params = {
					action: 'file_explorer_new_folder',
					path: JSON.stringify(fileinfo.folder.GetPathIDs()),
					name: fileinfo.fullPath,
					xsrftoken: 'asdfasdf'
				};

				fileinfo.currpathparam = 'currpath';

				// Automatic retry count for the directory on failure.
				fileinfo.retries = 3;

				// Create the directory.
				startupload(true);
			}
			else
			{
				var origcurrfolder = $this.GetCurrentFolder();

				// Prepare the file upload on the server.
				var xhr = new this.PrepareXHR({
					url: 'https://sed.ipo5.ru/file-explorer.php',
					params: {
						action: 'file_explorer_upload_init',
						path: JSON.stringify(fileinfo.folder.GetPathIDs()),
						name: fileinfo.fullPath,
						size: fileinfo.file.size,
						currpath: JSON.stringify(origcurrfolder.GetPathIDs()),
						queuestarted: queuestarted,
						xsrftoken: 'asdfasdf'
					},
					onsuccess: function(e) {
						var data = JSON.parse(e.target.responseText);


						if (!data.success)  startupload(data.error);
						else
						{
							if (data.entry && $this.IsMappedFolder(origcurrfolder))  origcurrfolder.SetEntry(data.entry);

							// Set a URL, headers, and params to send with the file data to the server.
							fileinfo.url = 'https://sed.ipo5.ru/file-explorer.php';

							fileinfo.headers = {
							};

							fileinfo.params = {
								action: 'file_explorer_upload',
								path: JSON.stringify(fileinfo.folder.GetPathIDs()),
								name: fileinfo.fullPath,
								size: fileinfo.file.size,
								queuestarted: queuestarted,
								xsrftoken: 'asdfasdf'
							};

							fileinfo.fileparam = 'file';
							fileinfo.currpathparam = 'currpath';

							// Optional:  Send chunked uploads.  Requires the server to know how to put chunks back together.
							fileinfo.chunksize = 1000000;

							// Optional:  Automatic retry count for the file on failure.
							fileinfo.retries = 3;

							// Start the upload.
							startupload(true);
						}
					},
					onerror: function(e) {

						startupload('Server/network error.');
					}
				});

				xhr.Send();
			}
		},

		onuploaderror: function(fileinfo, e) {


		},

		oninitdownload: function(startdownload, folder, ids, entries) {
			// Set a URL and params to send with the request to the server.
			var options = {};

			// Optional:  HTTP method to use.
//			options.method = 'POST';

			options.url = 'https://sed.ipo5.ru/file-explorer.php';

			options.params = {
				action: 'file_explorer_download',
				path: JSON.stringify(folder.GetPathIDs()),
				ids: JSON.stringify(ids),
				xsrftoken: 'asdfasdf'
			};

			// Optional:  Control the download via an in-page iframe (default) vs. form only (new tab).
//			options.iframe = false;

			startdownload(options);
		},

		ondownloadstarted: function(options) {

		},

		ondownloaderror: function(options) {
		},

		oncopy: function(copied, srcpath, srcids, destfolder) {
			var $this = this;

			var xhr = new this.PrepareXHR({
				url: 'https://sed.ipo5.ru/file-explorer.php',
				params: {
					action: 'file_explorer_copy_init',
					srcpath: JSON.stringify($this.GetPathIDs(srcpath)),
					srcids: JSON.stringify(srcids),
					destpath: JSON.stringify(destfolder.GetPathIDs()),
					xsrftoken: 'asdfasdf'
				},
				onsuccess: function(e) {
					var data = JSON.parse(e.target.responseText);


					if (!data.success)  copied(data.error);
					else if (data.overwrite > 0 && !confirm($this.FormatStr($this.Translate('Copying will overwrite {0} ' + (data.overwrite === 1 ? 'item' : 'items') + '.  Proceed?'), data.overwrite)))  copied('Copy cancelled.');
					else
					{
						var runxhr, origcurrfolder;

						var CancelXHR = function() {
							runxhr.Abort();
						};

						var progresstracker = $this.CreateProgressTracker(CancelXHR);

						var options = {
							url: 'https://sed.ipo5.ru/file-explorer.php',
							params: {
								action: 'file_explorer_copy',
								copykey: data.copykey,
								xsrftoken: 'asdfasdf'
							},
							onsuccess: function(e) {
								var data = JSON.parse(e.target.responseText);


								if (!data.success)  copied(data.error, data.finalentries);
								else
								{
									progresstracker.totalbytes = data.totalbytes;
									progresstracker.queueditems = data.queueditems;
									progresstracker.queuesizeunknown = data.queuesizeunknown;
									progresstracker.itemsdone = data.itemsdone;
									progresstracker.faileditems = data.faileditems;

									if ($this.IsMappedFolder(origcurrfolder))  origcurrfolder.UpdateEntries(data.currentries);

									if (data.queueditems)  NextRun();
									else
									{
										$this.RemoveProgressTracker(progresstracker, 'Copying done');
										progresstracker = null;

										copied(true, data.finalentries);

										runxhr.Destroy();
										runxhr = null;
									}
								}
							},
							onerror: function(e) {

								copied('Server/network error.');

								runxhr.Destroy();
								runxhr = null;
							},
							onabort: function(e) {
								progresstracker.queueditems = 0;
								progresstracker.queuesizeunknown = false;

								$this.RemoveProgressTracker(progresstracker, 'Copying stopped');
								progresstracker = null;

								copied(false);

								runxhr.Destroy();
								runxhr = null;
							}
						};

						// Performs another copy operation cycle.
						var NextRun = function() {
							if (runxhr)  runxhr.Destroy();

							origcurrfolder = $this.GetCurrentFolder();
							options.params.currpath = JSON.stringify(origcurrfolder.GetPathIDs());

							runxhr = new $this.PrepareXHR(options);

							runxhr.Send();
						};

						NextRun();
					}
				},
				onerror: function(e) {

					copied('Server/network error.');
				}
			});

			xhr.Send();
		},

		onmove: function(moved, srcpath, srcids, destfolder) {
			var $this = this;

			var xhr = new this.PrepareXHR({
				url: 'https://sed.ipo5.ru/file-explorer.php',
				params: {
					action: 'file_explorer_move',
					srcpath: JSON.stringify($this.GetPathIDs(srcpath)),
					srcids: JSON.stringify(srcids),
					destpath: JSON.stringify(destfolder.GetPathIDs()),
					xsrftoken: 'asdfasdf'
				},
				onsuccess: function(e) {
					var data = JSON.parse(e.target.responseText);


					if (!data.success)  moved(data.error);
					else  moved(true, data.entries);
				},
				onerror: function(e) {

					moved('Server/network error.');
				}
			});

			xhr.Send();
		},

		ondelete: function(deleted, folder, ids, entries, recycle) {
			var $this = this;

			// Ask the user if they really want to delete/recycle the items.
			if (!recycle && !confirm('Are you sure you want to permanently delete ' + (entries.length == 1 ? '"' + entries[0].name + '"' : entries.length + ' items') +  '?'))  deleted('Cancelled deletion');
			else
			{
				var xhr = new this.PrepareXHR({
					url: 'https://sed.ipo5.ru/file-explorer.php',
					params: {
						action: (recycle ? 'file_explorer_recycle' : 'file_explorer_delete'),
						path: JSON.stringify(folder.GetPathIDs()),
						ids: JSON.stringify(ids),
						xsrftoken: 'asdfasdf'
					},
					onsuccess: function(e) {
						var data = JSON.parse(e.target.responseText);


						if (!data.success)  deleted(data.error);
						else  deleted(true);
					},
					onerror: function(e) {

						deleted('Server/network error.');
					}
				});

				xhr.Send();
			}
		},
	};

	var fe = new window.FileExplorer(elem, options);
})();
</script>


</body>
</html>