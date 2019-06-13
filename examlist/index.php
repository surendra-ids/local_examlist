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
 * @package     local_examlist
 * @copyright  2019 idslogic <sales@idslogic.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->dirroot . '/local/examlist/lib.php');

$PAGE->set_pagelayout('frontpage');
//redirect_if_major_upgrade_required();

require_login();
$strmymoodle = 'Exam Listing';
if (!isguestuser()) {
    $userid = $USER->id;
    $context = context_user::instance($USER->id);
    $header = "$strmymoodle";
}
$params = array();
$PAGE->set_url('/local/examlist/index.php', $params);
$PAGE->set_pagelayout('standard');
$PAGE->set_pagetype('my-index');
$PAGE->set_context(context_system::instance());
$PAGE->set_title($header);
$PAGE->set_heading($header);
if (!isguestuser()) {

}
$PAGE->requires->css('/local/examlist/css/custom.css', false);
echo $OUTPUT->header();


global $USER;

$html = '';
$html .= html_writer::start_tag('section');
$html .= html_writer::tag('h2', 'My Assesments', array('class'=>'course_title'));
    
$renderer = $PAGE->get_renderer('local_examlist');
$html .=    $renderer->display_quizzes();
$html .= html_writer::end_tag('section');
echo $html;
echo $OUTPUT->footer();