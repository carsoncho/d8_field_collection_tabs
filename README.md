
Field collection Tabs
-----------------
Provides a field formatter for field collection fields. This module is a port of the D7 version and provides the same functionality.

 Usage
 ------
 
  * After enabling the module head to the "Manage Display" page for a given entity type
   that is implementing a field collection.
    
  * In the "Format" column select the dropdown list and choose the "Tabs" value.
   
  * You'll see in the summary "Numbered Tabs", meaning no field has been selected
  to use for the title of the tabs. If no title is present upon display or no title field is selected
  a default "Tab 1", "Tab 2", etc will be displayed as the title of the tab.
   
  * Below that you'll see "View Mode: Full" if you have don't have any display modes
   created for field collections, or selected a different view mode in the settings.  
    
  * Click the cog on the far right to open the settings. Here you will choose a field to use
  for displaying as the title of the tabs and, if view modes have been created for field collection, 
  then you will be set the view mode for how the field collection to display as in the tab. Otherwise it will
  just use the "Default" display.
  
  * Once finished selecting, update the settings. You'll see the summary update with the chosen values.
  Be sure to save, then go view your entities with tabs.
   

Notes
-------------

  * Currently you can use any non-base field for the title i.e. image, media, paragraphs, etc. 
   Use at own discretion.
