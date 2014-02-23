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
	
	return array(
		'page_title' => 'Convert Comment to Answer',
		'not_allowed' => 'You do not have permission to access this site.',
		'input_placeholder' => 'Insert comment-ID',
		'move_thread' => 'Move succeeding comments to new answer',
		'convertbtn' => 'Convert',
		'success' => 'Comment successfully converted to answer. Well done :)',
		'answer_id' => 'Answer ID',
		'question_id' => 'Question ID',
		'return' => 'return to converter',
		'error0' => 'Error: The ID you entered is no comment id.',
		'error1' => 'Error: Could not set new answer count to question.',
		'error2' => 'Error: Could not find parent of comment.',
		'error3' => 'Error: It seems that you did something impossible.',
	);
	

/*
	Omit PHP closing tag to help avoid accidental output
*/