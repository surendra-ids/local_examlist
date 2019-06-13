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
 * Local plugin "examlist"
 *
 * This file helps students with a list of all the quizzes that he has to perform.
 * @package    local_examlist
 * @copyright  2019 idslogic <sales@idslogic.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once("$CFG->libdir/blocklib.php");
global $DB, $CFG;

function local_examlist_extend_navigation(global_navigation $navigation) {
    $dashboardurl = '/local/examlist/index.php';

    if (isloggedin()) {
        $node = $navigation->add('My Assessments', $dashboardurl);
        $node->showinflatnavigation = true;
    }
}

class ExamList {

    public function getexams($userid) {
        global $DB;

        $coursequizzes = array();
        $usercourses = enrol_get_users_courses($userid);
        if(!empty($usercourses)) {
            foreach ($usercourses as $key => $course) {
                $quiz = $DB->get_records('quiz', array('course' => $course->id));
                if (!empty($quiz)) {
                    foreach ($quiz as $qkey => $value) {
                        $quizzes[$value->id] = $value;
                        $result = $DB->get_record('course_modules', array('instance' => $value->id, 'module' => 16), 'id');
                        $quizzes[$value->id]->instanceid = $result->id;
                    }
                    $coursequizzes[$key] = $quizzes;
                }
            }
        }
        return $coursequizzes;
    }
}
