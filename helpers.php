<?php

function _e($html)	{
	return htmlspecialchars($html);
}

function splitLines($text) {
	$text = str_replace("\r", "", $text);
	return array_filter(explode("\n", $text));
}

function paginate($url, $page, $tpages) {
    $adjacents = 3;
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $out = "<ul class='pagination'>";
    // previous
    if ($page == 1) {
        // $out.="<li><a href='#'>".$prevlabel."</a>\n</li>";
    } elseif ($page == 2) {
        $out.="<li><a href=\"".$url."\">".$prevlabel."</a>\n</li>";
    } else {
        $out.="<li><a href=\"".$url."&page=".($page - 1)."\">".$prevlabel."</a>\n</li>";
    }
    $pmin=($page>$adjacents)?($page - $adjacents):1;
    $pmax=($page<($tpages - $adjacents))?($page + $adjacents):$tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out.= "<li class=\"active\"><a href=''>".$i."</a></li>\n";
        } elseif ($i == 1) {
            $out.= "<li><a href=\"".$url."\">".$i."</a>\n</li>";
        } else {
            $out.= "<li><a href=\"".$url. "&page=".$i."\">".$i. "</a>\n</li>";
        }
    }
    
    if ($page<($tpages - $adjacents)) {
    	$out.= "<li><a>...</a></li><li><a style='' href=\"" . $url."&page=".$tpages."\">" .$tpages."</a>\n</li>";
    }
    // next
    if ($page < $tpages) {
        $out.= "<li><a href=\"".$url."&page=".($page + 1)."\">".$nextlabel."</a>\n</li>";
    } else {
        // $out.= "<span style='font-size:11px'>".$nextlabel."</span>\n";
    }
    $out.= "</ul>";
    return $out;
}