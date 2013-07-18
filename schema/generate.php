<?php
  use RedBean_Facade as R;
  function create_book() {
    $book = R::dispense('libro');
    $book->title ="Dummy title";
    R::store($book);
  }
?>
