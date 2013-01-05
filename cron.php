<?php
date_default_timezone_set('America/Sao_Paulo');
//Definição do diretório que será monitorado para modificações;
$dir = "./";
//Variáveis que armazenarão as informações dos arquivos;
$cache = null;
$modf = null;
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != ".." && $file != "Thumbs.db") {
                if ($file == "cron.appcache") {
                    //Se existir o arquivo appcache, é armazenada a informação da última modificação na variável;
                    $cache .= filemtime($dir.$file);
                }
                if ($file == "index.html" || $file == "style.css" || $file == "logo.png") {
                    //Armazena em um array a informação da última modificação dos arquivos da aplicação;
                    $modf .= filemtime($dir.$file).",";
                }
            }
        }
        closedir($dh);
        $arquivos = array($modf);
        foreach ($arquivos as $arquivo) {
            //Varre as informações dos arquivos que deverão ser armazenados no cache e compara com o arquivo cache manifest;
            if ($cache < $arquivo) {
                //Se a última modificação do arquivo cache manifest for anterior à algum dos arquivos é feita a atualização;
                $result = "Houve Atualização";
                $arqCache = "cron.appcache";
                $dt_modificacao = date("d/m/Y - H:i");
                $fh = fopen($arqCache, 'w') or die("Não é possível abrir o arquivo.");
                $contCache = "CACHE MANIFEST\n";
                $contCache .= "# modificado em ". $dt_modificacao ."\n";
                $contCache .= "\n";
                $contCache .= "CACHE:\n";
                $contCache .= "index.html\n";
                $contCache .= "logo.png\n";
                $contCache .= "style.css\n";
                $contCache .= "\n";
                $contCache .= "NETWORK:\n";
                $contCache .= "login.php\n";
                $contCache .= "\n";
                $contCache .= "FALLBACK:\n";
                $contCache .= "/ offline.html";
                fwrite($fh, $contCache);
                fclose($fh);                
            } else {
                $result = "Nada modificado";
            }
        }
        echo $result;
    }
} else {
    echo "Erro";
}                
?>