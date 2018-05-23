<?php
    namespace StartInterativa\StartFramework\Support;

    class Helper extends \StartInterativa\StartFramework\Base\Singleton {
        
        var $mail;
        var $twig;

        function __construct() {
            $this->mail = new \StartInterativa\StartFramework\Support\Email($this);
            $twigPath = SITEROOT . '/View';

            $loader = new \Twig_Loader_Filesystem($twigPath);
            $this->twig = new \Twig_Environment($loader, array('debug' => true));
            $this->twig->addExtension(new \Twig_Extension_Debug());
            $this->twig->addExtension(new \StartInterativa\StartFramework\Support\TwigHelper($this->twig, $this));
        }

        public function encodeArray($array, $to, $from) {
            foreach($array as $key => $value) {
                if(is_array($value)) {
                    $array[$key] = self::encodeArray($value, $to, $from);
                }
                else {
                    $array[$key] = mb_convert_encoding($value, $to, $from);
                }
            }

            return $array;
        }

        public function getDate($timestamp, $type = 'date') {
            switch ($type) {
                case 'date':
                    return date('d/m/Y', (int)$timestamp);
                case 'time':
                    return date('H:i', (int)$timestamp);
                case 'datetime':
                    return date('d/m/Y - H:i', (int)$timestamp);
            }
        }

        public function dataInterval($dataSaida, $dataEntrada) {
            $time_inicial = strtotime($dataSaida);
            $time_final = strtotime($dataEntrada);
            $diferenca = $time_final - $time_inicial;
            return (int)floor( $diferenca / (60 * 60 * 24));
        }

        public function dateToTimestamp($data) {
            if(!empty($data)) {
                list($day, $month, $year) = explode('/', $data);
                return mktime(0, 0, 0, $month, $day, $year);
            }
            return false;
        }

        public function timestampToDate($timestamp) {
            $ret = array();
            $ret['data'] = date('d/m/Y', $timestamp);
            $ret['hora'] = date('H:i', $timestamp);
            return $ret;
        }
        
        public function getMysqlDate($data) {
            return date("Y-m-d H:i:s",strtotime(str_replace('/','-',$data)));
        }

        public function processDate($date) {
            $arrayDate = explode("/", $date);
            $processedDate['original'] = $date;
            $processedDate['timestamp'] = strtotime(str_replace("/", "-", $date));
            $processedDate['mesano'] = $arrayDate[1] . "-" . substr($arrayDate[2], 0, 4);
            return $processedDate;
        }
        
        public function getMysqlFloatFormat($number) {
            $val =  str_replace(".", "", $number);
            $val =  str_replace(",", ".", $val);
            return $val;
        }

        public function getTextFromPostagemLabel($label) {
            $meses = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
            $labelArray = explode("-", $label);
            $pos = $labelArray[0]-1;
            if($pos > 11) {
                die('Mês não existente');
            }
            return array('mes'=>$meses[$pos], 'ano'=>$labelArray[1]);
        }

        public function getClientIp() {
            $ipaddress = '';
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }

        public function getServerProtocol() {
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                return 'https://';
            }
            return 'http://';
        }

        public function getPageTitle() {
            $title = "Social";
            if (isset($_GET['route']) && $_GET['route'] != 'sair') {
                $temp = preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $_GET['route']);
                $title .= " - ". ucfirst($temp);
            }
            if (intval(\Controller\Login::isLogged()) == 0){
                $title .= " - Entrar";
            }
            return $title;
        }

        public function getMessage($controller, $status, $id, $replace = null) {
            $status = intval($status);
            $messages = file_get_contents('messages.json');
            $messages = json_decode($messages, true);
            $message = $messages[$controller][$status][$id];
            if($replace) {
                $message = str_replace('%s', $replace, $message);
            }
            return $message;
         }

        public function isAllowedUser($types, $die = true){
            $is = false;
            foreach ($types as $type) {
                if(isset($_SESSION['login']['tipo']) && $_SESSION['login']['tipo'] == $type) {
                    $is = true;
                }
            }

            if (!$is && $die){
                $this->redirect404();
            }
            return $is;
        }

        public function isCurrentCliente($id) {
            if (($_SESSION['login']['tipo'] == 'cliente') && ($_SESSION['login']['id'] != $id)) {
                $this->redirect404();
            }
        }

        public function isNotUser() {
            if (isset($_SESSION['login']['tipo']) && $_SESSION['login']['tipo'] != 'cliente') {
                return true;
            }
            return false;
        }

        public function getProjectVersion() {
            $composerFile = file_get_contents('composer.json');
            $composerFile = json_decode($composerFile, true);
            return $composerFile['version'];
        }

        public function getMoneyFormat($val) {
            return 'R$ ' . number_format($val,2,",",".");
        }

        public function renderHeader($twig, $data) {
            if (intval(\StartInterativa\StartFramework\Core\Login::isLogged()) != 0) {
                echo $this->render($twig, $data);
            } else {
                echo $this->render($twig, $data);
            }
        }

        public function render($twig, $values = array()) {
            echo $this->twig->render($twig.'.twig', $values);
        }

        public function processHTML($twig, $values = array()) {
            return $this->twig->render($twig.'.twig', $values);
        }

        public function renderPage(\StartInterativa\StartFramework\Base\Controller $controller) {
            if ($controller->type == 'pdf') {
                $this->renderPDF($controller->page, $controller->data);
            } else {
                $this->renderHeader($controller->header, $controller->data['header']);
                $this->render($controller->page, $controller->data['body']);
                $this->render($controller->footer, $controller->data['footer']);
            }
        }
        
        public function renderPDF($template, $data) {
            $dompdf = new \Dompdf\Dompdf();
            $html = self::processHTML($template, $data['body']);
            $dompdf->loadHtml($html);
            $dompdf->render();
            $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
        }

        public function getProjectPath() {
            return getcwd();
        }

        public function newHashCadastro($id) {
            $hash['valor'] = md5($id);

            $stmt = $GLOBALS['db']['conexao']->prepare("INSERT INTO hash_cadastro (hash, empresa) VALUES(:hash, :empresa);");
            $stmt->bindValue(':hash', $hash['valor'], \PDO::PARAM_STR);
            $stmt->bindValue(':empresa', $id, \PDO::PARAM_INT);

            $hash['resposta'] = $stmt->execute();
            $hash['link'] = self::getServerProtocol() . $_SERVER['SERVER_NAME'] ."/cadastro.php?hash=".$hash['valor'];
            return $hash;
        }

        public function getImageObject($localPath) {
            $img = array();
            if (substr($localPath, 0, 4) == 'http') {
                return $localPath;
            }

            $img['filepath'] = $localPath;
            $img['url'] = self::getBaseUrl() ."/". $localPath;

            return $img;
        }

        public function requireId() {
            if(!isset($_GET['id'])) $this->redirect404();
        }

        public function reloadPage() {
            header("Location: {$_SERVER['PHP_SELF']}");
        }

        public function getBaseUrl() {
            return self::getServerProtocol() . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }

        public function addCookie($key, $value) {
            setcookie($key, $value, time()+3600 , '/');
        }

        public function getUrl($params = null) {
            $url = self::getBaseUrl();
            if($params) {
                $params = explode(",", $params);
                foreach ($params as $param) {
                    $url .= "/" . $param;
                }
            }
        	return $url;
        }
        
        public function redirect($url = null, $data = null){
            if($data) {
                setcookie('alert', serialize($data), time()+3600, '/');
            }
            
            if(substr($url, 0, 4) !== 'http') {
                $url = $this->getUrl($url);
            }
            header('Location: ' . $url);
        }

        public function redirect404() {
            header('HTTP/1.0 404 Not Found');
            header('Location: /404.html');
        }

        public function getCurrentUrl() {
            return self::getBaseUrl().$_SERVER['REQUEST_URI'];

        }

        public function getQueryWhere(&$query, $where = null) {
            if($where) {
                $query .= " WHERE " . implode(" AND ", $where);
            }
        }

        public function debug($var, $json = false) {
            if($json) {
                header('Content-type: application/json');
                echo json_encode($var);
            } else {
                var_dump($var);
            }
        }

        public function debugPDO($stmt) {
            if (!$stmt->execute()) {
                print_r($stmt->errorInfo());
            }
        }

        public function log(\Model\Object\Log $log) {
            $logDAO = \Model\DAO\Log::getInstance($this);
            $logDAO->insert($log);
        }

        public function getIdParam() {
            if($_SESSION['login']['tipo'] == 'cliente') {
                return $_SESSION['login']['id'];
            }

            if(isset($_GET['id'])) {
                return $_GET['id'];
            }
            return false;
        }

        public function getStatusLabel($status) {
            $status = (int)$status;
            switch ($status) {
                case 0:
                    return "Não revisada";
                case 1:
                    return "Pendente de Aprovação";
                case 2:
                    return "Agendada";
                case 3:
                    return "Publicada";
                case 4:
                    return "Reprovada";
                case 5:
                    return "Erro";
                case 6:
                    return "Deletada";
            }
        }
    
    }
?>
