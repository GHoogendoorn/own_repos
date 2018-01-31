<?php
require_once 'cesuurtabel.php';
require_once 'uitvoertabel.php';

define ( "AANTAL_VOLDOENDE_PUNTEN",     10-6);
define ( "AANTAL_ONVOLDOENDE_PUNTEN",   6);
define ( "SHOW_TYPE_HTML_TABLE",        1);

/**
 * Description of Cesuur
 *
 * @author G. Hoogendoorn
 * @version 0.1
 */
class Cesuur {

    /**
     *
     * @var int Maximaal te behalen score
     */
    private $max_score;

    /**
     *  Tot wanneer heb je een 1
     *
     * @var int
     */
    private $min_score;

    /**
     *  Vanaf welke score heb je een 6
     *
     * @var int
     */
    private $helft_score;


    /**
     *  Vanaf wanneer heb je een 10
     *
     * @var int
     */
    private $min_max_score; // Vanaf wanneer heb je een 10

    /**
     *  Object dat de resultaten bevat van de Cesuur berekening
     *
     * @var CesuurTabel
     */
    private $cesuur_tabel;

    public function __construct() {
        
    }

    public function setMaxPunten( $max_punten ){
        if ( !is_numeric($max_punten)){
            throw new Exception('Geef een getal op<br />');
        }
        $this->max_score = $max_punten;
    }
    
    public function setMinMaxPunten( $punten){
        
        if ( !is_numeric($punten)){
            throw new Exception('Geef een getal op<br />');
        }
        $this->min_max_score = $punten;
    }

    public function setMinPunten( $min_punten ){
        if ( ! is_numeric($min_punten)){
            throw new Exception('Geef een getal op<br />');
        }
        $this->min_score = $min_punten;
    }

    public function setPuntenVoorEenZes( $helft_score ){
        if ( !is_numeric($helft_score)){
            throw new Exception('Geef een getal op<br />');
        }
        $this->helft_score = $helft_score;
    }

    public function bereken(){
        // Vul cesuur list op basis van min - max en helft
        try{

            $this->checkInputVars();

            $this->verdeelScoreOverPunten();
            
            // Now the numbur of scores is known create an score card
            $this->cesuur_tabel->reset();
            $this->cesuur_tabel->listToScoreCard();
        }

        //catch exception
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
            //echo $e->getMessage();
        }
    }
    
    public function getCesuurHTMLTable(){
        try {
            $output_table = new UitvoerTabel($this->cesuur_tabel->getScoreCard());
        
            return $output_table->getTable();
        }
        //catch exception
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
            //echo $e->getMessage();
        }
    }
    
    public function setCardTypeScorePerNumber(){
        if ( !is_object($this->cesuur_tabel)){
            $this->cesuur_tabel = new CesuurTabel();
        }
        $this->cesuur_tabel->setCardTypeScorePerNumber();
    }
    
    public function setCardTypeResultPerScore(){
        if ( !is_object($this->cesuur_tabel)){
            $this->cesuur_tabel = new CesuurTabel();
        }
        $this->cesuur_tabel->setCardTypeResultPerScore();
    }

    private function checkInputVars() {

        if (
             ((strlen($this->min_score) < 1) )          ||
             ( empty($this->helft_score) )             ||
             ( empty($this->max_score) )
            ) {

            // Error
            throw new Exception("Missing mandatory variable. (Min, Max en of Aantal punten voor een 6)");

        // Check min < helft < max
        } else if ( ($this->helft_score >= $this->max_score)    ||
                ($this->min_score  >= $this->helft_score) ){
            
            // Error
            throw new Exception("Minumum moet kleiner zijn dan de helft ".
                    "en de helft moet kleiner zijn dan het maximum.");

        } else {

            if ( empty($this->min_max_score)) {
                $this->min_max_score = $this->max_score;
            } else {
                if ( $this->min_max_score < $this->helft_score){
                    throw new Exception('Minimum punten voor de max score moet hoger dan de aantal punten voor een zes.');
                }
            }
        }
    }

    private function verdeelScoreOverPunten(){
        if ( !is_object($this->cesuur_tabel)){
            $this->cesuur_tabel = new CesuurTabel();
        }
        for( $i= 1; $i<=$this->max_score; $i++){
            if ( $i <= $this->min_score ){
                $this->cesuur_tabel->addResultaat($i, sprintf("%.2f", 1));
            } else if ($i < $this->helft_score){
                // ((Aantal te verdelen onvoldoende punten -1 /
                //   aantal te behalen punten tussen 1 en de helft) *
                //   behaalde punten - het aantal minimaal te behalen punten (1 grens))
                //   + 1
                $punt = (((AANTAL_ONVOLDOENDE_PUNTEN-1)/($this->helft_score-$this->min_score)) *
                        ($i-$this->min_score)+1);
                $punt = sprintf("%.2f", $punt);
                //echo "$i, $punt<br />";
                //$data_array[$i]=$punt;
                $this->cesuur_tabel->addResultaat($i, $punt);

            } else if ( $i >= $this->min_max_score ){
                //$data_array[$i]=sprintf("%.2f", 10);
                $this->cesuur_tabel->addResultaat($i, sprintf("%.2f", 10));

            } else {
                // Positief (6-10)
                // 4/(min_max-helft)*(punten-helft) + 6
                $punt = (AANTAL_VOLDOENDE_PUNTEN/($this->min_max_score-$this->helft_score) * ($i-$this->helft_score))+6;
                $punt = sprintf("%.2f", $punt);
                //echo "$i, $punt<br />";
                //$data_array[$i]=$punt;
                $this->cesuur_tabel->addResultaat($i, $punt);
            }

        } // for
    }
    
} // Cesuur

class TestCesuur{

    public function TestCesuur(){

        $test = new Cesuur(); 
        
        try{

            
            // Set mandatory values
            $test->setMaxPunten(40);
            $test->setMinPunten(10);
            $test->setMinMaxPunten(38);
            $test->setPuntenVoorEenZes(30);


            $test->setCardTypeResultPerScore();
            
            // test berekening.
            $test->bereken();
            
            // test get HTML code
            echo $test->getCesuurHTMLTable();
            

            $test->setCardTypeScorePerNumber();
            
            $test->bereken();
            
            // test get HTML code
            echo $test->getCesuurHTMLTable();

            echo "<pre>";
            $this->test_var_dump($test);
            echo "</pre>";

        }
        //catch exception
        catch(Exception $e)
        {
            echo 'Message : ' .$e->getMessage() . '<pre>' .$e->getTraceAsString(). '</pre>';
            
            $this->test_var_dump( $test );
            
        }
    }

    private function test_var_dump($var){

        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
}
?>
