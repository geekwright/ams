<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

include __DIR__ . '/../../mainfile.php';
include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/class/class.newsstory.php';
include_once XOOPS_ROOT_PATH . '/class/template.php';
$xoopsOption['theme_use_smarty'] = 1;
$xoopsTpl = new XoopsTpl();
$xoopsTpl->xoops_setCaching(0);
if (3 == $xoopsConfig['debug_mode']) {
    $xoopsTpl->xoops_setDebugging(true);
}
$xoopsTpl->assign(array('xoops_theme'      => $xoopsConfig['theme_set'],
                        'xoops_imageurl'   => XOOPS_THEME_URL . '/' . $xoopsConfig['theme_set'] . '/',
                        'xoops_themecss'   => xoops_getcss($xoopsConfig['theme_set']),
                        'xoops_requesturi' => htmlspecialchars($GLOBALS['xoopsRequestUri'], ENT_QUOTES),
                        'xoops_sitename'   => htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES),
                        'xoops_slogan'     => htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)
                  ));

$storyid = isset($_GET['storyid']) ? (int)$_GET['storyid'] : 0;
$version = isset($_GET['version']) ? (int)$_GET['version'] : 0;
$revision = isset($_GET['revision']) ? (int)$_GET['revision'] : 0;
$revisionminor = isset($_GET['revisionminor']) ? (int)$_GET['revisionminor'] : 0;
if (!$storyid || !$version) {
    redirect_header(XOOPS_URL . '/modules/AMS/index.php', 2, _AMS_NW_NOSTORY);
    exit();
}

$myts = MyTextSanitizer::getInstance();
// set comment mode if not set


$article = new AmsStory();
$article->getNewsVersion($storyid, $version, $revision, $revisionminor);
$gpermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!$gpermHandler->checkRight('ams_approve', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL.'/modules/AMS/index.php', 3, _NOPERM);
    exit();
}


$GLOBALS['xoopsOption']['template_main'] = 'ams_article.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';
$xoopsTpl->assign('story', $article->toArray(true, false, -1));
$banner = $myts->displayTarea($article->getBanner(), 1);
if (!$banner || '' == $banner) {
    $banner = ' ';
}
$xoopsTpl->assign('articlebanner', $banner);
if (XOOPS_COMMENT_APPROVENONE != $xoopsModuleConfig['com_rule']) {
    $showcomments = 1;
} else {
    $showcomments = 0;
}
$xoopsTpl->assign('showcomments', $showcomments);
$xoopsTpl->assign('lang_printerpage', _AMS_NW_PRINTERFRIENDLY);
$xoopsTpl->assign('lang_sendstory', _AMS_NW_SENDSTORY);
$xoopsTpl->assign('lang_on', _ON);
$xoopsTpl->assign('lang_postedby', _POSTEDBY);
$xoopsTpl->assign('lang_reads', _READS);
$xoopsTpl->assign('showfull', true);
$xoopsTpl->assign('admin', false);
$xoopsTpl->assign('xoops_sitename', $myts->htmlSpecialChars($article->title()));
$xoopsTpl->assign('xoops_pagetitle', ' v.' . $article->version());

include_once XOOPS_ROOT_PATH.'/footer.php';
