<?php
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System            				        //
// Copyright (c) 2000 XOOPS.org                           					//
// <http://www.xoops.org/>                             						//
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// 																			//
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// 																			//
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// 																			//
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
include __DIR__ . '/admin_header.php';
include_once XOOPS_ROOT_PATH . '/modules/AMS/class/class.newstopic.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

$moduleAdmin = \Xmf\Module\Admin::getInstance();
$moduleAdmin->displayNavigation('groupperms.php');

$module_id = $xoopsModule->getVar('mid');
$xt = new AmsTopic($xoopsDB -> prefix('ams_topics'));
$allTopics = $xt->getAllTopics();
$totaltopics = count($allTopics);
if ($totaltopics > 0) {
    //Approver Form
    $approveform = new XoopsGroupPermForm(_AMS_AM_APPROVEFORM, $module_id, 'ams_approve', _AMS_AM_APPROVEFORM_DESC);

    //Submitter Form
    $submitform = new XoopsGroupPermForm(_AMS_AM_SUBMITFORM, $module_id, 'ams_submit', _AMS_AM_SUBMITFORM_DESC);

    //Viewer Form
    $viewform = new XoopsGroupPermForm(_AMS_AM_VIEWFORM, $module_id, 'ams_view', _AMS_AM_VIEWFORM_DESC);


    foreach ($allTopics as $topic_id => $topic) {
        $approveform->addItem($topic_id, $topic->topic_title(), $topic->topic_pid());
        $submitform->addItem($topic_id, $topic->topic_title(), $topic->topic_pid());
        $viewform->addItem($topic_id, $topic->topic_title(), $topic->topic_pid());
    }

    echo $approveform->render();
    unset($approveform);

    echo $submitform->render();
    unset($submitform);

    echo $viewform->render();
    unset($viewform);
}

xoops_cp_footer();
