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

	class q2apro_comment_to_answer_page {
		
		var $directory;
		var $urltoroot;
		
		function load_module($directory, $urltoroot)
		{
			$this->directory=$directory;
			$this->urltoroot=$urltoroot;
		}
		
		// for display in admin interface under admin/pages
		function suggest_requests() 
		{	
			return array(
				array(
					'title' => 'Convert Comment to Answer', // title of page
					'request' => 'convertcomment', // request name
					'nav' => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
				),
			);
		}
		
		// for url query
		function match_request($request)
		{
			if ($request=='convertcomment') {
				return true;
			}

			return false;
		}

		function process_request($request)
		{
		
			// return if not admin level
			$level=qa_get_logged_in_level();
			if ($level<QA_USER_LEVEL_ADMIN) {
				$qa_content = qa_content_prepare();
				$qa_content['custom0']='<p>'.qa_lang('q2apro_comment_to_answer_lang/not_allowed').'</p>';
				return $qa_content;
			}

			// counter for custom html output
			$c = 2;

			// start content
			$qa_content = qa_content_prepare();

			// page title
			$qa_content['title'] = qa_lang('q2apro_comment_to_answer_lang/page_title'); 

			// some CSS styling
			$qa_content['custom'.++$c] = '<style type="text/css">
				#convdiv, .qa-main p, .qa-main a, .qa-main input { font-size:14px; }
				#convdiv { border-left:10px solid #ABF;margin:20px 0 0 5px;padding:5px 10px; }
				#withthread { margin-left:20px; }
				.qa-main h1 { margin-bottom:40px; }
			</style>';
			
			
			// REQUEST: if we have convert data, convert
			$commentid = qa_post_text('commentid');
			$withthread = qa_post_text('withthread'); // checkbox
			
			if(isset($commentid)) {

				// make sure we have a comment and not Q or A
				$typeCheck = qa_db_read_one_value(
								qa_db_query_sub('SELECT type FROM `^posts` 
												WHERE `postid` = #
												LIMIT 1', $commentid));
				if($typeCheck!='C') {
					// content output error
					$wrongid_link = '→ '.qa_lang('q2apro_comment_to_answer_lang/answer_id').': '.$commentid;
					if($typeCheck=='Q') {
						$wrongid_link = '→ <a target="_blank" href="'.qa_opt('site_url').$commentid.'">'.qa_lang('q2apro_comment_to_answer_lang/question_id').': '.$commentid.'</a>';
					}
					$qa_content['custom'.++$c]= '<p>'.qa_lang('q2apro_comment_to_answer_lang/error0').' '.$wrongid_link.'</p>';
					$qa_content['custom'.++$c]= '<a href="./convertcomment" class="btnblue">'.qa_lang('q2apro_comment_to_answer_lang/return').'</a>';
					return $qa_content;
				}
				
				// convert type from C to A
				$convertQuery = qa_db_query_sub('UPDATE `^posts` 
												SET `type` = "A" 
												WHERE `postid` = #
												AND `type` = "C"
												LIMIT 1', $commentid);
												
				// get parentid of converted comment (is question or answer)
				$getQA_query = qa_db_read_all_assoc( 
									qa_db_query_sub('SELECT created, parentid, type FROM `^posts` 
														WHERE `postid` = # 
														LIMIT 1', $commentid) );
				$parentid = $getQA_query[0]['parentid'];
				
				// check if parent is question or answer
				$parenttype = qa_db_read_one_value( 
									qa_db_query_sub('SELECT type FROM `^posts` 
														WHERE `postid` = # 
														LIMIT 1', $parentid) );

				// questionid or answerid that will hold all succeeding comments to be transferred
				$succId = null;
				if($parenttype=='Q') {
					// question
					$questionid = $getQA_query[0]['parentid']; // e.g. 52838
					$created = $getQA_query[0]['created']; // e.g. 2013-10-11 12:45:43
					$succId = $questionid;
				}
				else if($parenttype=='A') {
					// answer, we need to query again to receive the question id
					$answerid = $getQA_query[0]['parentid'];
					$created = $getQA_query[0]['created']; // e.g. 2013-10-11 12:45:43
					$succId = $answerid;
					$getQ_query = qa_db_read_all_assoc( 
										qa_db_query_sub('SELECT parentid FROM `^posts` 
															WHERE `postid` = # 
															AND `type` = "A"
															LIMIT 1', $answerid) );
					$questionid = $getQ_query[0]['parentid']; // e.g. 52838
					
					// parent of comment was answer, must be question now
					$convertQuery = qa_db_query_sub('UPDATE `^posts` 
												SET `parentid` = #
												WHERE `postid` = #
												AND `type` = "A"
												LIMIT 1', $questionid, $commentid);
				}
				else {
					// content output error
					$qa_content['custom'.++$c]= '<p>'.qa_lang('q2apro_comment_to_answer_lang/error3').'</p>';
					$qa_content['custom'.++$c]= '<a href="./convertcomment" class="btnblue">'.qa_lang('q2apro_comment_to_answer_lang/return').'</a>';
					return $qa_content;
				}

				if(isset($questionid) && isset($created)) {
					/* move all succeeding comments to new answer, 
					   change parentid of each succeeding comment to answer-id ($commentid) */
					if(isset($withthread) && isset($succId)) {
						// get all comments with same parentid that are older than our converted comment
						$succCommentsQuery = qa_db_query_sub('SELECT postid FROM `^posts`
														WHERE `parentid` = #
														AND `created` > #
														AND `type` = "C"
														',
														$succId, $created);
						// move all succeeding comments to new answer
						while( ($comment = qa_db_read_one_assoc($succCommentsQuery,true)) !== null ) {
							qa_db_query_sub('UPDATE `^posts` 
												SET `parentid` = # 
												WHERE `postid` = #
												AND `type` = "C"
												LIMIT 1', 
												$commentid, $comment['postid']);
						}
					}

					// recalculate answer count for question, count answers first
					$newAcount = qa_db_read_one_value( qa_db_query_sub('SELECT COUNT(*) FROM `^posts`
														WHERE `parentid` = #
														AND `type` = "A"',
														$questionid), true);
					if(isset($newAcount)) {
						qa_db_query_sub('UPDATE `^posts`
											SET acount = # 
											WHERE postid = #',
											$newAcount,$questionid);
						
						// content output success
						$qa_content['custom'.++$c]= '<p>'.qa_lang('q2apro_comment_to_answer_lang/success').' → <a target="_blank" href="'.qa_opt('site_url').$questionid.'?show='.$commentid.'#a'.$commentid.'">ID: '.$commentid.'</a></p>';
						$qa_content['custom'.++$c]= '<a href="./convertcomment" class="btnblue">'.qa_lang('q2apro_comment_to_answer_lang/return').'</a>';
						return $qa_content;
					}
					else {
						// content output error
						$qa_content['custom'.++$c]= '<p>'.qa_lang('q2apro_comment_to_answer_lang/error1').'</p>';
						$qa_content['custom'.++$c]= '<a href="./convertcomment" class="btnblue">'.qa_lang('q2apro_comment_to_answer_lang/return').'</a>';
						return $qa_content;
					}
				}
				else {
					// content output error
					$qa_content['custom'.++$c]= '<p>'.qa_lang('q2apro_comment_to_answer_lang/error2').'</p>';
					$qa_content['custom'.++$c]= '<a href="./convertcomment" class="btnblue">'.qa_lang('q2apro_comment_to_answer_lang/return').'</a>';
					return $qa_content;
				}
			}


			/* default page with convert dialog */
			$qa_content['custom'.++$c] = '<div id="convdiv">
											<form name="uploadform" method="post" action="'.qa_self_html().'">
												<input name="commentid" id="commentid" type="text" placeholder="'.qa_lang('q2apro_comment_to_answer_lang/input_placeholder').'" autofocus>
												<input name="withthread" id="withthread" type="checkbox" checked="true"> 
												<label for="withthread">'.qa_lang('q2apro_comment_to_answer_lang/move_thread').'</label><br />
												<br />
												<input type="submit" value="'.qa_lang('q2apro_comment_to_answer_lang/convertbtn').'" class="btnblue">
											</form>
										 </div>';
			return $qa_content;
		}
		
	};
	

/*
	Omit PHP closing tag to help avoid accidental output
*/