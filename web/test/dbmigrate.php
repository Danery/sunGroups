<?php
require '../../vendor/autoload.php';
	function process_layer_2($uri, $p_id)
	{
		global $enlace;
		$doc = new DOMDocument();
		$doc->loadHTMLFile("C:\\Users\\CHAMPY\\Documents\\Maestria\\Serviciobecario\\04_REMERI\\output\\$uri");
		$renglones = $doc->getElementsByTagName('td');
		
		foreach($renglones as $renglon)
		{
			print "Nivel 2 ";
			$gid = process_group_name($renglon,"nivel_2","nombre_nivel_2");
			$cn_sql = "insert into nivel_2_a_nivel_1 (nivel_2_id, nivel_1_id) values ('$gid','$p_id')";
			$enlace->query($cn_sql);
			$documentos = $renglon->getElementsByTagName('a');
			foreach($documentos as $documento)
			{
				$did = process_document($documento->getAttribute('href'));
				if ($did != 0)
					$cn_sql = "insert into publicacion_a_nivel_2 (nivel_2_id, publicacion_id) values ('$gid','$did')";
				else 
					print "error!!!";
				$enlace->query($cn_sql);
			}
		}	
		return $gid;
	}
	
	
use RedBean_Facade as R;
R::setup('mysql:host=localhost;dbname=sunburst','root','root');
R::$writer->setUseCache(true);
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
            $ext = end(explode('.',$addr));
            if ($ext == "html") {
                process_layer('raw/output/'.$addr,$group);
            } else {
                process_document($addr, $parent);
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
    $doc = file_get_contents("raw/output/{$uri}");
    /* $cdoc = utf8_encode(substr($doc,0,100));  */
    $cdoc = substr($doc,0,100);
    $cdoc = str_replace("'","",$cdoc);
    $cdoc = str_replace('"',"",$cdoc);
    $document = R::dispense("document");
    $document->title = $cdoc;
    $document->author = "";
    $document->year = "";
    $document->institution = "";
    $document->uri = $uri;
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