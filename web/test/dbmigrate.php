<?php
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
	
	function process_document($uri)
	{
		global $enlace;
		$s_sql = "select id from publicacion where publicacion.file_name = '$uri'";
		$result = $enlace->query($s_sql);
		if ($result->num_rows<=0)
		{
			$doc = file_get_contents("C:\\Users\\CHAMPY\\Documents\\Maestria\\Serviciobecario\\04_REMERI\\output\\$uri");
			$cdoc = utf8_encode(substr($doc,0,100));
			$cdoc = str_replace("'","",$cdoc);
			$cdoc = str_replace('"',"",$cdoc);
			$sql = "insert into publicacion (titulo, file_name) values ('$cdoc','$uri')";
			$r = $enlace->query($sql);
			$did = $enlace->insert_id;
			 if (!$did)
			 {
				print $sql;
				print $enlace->error;
			}
			return $did;
		} 
		else
		{
			$row = $result->fetch_row();
			if (((int)$row[0]) == 0)
			{
				print "ERROR $uri".PHP_EOL;
				var_dump($row);
			}
			return (int) $row[0];
		}
		
	}
	
	function process_group_name($renglon,$group_level,$group_relation)
	{
		global $enlace;
		$i = 0;
		$full_name = array();
		print "$group_level ";
		foreach($renglon->childNodes as $child)
		{
			if($child instanceOf DOMText){
				if ($i == 0)
				{
					$i++;
				} else {
					$full_name[] = create_word($child->nodeValue);
					print "$child->nodeValue ";
				}
			}
		}
		$sql = "insert into $group_level values()";
		$c_niv = $enlace->query($sql);
		$gid = $enlace->insert_id;
		foreach($full_name as $sub_name)
		{
			$cn_sql = "insert into $group_relation (palabra_id, ${group_level}_id) values ('$sub_name','$gid')";
			$enlace->query($cn_sql);
		}
		print PHP_EOL;
		return $gid;
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
	
	$enlace =  new mysqli('localhost', 'root', 'root', 'tesis');
	
	
	$doc = new DOMDocument();
	$doc->loadHTMLFile("C:\\Users\\CHAMPY\\Documents\\Maestria\\Serviciobecario\\04_REMERI\\output\\ifs_abstr_ghsom1_1_1_0_0.html");
	$renglones = $doc->getElementsByTagName('td');
	foreach($renglones as $renglon)
	{
		print "Nivel 1 ";
		$g1id = process_group_name($renglon,"nivel_1","nombre_nivel_1");
		$addr = $renglon->getElementsByTagName('a');
		foreach($addr as $dress)
			$uri = $dress->getAttribute('href');
		$g2id = process_layer_2($uri,$g1id);
	}
		$enlace->close();

?>