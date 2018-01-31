<?php
/**
 * Description of Resultaat
 *
 * @author G. Hoogendoorn
 * @version 0.1
 */
class Resultaat {
    private $punt;
    private $score;

    public function Resultaat( $score='', $punt=''){
        if ($this->checkPunt($punt)){
            $this->punt = $punt;
        }
        if ($this->checkScore($score)){
            $this->score = $score;
        }
    }

    public function getPunt(){
        return $this->punt;
    }

    public function getScore(){
        return $this->score;
    }

    public function setPunt($punt){
        if ($this->checkPunt($punt)){
            $this->punt = $punt;
            return TRUE;
        }
        return FALSE;
    }

    public function setScore($score){
        if( $this->checkScore($score)){
            $this->score = $score;
            return TRUE;
        }
        return FALSE;
    }

    private function checkPunt($punt){
        if ((strlen($punt) > 0) && is_numeric($punt)){
            return TRUE;
        }
        return FALSE;
    }

    private function checkScore($score){
        if ( (strlen($score)>0) && is_numeric($score)){
            return TRUE;
        }
        return FALSE;
    }
    
    public function  __toString() {
        return "$this->punt : $this->score";
    }
}

class TestResultaat {
    public function TestResultaat(){

        $test = new Resultaat();

        if ( !$test->setPunt(1) ) {
            echo "Fout punt <br />";
        }


        if ( !$test->setScore(3) ) echo "Fout score <br />";
        echo $test.'<br />';

        echo "<hr/>Test Set Punt / Score";
        echo "<pre>";
        var_dump( $test );
        echo "</pre>";
    }
}
?>
