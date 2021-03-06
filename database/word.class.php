<?php

class Word {
  public $id;
  public $list;
  public $language1;
  public $language2;
  public $comment;
  public $answers;

  public function __construct($id, $list, $language1, $language2, $comment) {
    $this->id = intval($id);
    $this->list = intval($list);
    $this->language1 = htmlspecialchars_decode($language1);
    $this->language2 = htmlspecialchars_decode($language2);
    $this->comment = htmlspecialchars_decode($comment);
  }

  // load answers
  // 
  // @param unsigned int user_id: id of the user (a word can be answered by multiple users)
  //
  // @return Answer[]: answers given to the word by the passed user
  function load_answers($user_id) {
    $this->answers = array();
    global $con;
    $sql = "SELECT * FROM `answer` WHERE `user` = ".$user_id." AND `word` = ".$this->id.";";
    $query = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($query)) {
      array_push($this->answers, new Answer($row['id'], $row['user'], $row['word'], $row['correct'], $row['direction'], $row['type'], $row['time']));
    }
  }

  static function get_by_id($id) {
    global $con;
    $sql = "SELECT * FROM `word` WHERE `id` = ".$id." ORDER BY `id` DESC;";
    $query = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($query)) {
      return new Word($id, $row['list'], $row['language1'], $row['language2'], $row['comment']);
    }
  }
}

?>