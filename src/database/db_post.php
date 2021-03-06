<?php
    include_once('../includes/database.php');

    function addVote($post_id, $user_id, $vote)
    {
        $db = Database::instance()->db();
        $stmt = $db->prepare("INSERT INTO vote VALUES(?, ?, ?)");
        $stmt->execute(array($post_id, $user_id, $vote));
    }

    function getVotes($post_id){
        $db = Database::instance()->db();
        $stmt = $db->prepare("SELECT COUNT(*) as numEntries FROM vote WHERE post_id = ?");
        $stmt->execute(array($post_id));
        $res = $stmt->fetch()['numEntries'];

        if($res == 0)
            return 0;

        $stmt = $db->prepare("SELECT SUM(vote) as num_votes FROM vote WHERE post_id = ?");
        $stmt->execute(array($post_id));
        return $stmt->fetch()['num_votes'];
    }

    function remVote($post_id, $user_id)
    {
        $db = Database::instance()->db();
        $stmt = $db->prepare("DELETE FROM vote WHERE post_id = ? AND user_id = ?");
        $stmt->execute(array($post_id, $user_id));
    }

    function lastVote($user_id, $post_id){
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT vote FROM vote WHERE user_id = ? AND post_id = ?');
        $stmt->execute(array($user_id, $post_id));
        return $stmt->fetch()['vote'];
    }

    function getAllTags(){
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT * FROM tag');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function getStoryTags($story_id){
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT tag_text FROM tag WHERE tag_id IN (SELECT tag_id FROM post_tag WHERE post_id = ?)');
        $stmt->execute(array($story_id));
        return $stmt->fetchAll();
    }

    function getNumComments($post_id){

        $numComments = 0;

        $comments = getChildComments($post_id);
        $result = getNumChildComments($post_id);

        $numComments+=$result;

        foreach($comments as $comment){
            $numComments+=getNumComments($comment['post_id']);
        }

        return $numComments;
    }

    function getChildComments($post_id, $order = "post_date", $asc_desc = "DESC")
    {
        $db = Database::instance()->db();
        $stmt = $db->prepare("SELECT * FROM post WHERE post_father = ? ORDER BY $order $asc_desc");
        $stmt->execute(array($post_id));
        return $stmt->fetchAll();
    }

    function getNumChildComments($post_id){
        $db = Database::instance()->db();
        $stmt = $db->prepare('SELECT COUNT(*) as numComments FROM post GROUP BY post_father HAVING post_father = ?');
        $stmt->execute(array($post_id));
        $res = $stmt->fetch()['numComments'];

        return $res == 0 ? 0 : $res;
    }