itunes-album-HTML-archive
=========================

Create and maintain an neat HTML rendition of your iTunes album collection. Utilizes Applescript, MySQL, PHP, jQuery &amp; CSS.

=-=-=-=-=-=-=-=-=-=-=-=-  
**version**: 0.1  
**Project Homepage**: http://sethelalouf.com/archives/133  
**Demo**: http://sethelalouf.com/wp-content/project_files/ialbum_archive/  
=-=-=-=-=-=-=-=-=-=-=-=-



Requirements
------------

- A web server with PHP5.
- A MySQL database
- A Mac with an iTunes library
- Familiarity with PHP, MySQL, and jQuery if you plan to heavily customize.




Installation
------------

1. Download the iTunes Album Archive zip.

2. Expand the archive

3. Double click the ```iTunes Album Archive.scptd``` file to open it in the **AppleScript Editor**.

4. Follow the directions and examples in the first few lines of this file to change the configuration variables to reflect your installation.

5. Save your changes to this file, close it, and quit the **AppleScript Editor** app.

6. Now, put this file (iTunes Album Archive.scptd) here: ```~/Library/iTunes/Scripts```. 

Note: In Mac OS X 10.7+, to access your User's Library folder, you'll need to hold down the **OPTION** key and select the Go menu from the Finder's menu bar. Then while continuing to hold the **OPTION** key, select the Library menu item. Also: If there is no ```Scripts``` folder in your ```~/Library/iTunes``` folder then simply create it.

- On your hosting platform, create a new database for the *iTunes Album Archive Project* to use.

- Now, find the php config file (```ialbum_archive/inc/config.php``` within the ```iTunes Album Archive Project``` folder from step 2) and open it in a text editor.

- Follow the directions and examples in the first few lines of this file to change the configuration variables to reflect your installation. Save your changes to this file, close it, and quit your text editor.

- Upload the entire ```ialbum_archive``` directory (not just the contents, but the whole folder) to the location that corresponds to the paths you set in the configuration steps above.

- That's it. Now all you need to do is run the applescript on the playlist of your choice to have the system start creating your online archive.

**Note**: *You might want to create a smart playlist that selects all the tracks you wish to include in your iTunes Album Archive. Maybe you only want to create the web archive with albums of a particular artist, or from a particular span of years, etc. Once you've got your playlist set up, just run the iTunes Album Archive script from time to time while your target playlist is selected. Remember, it takes between 25 seconds and a minute to process a new album, and about 10 seconds to check for and then skip over an album that's already been added. If you're adding more than 30 or so new albums (let's say for your initial running of the script) it's probably best to do this at the end of the day, and to let the script run over night. When the script is running, you won't be able to adjust iTunes but iTunes will continue to play while the script runs if it was playing before.*
