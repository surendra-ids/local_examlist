<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local plugin examlist
 *
 * This file helps students with a list of all the quizzes that he has to perform.
 * @package   local_examlist
 * @copyright 2019 idslogic <sales@idslogic.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

class local_examlist_renderer extends plugin_renderer_base {

    /**
     * Returns html to display the list of all quizzes assigned to user
     *
     * @return html
     */
    public function display_quizzes() {
        $output = '';
        global $USER;
        $examlist = new ExamList();
        $records = $examlist->getexams($USER->id);
		$table = new html_table();
	    $table->head = array(get_string('exam_name','local_examlist'),get_string('course_name','local_examlist'),
               get_string('status','local_examlist'), get_string('operation','local_examlist'));
	    if(!empty($records)) {
		    	foreach($records as $id => $quiz) {
		        	$table->data[] = array($quiz->quizname, $quiz->counrsename,$quiz->attempt,html_writer::link(new moodle_url('/mod/quiz/view.php', array('id'=>$quiz->instanceid)), 'Take Assesment'));
		        }
		} else {
            $table->data[] = array(get_string('noassessments','local_examlist'), '');
        }
	    return html_writer::table($table);
    }
    
    
        public function print_paging_bar($totalrecords, $page, $perpage,$search) {
        global $OUTPUT;

        $baseurl = new moodle_url('/local/examlist/index.php');
        
        $baseurl->params(array('search' => $search));

        $output = '';
        $output .= $OUTPUT->paging_bar($totalrecords, $page, $perpage, $baseurl);
        return $output;
        
    }
}