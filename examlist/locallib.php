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
defined('MOODLE_INTERNAL') || die();
global $DB, $CFG;

class ExamList {

    public function getexams($userid) {
        global $DB;
        $page = optional_param('page', 0, PARAM_INT);
        $perpage = optional_param('perpage', 2, PARAM_INT);
        $start = $page * $perpage;
        $totalcount = 0;
        $usercourses = enrol_get_users_courses($userid);
        if(!empty($usercourses)) {
             foreach ($usercourses as $key => $course) {
                $totalcount += $DB->count_records('quiz', array('course' => $course->id));
            }
        }
        if ($start > $totalcount) {
          $page = 0;
          $start = 0;
        }
        $coursequizzes = array();
        $usercourses = enrol_get_users_courses($userid);
        $quizid = $DB->get_field('modules', 'id', array('name' => 'quiz'));
        if(!empty($usercourses)) {
            foreach ($usercourses as $key => $course) {
            $sql = "SELECT q.id, q.name as quizname, c.fullname as counrsename, qa.attempt, cm.id as instanceid"
                    . " FROM {quiz} as q left join {course} as c on q.course = c.id left join {quiz_attempts} as qa "
                    . "on qa.quiz = q.id left join {course_modules} as cm on cm.instance = q.id WHERE "
                    . "c.id = $course->id and cm.module = $quizid";
                $quiz_record = $DB->get_records_sql($sql);
                if (!empty($quiz_record)) {
                    foreach ($quiz_record as $qkey => $value) {
                        $coursequizzes[$value->id] = new stdClass();
                        $coursequizzes[$value->id]->quizname = $value->quizname;
                        $coursequizzes[$value->id]->counrsename = $value->counrsename;
                        $coursequizzes[$value->id]->attempt = isset($value->attempt) ? get_string('attempted','local_examlist') : get_string('not_yet_attempted','local_examlist') ;
                        $coursequizzes[$value->id]->instanceid = $value->instanceid;
                    }
//                    $coursequizzes[] = $quizzes;
                }
            }
        }
        return array_slice($coursequizzes,$start,$perpage);
    }
}