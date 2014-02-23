=========================================
Question2Answer Plugin: Comment to Answer
=========================================
-----------
Description
-----------
This is a plugin_ for Question2Answer_ that converts a comment to an answer, optionally moves the succeeding comments. 

------------
Installation
------------
#. Install Question2Answer_ if you haven't already.
#. Get the source code for this plugin directly from github_ or from the `q2apro plugin page`_.
#. Extract the files.
#. Optional: Change language strings in file ``q2apro-comment-to-answer-lang.php``
#. Upload the files to a subfolder called ``q2apro-comment-to-answer`` inside the ``qa-plugin`` folder of your Q2A installation.
#. Navigate to your site, go to **Admin -> Plugins**. Check if the plugin "Comment to Answer" is listed.
#. Navigate to ``yourq2asite.com/convertcomment``. From there you can do the converting as admin.

----------
How-To-Use
----------
1. Find ID of comment by clicking on the "commented" link, check URL part *?show=* **58472**
2. Insert the ID into the input field on page ``yourq2asite.com/convertcomment``
3. Click the convert button, done!

----------
Disclaimer
----------
This is **beta** code. It is probably okay for production environments, but may not work exactly as expected. You bear the risk. Refunds will not be given!

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the GNU General Public License for more details.

---------
Copyright
---------
All code herein is OpenSource_. Feel free to build upon it and share with the world.

---------
About q2a
---------
Question2Answer_ is a free and open source PHP software for Q&A sites.

----------
Final Note
----------
If you use the plugin:
  * Consider joining the `Question2Answer forum`_, answer some questions or write your own plugin!
  * You can use the code of this plugin to learn more about q2a-plugins. It is commented code.
  * Visit q2apro.com_ to get more free and premium plugins_.

  
.. _github: https://github.com/q2apro/q2apro-comment-to-answer
.. _OpenSource: http://www.gnu.org/licenses/gpl.html
.. _q2apro plugin page: http://www.q2apro.com/plugins/comment-to-answer
.. _q2apro.com: http://www.q2apro.com
.. _plugin: http://www.q2apro.com/plugins
.. _plugins: http://www.q2apro.com/plugins
.. _Question2Answer: http://www.question2answer.org/
.. _Question2Answer forum: http://www.question2answer.org/qa/
