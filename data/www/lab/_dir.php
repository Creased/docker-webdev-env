<?php

/**
 * @author Baptiste MOINE (Creased) <contact@bmoine.fr>
 * @project Projects List
 * @released 18/03/2016
 */

/*!
 * @param $dir
 * @param int $lvl
 * @return array
 */
function scan($dir, $lvl = 0) {
	// Niveau de récursion maximum non atteint
	if ($lvl <= 1) {
		$lvl++;
		$files = array();
		if (file_exists($dir)) {
			foreach (scandir($dir) as $f) {
				// Ignore les fichiers cachés
				if (!$f || $f[0] == '.') {
					continue;
				}

				// Construit un flux JSON pour lister les fichiers et dossiers
				if (is_dir($dir . "/" . $f)) {  // Dossier
					$files[] = array(
						"name" => $f,
						"type" => "folder",
						"path" => $dir . "/" . $f
					);
				} else {  // Fichier
					$files[] = array(
						"name" => $f,
						"type" => "file",
						"path" => $dir . "/" . $f,
						"extension" => strtolower(pathinfo($f)["extension"]),
						"size" => filesize($dir . "/" . $f)
					);
				}
			}
		}
		return $files;
	}
}

/*!
 * @param $dir
 * @param $recursive
 * @return array
 */
function parse($dir, $recursive) {
	setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
	$files = array();

	if (file_exists($dir)) {
		foreach (scandir($dir) as $f) {
			if (($f && $f[0] <> ".") && ((is_dir($dir . "/" . $f)))) {
				if ($recursive) {
					$files[] = array(
						"name" => basename($f),
						"files" => scan($f)
					);
				} else {
					$files[] = array(
						"name" => basename($f)
					);
				}
			}
		}
	}
	return $files;
}

$basedir = @dirname(__FILE__);  //!< Répertoire de base pour la liste des fichiers et répertoires
$recursive = false;  //!< Liste des fichiers et sous-répertoires
$response = parse($basedir, $recursive);  //!< Liste des fichiers et répertoires

ob_start();  //!< Initialise un tampon (buffer)

if (isset($response)) {
	foreach ($response as $fileIndex => $file) {
?>
							<a href="<?php echo $file["name"]; ?>" class="list-group-item dir"><?php echo $file["name"]; ?></a>
<?php
		if ($recursive && isset($file["files"][0])) {
?>
							<div class="list-group sub">
<?php
			foreach ($file["files"] as $subFileIndex => $subFile) {
				$fileType = (isset($subFile["extension"])) ? $subFile["extension"] : "dir";
?>
								<a href="<?php echo $subFile["path"]; ?>" class="list-group-item sub <?php echo $fileType; ?>"><?php echo $subFile["name"]; ?></a>
<?php
			}
?>
							</div>
<?php
		}
	}
}

$buffer = ob_get_contents();  //!< Sauvegarde le contenu de ce buffer dans une variable
ob_end_clean();  //!< Vide le buffer
?>
<!DOCTYPE html>
<html lang="fr" itemscope="itemscope" itemtype="http://schema.org/QAPage">
	<head>
		<!-- assign metadata -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<title>Projects List</title>

		<style media="all" type="text/css">
			/***********************
			 * GENERAL
			 * CONTAINER
			 * LIST
			***********************/

			/* GENERAL
			***********************/

			a {
				color: #126E8E;
				text-decoration: none;
			}

			a:hover,
			a:hover::after,
			a:hover::before {
				color: #218EB4;
				text-decoration: none;
				-moz-transition: all 100ms linear 0s;
				-moz-transition-property: color, background-color;
				-ms-transition: all 100ms linear 0s;
				-ms-transition-property: color, background-color;
				-o-transition: all 100ms linear 0s;
				-o-transition-property: color, background-color;
				-webkit-transition: all 100ms linear 0s;
				-webkit-transition-property: color, background-color;
				transition: all 100ms linear 0s;
				transition-property: color, background-color;
			}

			body {
				background-color: #F1F1F1;
				color: #3E3E3E;
				font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
				font-size: 14px;
				line-height: 1.4;
				margin: 0;
			}

			html {
				font-size: 10px;
			}

			h1 > .small {
				color: #386E81;
				font-size: .55em;
			}

			input[type="submit"],
			input[type="text"],
			textarea {
				background-color: #F9F9F9;
				border: 1px solid #D8D8D8;
				font-size: 1em;
				font-weight: 400;
				padding: 6px 12px;
				transition: all .3s ease;
				vertical-align: middle;
				white-space: nowrap;
			}

			input[type="submit"] {
				-moz-user-select: none;
				cursor: pointer;
				text-align: center;
			}

			input[type="submit"]:hover {
				background-color: #F1F1F1;
			}

			/* CONTAINER
			***********************/

			div.container {
				padding: 0 15px 15px;
			}

			/* LIST GROUP
			***********************/

			div.list-group:not(.sub) {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAACCAYAAACZgbYnAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QTkzQTc1QzZENTQzMTFFNEI5QkFEMDlGRTQ4NDMyQzQiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QTkzQTc1QzVENTQzMTFFNEI5QkFEMDlGRTQ4NDMyQzQiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PvWgR54AAAAVSURBVHjaYvj//z8Dw5kzZ/4DBBgAIB0GYY+p62MAAAAASUVORK5CYII=) repeat-y scroll left 14px top #F1F1F1;
				margin-bottom-width: 20px;
				padding-left: 30px;
			}

			div.list-group.sub {
				margin-left: 30px;
			}

			/* LIST GROUP ITEM
			***********************/

			div.list-group a.list-group-item {
				background: #F1F1F1 url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAQJJREFUeNqUk70OREAQx4dcpVSIxiMpNEq9SzyMZhUShafz9QK+4vwnGVnO4f7JmFgzv5kdu8ayLFSWpSKimK6VRVH0/loFoCiKZZ7nS0PMagrxupmATNPEsLZtqWkatrquqaoqNsj3fcTFeZ4rvQEGjOPIL4Zh7Mw0TfbQMAwUBAFiY7XqEUBMYgAJw5AhaZqqDYAPApCq4gXgeR65rku2bVOSJMjhob+OgKOw1nUdD0yG7jjOlsOAvu9/AnQQkiVGcnYdoG2pdKfLDvRqutd12sHVFnTwbQdPtOtAzsE/EMk53YI+7UdDxF3AH7As61F1xMr9EUC2nvOY/lOGx0eAAQBQ5tYUOar6bQAAAABJRU5ErkJggg==) no-repeat scroll left 5px center;
				border: 1px solid #DDD;
				color: #555;
				display: block;
				padding: 10px 30px;
				position: relative;
			}

			div.list-group a.list-group-item::before,
			div.list-group div.list-group.sub a.list-group-item.sub::before,
			div.list-group div.list-group.sub:last-child a.list-group-item.sub:last-child::after {
				bottom: 0;
				content: "";
				left: -47px;
				position: absolute;
				top: 0;
				width: 46px;
			}

			div.list-group a.list-group-item::before {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAqCAYAAAC6EtuqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NTc5REVDOUFENTQ2MTFFNDk5QTU4Q0IwOThBQUY0NTIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NTc5REVDOTlENTQ2MTFFNDk5QTU4Q0IwOThBQUY0NTIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PtwFy6cAAAA1SURBVHja7NChFQAACAJR3X81dsLnCloIR7n2A227PmsAAACAGKAkeZFrOREAAAAgCRgBBgBKYa2vLpmT2wAAAABJRU5ErkJggg==) no-repeat scroll left 30px top transparent;
			}

			div.list-group a.list-group-item:not(.sub):last-of-type:last-child::before {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAqCAYAAAC6EtuqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6REMxRDdCQzJENTQ5MTFFNDg2ODdFRDBENkVBRjI0MkIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6REMxRDdCQzFENTQ5MTFFNDg2ODdFRDBENkVBRjI0MkIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PngpAAoAAABFSURBVHja7NTBCQAwCARBTf+l2dMltuAJ+awg/oZ9mZLCmqpSI9PN7wXnGekENCAXoIACCmLxH0yvXQAAAAAAsAhcAQYAKmTlV93PzF4AAAAASUVORK5CYII=) no-repeat scroll right top #F1F1F1;
			}

			div.list-group a.list-group-item:hover,
			div.list-group div.list-group.sub a.list-group-item.sub:hover {
				background-color: #F5F5F5 !important;
				color: #555;
				text-decoration: none;
			}

			div.list-group a.list-group-item.dir {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAA3NCSVQICAjb4U/gAAABhlBMVEX//v7//v3///7//fr//fj+/v3//fb+/fT+/Pf//PX+/Pb+/PP+/PL+/PH+/PD+++/+++7++u/9+vL9+vH79+r79+n79uj89tj89Nf889D88sj78sz78sr58N3u7u7u7ev777j67bL67Kv46sHt6uP26cns6d356aP56aD56Jv45pT45pP45ZD45I324av344r344T14J734oT34YD13pD24Hv03af13pP233X025303JL23nX23nHz2pX23Gvn2a7122fz2I3122T12mLz14Xv1JPy1YD12Vz02Fvy1H7v04T011Py03j011b01k7v0n/x0nHz1Ejv0Hnuz3Xx0Gvz00buzofz00Pxz2juz3Hy0TrmznzmzoHy0Djqy2vtymnxzS3xzi/kyG3jyG7wyyXkwJjpwHLiw2Liw2HhwmDdvlXevVPduVThsX7btDrbsj/gq3DbsDzbrT7brDvaqzjapjrbpTraojnboTrbmzrbmjrbl0Tbljrakz3ajzzZjTfZijLZiTJdVmhqAAAAgnRSTlP///////////////////////////////////////8A////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////9XzUpQAAAAlwSFlzAAALEgAACxIB0t1+/AAAAB90RVh0U29mdHdhcmUATWFjcm9tZWRpYSBGaXJld29ya3MgOLVo0ngAAACqSURBVBiVY5BDAwxECGRlpgNBtpoKCMjLM8jnsYKASFJycnJ0tD1QRT6HromhHj8YMOcABYqEzc3d4uO9vIKCIkULgQIlYq5haao8YMBUDBQoZWIBAnFtAwsHD4kyoEA5l5SCkqa+qZ27X7hkBVCgUkhRXcvI2sk3MCpRugooUCOooWNs4+wdGpuQIlMDFKiWNbO0dXTx9AwICVGuBQqkFtQ1wEB9LhGeAwDSdzMEmZfC0wAAAABJRU5ErkJggg==) no-repeat scroll left 5px center #F1F1F1;
			}

			div.list-group a.list-group-item.png,
			div.list-group a.list-group-item.jpg,
			div.list-group a.list-group-item.tif,
			div.list-group a.list-group-item.tiff,
			div.list-group a.list-group-item.psd,
			div.list-group a.list-group-item.bmp,
			div.list-group a.list-group-item.svg,
			div.list-group a.list-group-item.jpeg,
			div.list-group a.list-group-item.gif {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAatJREFUeNqkU79LAzEYfclVe9dfohZswUGHOjvUVRSh4Ca4FdpdXSr0zxDc7FawUHAUhw7SzcXFxUUcHOyBDsUWKbT3o5eYpLZ4tCqlgeTuu3zvfe99lxDOOWYZAblUKpUL8TiaElvK5/PHkArK5TJnjE01JUZilQLxQVEeXJmAx6BxC4TocCkFBYfc1cSUZu0+Qy23NsIogn6/r4KETmG93QKtO5DoFmhyF9SICyAXhAOG7iB1hPERtBvXsF7PoTldGF2GqB7DwlIGmqaDwAMXJK5LJhNIPxGvC7sTge3MwfZMLK82kAgL+fMijQsTAus4XOX6CFzXVUGIBvD0bqD5aaAX0BFOLmLTCIHKBuC7sjawMMT4FKTTe1jR58Dvb6Dt57CR2kEwFgRTZ4WoHjgefldgfpwiuh5H68EE0S/x+HIGx+0MSgowFZ10GMdh6nlcgRy21YZlt8AzBpjbhPjhCGgR3+mhvDfeRMkmZZ1s1/Df0SZChcwZKqBy8TxPBdVqFfV6XSVMmnJP5vzE+CwUCoU/q2ez2dG7z4JgKxWLxakvk7I063X+EmAAIPsjzZFOpdwAAAAASUVORK5CYII=) no-repeat scroll left 5px center #F1F1F1;
			}

			div.list-group a.list-group-item.js {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAhZJREFUeNqEUk1oE0EU/naz2f4YQ0kWUmKs2LSXtmAPYsGDSkDQIoggKJ4UoaBXqdqDiODfSVDEQz14r0JF9KgsWMUfLFG0ii1NbAmEmhaa7q66u7PjmyQbIt2aB4+Z9zHfm2++NxJzVuGHfjXeR8sVyqP4T3ihzdxu7ckzpeOQAvAaWdtCy8vuzKVYaugM5JAaSF6amcTH5+MSQ2Q7lTfqDSgudu44Hts6dBp2eQrc+7OOrEZ3YvHNXTg8CkgVKKOA1xscSA6egGt+AXdXg7VzD1ZpDsOjOcmHSIHn77va4ykw4y0ddALYMuEumG3aDRyBiqICqLKomEEX/QpIs9Ig3B5X9WudXT5PFrIqCSw41hIkibowKzipgda7X5w95vPkqokVH56VZnUomwYItwLTMaaxbfeImNA5/XpKFbxGBeOF6Qlyl3AxgQAFzPiMcFsLEgNHEr6Kugf7xnJZa3n+UfHTY7RohzdU4a69Q6JvWFw48q8HVRWX868fMO4Jo+1gFWtZRLSkODsoOIp+M80bh+X+LmPqzsF1Q2yLpdGf6acnhuFWv3+Ekwf0D2DsGZ2JSKEwOHM2/P+vbu8iVWmo9LzlhQ8CyqHWoFj+8bQHTaI1qqHwXUZ3sgOL7x8KaLJmIp7MvrjXjI/ezFmUi98wr9+H+XPOIOiWr2DMLOVXshPnT9K+qRLPC4nl1N4LXwuiwV8BBgBa7ylg5mm97AAAAABJRU5ErkJggg==) no-repeat scroll left 5px center #F1F1F1;
			}

			div.list-group a.list-group-item.css,
			div.list-group a.list-group-item.xml {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAc9JREFUeNqMUz2rGlEQPbsIonYWYmMjdhZiIwpWEe0EbbSzSrMB/0IgRdqAja+QCGIhImIpNukV7bRXLPxqTOH3V+654T52fcnjHTjcYe545sxcV6vVamUABt7HS6FQ+PLPm2q1+rjdbu+SNYLlx+OBZ+rX61UKbTYbrNdrydVqheVyKUmk02mIOqNSqZSfDeiXy0UGmqZZqOu6PInz+YxsNgtRa5QFPiSgSLCGIrlcToqUSqVXEZ0XSkB1VacS8Pl88Hq9cLvdKBaLFHtdus0s8AzmFosFttstZrOZzIVCISjX0sHpdPrvCJPJBN1uF/1+H36/X7LX68Hj8UCs4pvFAW3zWRSYH4/HyOfzYBNVl0qlYLfb0Ww2v/4QsJkdqJNCtE6rfMr5fC7dEMFgUO6Egsfj8ZNliWaORiNEo1Hs93sMh0Mkk0lJxszFYjGexhsH5hG4PJfLJUdQ/zzG5G63w+FwgM28UbNIOBxGq9WSM8fjcdTrdZmnC/6YyxRCL2+ekV0YBwIB2YENHA4HDOPv00+nU84uKQR+2fgt8AWcTqdlBFpPJBJot9vyPhKJyPxgMMD9fueI3xuNxm8tk8l85HO2OBT42el0PjP4I8AA7SVEpkV+DtMAAAAASUVORK5CYII=) no-repeat scroll left 5px center #F1F1F1;
			}

			div.list-group a.list-group-item.html {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAApdJREFUeNqEU11IU2EYfnbcmU6Hc4q06EdCNCt/iKipqBR1oYaBsgiiFsurRdC9EXQZ3VYT0rRWehGEXYgYjsoyMkz6MVTQUDZ0P8ffNbfzfzrfkTMUQR94eL/z8b4vz/ue7zEoigICn8/nVYMHu6Pd5XLd2nZDGhB2dXUpkiTtSpKj0qvXEFJ6I1EUtcgwDKLRqMZIJIJwOKyRoKmpieR5Ojo6vHpdqoEg8Pj+dwj9v5/A97VNjY/xZ/ETZEgwGAxaDs/zaG5uVnMFj1cFuTMQGfd7L1+k+dz+osPFOHfKiezMPMTZNcyFJzAw/hznj91AVVk9gsEgaJqGyWRCT0+PtpM0vuCHSZaVqRP2StRXX0U6nQFJEUCnmWC3FaC8oBavhh+gsqQRebn5sFgsMJvNcDgcGBkZOU1xLHenyF4Ojo2BF1nEE6v4OfkZgnom3+Z0Cy45WuEfe63tR9+RPhLFJpOenKwcrAtBDAy/QN/QU4SW5lUVgCRLECQOR+zHMTb9DhRFafsgkYDjOBjZJHsgElK3zqwglrWObGsGZgNLyLPtR8XRGi3xw2gfQkwgtUwdmwoSiYWTpXW4fe0hDuaXYXZmEciMYfDLSwTCs5AVGeNTH2FUTFoDnboCdQS2dybwS5NbUVIDQRaR4OJgxQ0wKwuYX5jGajyK8sK6lPytv1UdIdn+ZrDzbuGhUrx93w3RGMMGT0M2ihid8GN5NYRINIyzV1p2jEAUaO/ggju/lTJQnRZbJmz7skCnGyHwEv4tJ7DGxHGz+R4aaq9D9w2JVqsVbrd78yX6u5lnOclqNJxxY2MxDZPf5hALAlXFLXjU5kdjnWvb/FtHSJnC6XQqBHsZSicBqTFuMVO7+s73svMOe/8XYAB64aU0FZ9DVQAAAABJRU5ErkJggg==) no-repeat scroll left 5px center #F1F1F1;
			}

			div.list-group a.list-group-item.php {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAURJREFUeNqUUz1rhEAQHcXKRrhCbPxJEkxEsIiHRQoD1v4OGw0nJ5zBLuR3+VUegl9snA0rKneH9+Dx2J2ZtzuOyxFCAJGmaTiJC48R2bb9udpBA2SSJGQcx4fEnIkhq0HyzGgYBqpVVUFZlpRFUUCe55QITdMwz43jOGR1s0Hf91Q5jluR53mqiK7rQNd1zHXDCbsMGFkOmpimSU2CIAhnAwwwA3YqU2agqiooigKHwwE8z8MaV7hlsAXu1XUNbGKosizTGuHViP93QQbDPMMefIUvVNu2BdDfTuR6bWey9VaX8WlCdKy+75P5G7wfL6tTvi/H1XoZZ23iDfg9Bdv4cqzCvYIt7t2Aw57gSZxjAyRJAsdxQPj9+aB2lmWRLMugaZp5VMv3cktXLeBbwB9HFMVdt8BcrFkaRNN/7j7ZTfQnwAC4NwVyE+bF9gAAAABJRU5ErkJggg==) no-repeat scroll left 5px center #F1F1F1;
			}

			div.list-group a.list-group-item.sql {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAdhJREFUeNrEUzuLWmEQHeX6fgs+8AWRlBaBxcZaLbdd2G6bhd0/FEiVKp3Nlu4PiIXaaGdQiaLiBUHF92vnzN77ZUMqqwyMzHzOnJkzc8dyuVyoWq0Syz3rLetnepcw6yfD/s2qG/Yv1hfWH+VymTS8bLfb12g0WkwkEuTz+STKbreTx+MRe71eZ3a7XQb2crm8GY1Gd9Pp9IHdkgCcz+dir9cjr9dLDodDkpxOJ5lisVhov9+TAUD9fh//F+FrZlAul6Nms0m6rqtAriw2OgkGgwIUi8Uon89Tq9UiBTCfz0XD4TDF43HpAh24XC7RUCgk1PDOdInpSLwCmM1mFAgEpDqC3G63VIWeTiflQ+Afj0fJUQCaplGn05Egm80mfD/OAFUnk4mADwYDoYocBZBOpymbzVK9XqfxeCxc0eLHGYAeBBRLpRK12+2/Z7BYLIhXSalU6j/MANWvnQFyFABQsd9utyvtWa1WATE/HnQAGniPRCJUKBSoUqn8AUCS3++nZDL5DwVwx2wwRMxjtVqpOUCs+DErXSNmjnRwOBy+NhqNJ1TBCiHmTUA2mw0Nh0N5wx3UajXJUQDc0jNv4DvrI7tfjDxcX8SwdeOkITiCb7yJn3DeBBgANY8ClPuhFL4AAAAASUVORK5CYII=) no-repeat scroll left 5px center #F1F1F1;
			}

			/* SUB LIST GROUP ITEM
			***********************/

			div.list-group div.list-group.sub a.list-group-item.sub:first-child,
			div.list-group a.list-group-item + a.list-group-item {
				border-top-width: 0;
			}

			div.list-group div.list-group.sub:not(:last-child) a.list-group-item.sub:last-child {
				border-bottom-width: 0;
			}

			div.list-group div.list-group.sub a.list-group-item.sub::before {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAqCAYAAAC6EtuqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NTc5REVDOUFENTQ2MTFFNDk5QTU4Q0IwOThBQUY0NTIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NTc5REVDOTlENTQ2MTFFNDk5QTU4Q0IwOThBQUY0NTIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PtwFy6cAAAA1SURBVHja7NChFQAACAJR3X81dsLnCloIR7n2A227PmsAAACAGKAkeZFrOREAAAAgCRgBBgBKYa2vLpmT2wAAAABJRU5ErkJggg==) repeat-x scroll left 30px top transparent;
			}

			div.list-group div.list-group.sub a.list-group-item.sub.dir {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJISURBVDjLpZPLS5RhFIef93NmnMIRSynvgRF5KWhRlmWbbotwU9sWLupfCBeBEYhQm2iVq1oF0TKIILIkMgosxBaBkpFDmpo549y+772dFl5bBIG/5eGch9+5KRFhOwrYpmIAk8+OjScr29uV2soTotzXtLOZLiD6q0oBUDjY89nGAJQErU3dD+NKKZDVYpTChr9a5sdvpWUtClCWqBRxZiE/9+o68CQGgJUQr8ujn/dxugyCSpRKkaw/S33n7QQigAfxgKCCitqpp939mwCjAvEapxOIF3xpBlOYJ78wQjxZB2LAa0QsYEm19iUQv29jBihJeltCF0F0AZNbIdXaS7K6ba3hdQey6iBWBS6IbQJMQGzHHqrarm0kCh6vf2AzLxGX5eboc5ZLBe52dZBsvAGRsAUgIi7EFycQl0VcDrEZvFlGXBZshtCGNNa0cXVkjEdXIjBb1kiEiLd4s4jYLOKy9L1+DGLQ3qKtpW7XAdpqj5MLC/Q8uMi98oYtAC2icIj9jdgMYjNYrznf0YsTj/MOjzCbTXO48RR5XaJ35k2yMBCoGIBov2yLSztNPpHCpwKROKHVOPF8X5rCeIv1BuMMK1GOI02nyZsiH769DVcBYXRneuhSJ8I5FCmAsNomrbPsrWzGeocTz1x2ht0VtXxKj/Jl+v1y0dCg/vVMl4daXKg12mtCq9lf0xGcaLnA2Mw7hidfTGhL5+ygROp/v/HQQLB4tPlMzcjk8EftOTk7KHr1hP4T0NKvFp0vqyl5F18YFLse/wPLHlqRZqo3CAAAAABJRU5ErkJggg==) no-repeat scroll left 5px center transparent !important;
			}

			div.list-group div.list-group.sub:last-child a.list-group-item.sub:last-child {
				border-bottom: 1px solid #DDD !important;
			}

			div.list-group div.list-group.sub:last-child a.list-group-item.sub:last-child::before {
				background-color: transparent !important;
				z-index: 1;
			}

			div.list-group div.list-group.sub:last-child a.list-group-item.sub:last-child::after {
				background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAqCAYAAAC6EtuqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6REMxRDdCQzJENTQ5MTFFNDg2ODdFRDBENkVBRjI0MkIiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6REMxRDdCQzFENTQ5MTFFNDg2ODdFRDBENkVBRjI0MkIiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4MUY4NUMxMzNCRDVFNDExODZFNkU1MDM1MDUyMjU5MSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PngpAAoAAABFSURBVHja7NTBCQAwCARBTf+l2dMltuAJ+awg/oZ9mZLCmqpSI9PN7wXnGekENCAXoIACCmLxH0yvXQAAAAAAsAhcAQYAKmTlV93PzF4AAAAASUVORK5CYII=) no-repeat scroll left top #F1F1F1;
			}
		</style>
	</head>
	<body>
		<div id="content">
			<div class="container">
				<div class="row">
					<h1 class="page-header">{ Projects List } <span class="small">by Creased</span></h1>
					<div class="col-l-12 col-m-12 col-s-12">
						<div class="list-group">
<?php echo $buffer; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
