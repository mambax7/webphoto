<?php
// $Id: WebphotoD3commentContent.class.php,v 1.1 2008/09/03 02:44:53 ohwada Exp $

//=========================================================
// webphoto module
// 2008-09-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// WebphotoD3commentContent
// a class for d3forum comment integration
//=========================================================

/**
 * Class WebphotoD3commentContent
 */
class WebphotoD3commentContent extends D3commentAbstract
{
    /**
     * @param $link_id
     * @return array|string
     */
    public function fetchSummary($link_id)
    {
        $mydirname = $this->mydirname;
        if (preg_match('/[^0-9a-zA-Z_-]/', $mydirname)) {
            die('Invalid mydirname');
        }

        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $myts = MyTextSanitizer::getInstance();

        $moduleHandler = xoops_getHandler('module');
        $module = $moduleHandler->getByDirname($mydirname);

        // query
        $sql = 'SELECT * FROM ' . $db->prefix($mydirname . '_item');
        $sql .= ' WHERE item_id=' . (int)$link_id;
        $sql .= ' AND item_status > 0 ';
        $item_row = $db->fetchArray($db->query($sql));
        if (empty($item_row)) {
            return '';
        }

        // dare to convert it irregularly
        $summary = str_replace('&amp;', '&', htmlspecialchars(xoops_substr(strip_tags($item_row['item_description']), 0, 255), ENT_QUOTES));

        $ret = [
            'dirname' => $mydirname,
            'module_name' => $module->getVar('name'),
            'subject' => $myts->makeTboxData4Show($item_row['item_title']),
            'uri' => XOOPS_URL . '/modules/' . $mydirname . '/index.php?fct=photo&photo_id=' . (int)$link_id,
            'summary' => $summary,
        ];

        return $ret;
    }

    /**
     * @param $link_id
     * @return bool
     */
    public function validate_id($link_id)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        // query
        $sql = 'SELECT COUNT(*) FROM ' . $db->prefix($this->mydirname . '_item');
        $sql .= ' WHERE item_id=' . (int)$link_id;
        $sql .= ' AND item_status > 0 ';

        list($count) = $db->fetchRow($db->query($sql));
        if ($count <= 0) {
            return false;
        }

        return $link_id;
    }

    /**
     * @param     $mode
     * @param     $link_id
     * @param     $forum_id
     * @param     $topic_id
     * @param int $post_id
     * @return mixed
     */
    public function onUpdate($mode, $link_id, $forum_id, $topic_id, $post_id = 0)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql1 = 'SELECT COUNT(*) FROM ';
        $sql1 .= $db->prefix($this->d3forum_dirname . '_posts') . ' p ';
        $sql1 .= ' LEFT JOIN ';
        $sql1 .= $db->prefix($this->d3forum_dirname . '_topics') . ' t ';
        $sql1 .= ' ON t.topic_id=p.topic_id ';
        $sql1 .= ' WHERE t.forum_id=' . (int)$forum_id;
        $sql1 .= ' AND t.topic_external_link_id=' . (int)$link_id;

        list($count) = $db->fetchRow($db->query($sql1));

        $sql2 = 'UPDATE ' . $db->prefix($this->mydirname . '_item');
        $sql2 .= ' SET item_comments=' . (int)$count;
        $sql2 .= ' WHERE item_id=' . (int)$link_id;

        return $db->queryF($sql2);
    }

    // --- class end ---
}
