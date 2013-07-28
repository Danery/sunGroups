<?php
namespace sunburst;
use ReadBean_Facade as R;

function getGroup($id) {
    $group = R::find('grupo',$id);
    if (!$group->id)
        return null;
}
?>