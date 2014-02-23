<?php
/*
	Plugin Name: Comment to Answer
	Plugin URI: https://github.com/q2apro/q2a-comment-to-answer
	Plugin Description: Converts a comment to an answer, optionally moves the succeeding comments
	Plugin Version: 0.4
	Plugin Date: 2014-02-23
	Plugin Author: q2apro.com
	Plugin Author URI: http://www.q2apro.com/
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: https://raw.github.com/q2apro/q2a-comment-to-answer/master/qa-plugin.php

	This program is free software. You can redistribute and modify it 
	under the terms of the GNU General Public License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.gnu.org/licenses/gpl.html
	
*/

if ( !defined('QA_VERSION') )
{
	header('Location: ../../');
	exit;
}

// page
qa_register_plugin_module('page', 'q2apro-comment-to-answer-page.php', 'q2apro_comment_to_answer_page', 'Comment to Answer Page');

// language file
qa_register_plugin_phrases('q2apro-comment-to-answer-lang.php', 'q2apro_comment_to_answer_lang');


/*
	Omit PHP closing tag to help avoid accidental output
*/