<?php
require '../../vendor/autoload.php';
use RedBean_Facade as R;
R::setup('mysql:host=localhost;dbname=sunburst','root','root');
R::$writer->setUseCache(true);
R::debug(false);

$institutions = array("UASLP", "UDLAP", "UAEH", "UV", "UAEMEX", "ITESM", "UDG",
						"UCSJ");
$types = array("tesis digital", "documento", "artículo", "elemento");

$authors = file("names.dat", FILE_IGNORE_NEW_LINES);


process_layer("raw/output/ReMeRi_1_1_0_0.html");
R::close();


	
function process_layer($file, $parent = null) {
    $doc = new DOMDocument();
    $doc->loadHTMLFile($file);
    $renglones = $doc->getElementsByTagName('td');
    foreach($renglones as $renglon) {
        $name = process_group_name($renglon);
        if ($name) {
            $group = R::dispense("grupo");
            $group->name = $name;
            $id = R::store($group);
            if ($parent) {
                $parent->ownGroup[] = $group;
                R::store($parent);
            }
            print $name.PHP_EOL;
            $addr = $renglon->GetElementsByTagName('a')->item(0)->getAttribute('href');
			$splitname = explode('.',$addr);
            $ext = end($splitname);
            if ($ext == "html") {
                process_layer('raw/output/'.$addr,$group);
            } else {
                process_document($addr, $group);
            }           
        }
    }
}
        
function process_group_name($renglon) {
    $i = 0;
    $full_name = "";
    foreach($renglon->childNodes as $child) {
        if($child instanceOf DOMText) {
            if ($i == 0) {
                $i++;
            } else {
                $full_name .= " ".$child->nodeValue;
            }
        }
    }
    print $full_name;
    return trim($full_name);
}

function process_document($uri, $parent)
{
	global $authors;
	global $types;
	global $institutions;
    $doc = file_get_contents("raw/output/{$uri}");
    $cdoc = utf8_encode(substr($doc,0,100));  
    //$cdoc = substr($doc,0,100);
    $cdoc = str_replace("'","",$cdoc);
    $cdoc = str_replace('"',"",$cdoc);
    $document = R::dispense("document");
    $document->name = $cdoc;
	$rauthor = array_rand($authors);
    $document->author = $authors[$rauthor];
    $document->year = rand(1564,2013);
	$rinst = array_rand($institutions);
    $document->institution = $institutions[$rinst];
    $document->uri = $uri;
	$rtype = array_rand($types);
	$document->type = $types[$rtype];
    $id = R::store($document);
    $parent->ownDocument[] = $document;
    R::store($parent);
    return $id;
}
	
	
function create_word($word)
{
    global $enlace;
    $sql = "select id from palabra where palabra.palabra = '$word'";
    $c_sql = "insert into palabra (palabra) values ('$word')";
    $result = $enlace->query($sql);
    if ($result->num_rows<=0)
		{
			$insert = $enlace->query($c_sql);
			return $enlace->insert_id;
		} else {
        $row = $result->fetch_row();
        return $row[0];
    }
}
?>