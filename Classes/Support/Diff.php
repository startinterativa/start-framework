<?php
    namespace StartInterativa\StartFramework\Support;

    class Diff extends \StartInterativa\StartFramework\Base\Singleton {

        public function logDiff($before, $after) {
            $arrayBefore = get_object_vars($before);
            $arrayAfter = get_object_vars($after);

            $log = '';
            
            foreach ($arrayBefore as $attr => $value) {
                if($arrayAfter[$attr] != $value) {
                    if ($attr == 'data') {
                        $value = date('d/m/Y H:i', (int)$value);
                        $arrayAfter[$attr] = date('d/m/Y H:i', (int)$arrayAfter[$attr]);
                    }
                    $log .= "Alterou ".$attr. ": " . $value . " -> " . $arrayAfter[$attr].". ";
                }
            }
            
            if(empty($log)) {
                $log = "Sem alterações";
            }
            
            return $log;
        }
    }

?>
