<?php
include_once 'resultaat.php';
/**
 * Description of CesuurTabel
 * 
 *
 * @author G. Hoogendoorn
 * @version 0.1
 */
class CesuurTabel {
    
    private $kolom_max=10;
    /**
     *
     * @var Array Object lijst met Resultaat objectjes.
     * 
     */
    private $rij;
    private $item;
    private $resultaat;
    private $cesuur_list = array();   // $data_array
    private $cesuur_card = array();  // $cesuur_card
    private $card_type = 'results_per_score';


    public function CesuurTabel(){
        // Doe iets
        $this->rij;

    }
    
    /**
     *  Deze methode voegt een punt en een score toe aan de lijst.
     * $data_array[$i]=$punt;
     *
     * @param <type> $score
     * @param <type> $punt
     * @return <type>
     */
    public function addResultaat($score,$punt){
        // Voeg $score en $punt toe aan de $this->cesuur_list
        $this->cesuur_list[$score] = new Resultaat( $score, $punt );
        return TRUE;
    }
    
    public function getRij(){
        
    }

    /**
     *
     * @return Array 2-dimensional array 
     */
    public function getScoreCard(){
        
        return $this->cesuur_card;
    }
    
    
    public function listToScoreCard( $max=10, $rows=10 ){
        switch ( $this->card_type ){
            case 'results_per_number':
                $this->resultsPerNumber();
                break;
            
            case 'results_per_score':
                $this->resultsPerScore($max,$rows);
                break;
            
            default:
                throw new Exception(__FILE__.__LINE__.'Internal error: No valid score type.');
        }
    }
    
    public function reset(){
        $this->cesuur_card = '';
        
    }

    public function setKolom( $kolom=10 ){
        $this->kolom_max = $kolom;
    }

     
    public function setCardTypeScorePerNumber(){
        $this->card_type = 'results_per_number';
    }
    
    public function setCardTypeResultPerScore(){
        $this->card_type = 'results_per_score';
    }
    public function setScore( $score ){

    }
   
    
    private function resultsPerNumber($max_score=10){
         /*
         * $array =  10 => 1, 11 => 1.1 , 12 =>1.2 ...
         * change into:
         * $array =
         *          1 =>  11=>1.1   12=>1.2 13=>1.3 14=>1.4 15 =>1.5    ...
         *          2 =>  21=>2.1   22=>2.2 23=>2.3...
         *
         * change into:
         * $array = 1=>  11=>1.1   21=>2.1 31=>3.1 41=>4.1 51 =>5.1    ...
         *          2=>  12=>1.2   22=>2.2  ...
         *          3=>  13=>1.3   23=>2.3
        */
        if (empty($this->cesuur_list)){
            throw new Exception(__FILE__.__LINE__.'Internal error: No score list.');
        }
        
        $new_array = array();

        foreach( $this->cesuur_list as $idx => $result_object ){
            $new_array[(int)($result_object->getPunt())][$idx] = $result_object;
        }

        /* Create table header (row 1) */
        for( $i=1; $i<=10; $i++){
            $this->cesuur_card[0][][]=$i;
        }

        
        /* Create an empty cesuur_card */
        for($row_nr = 1; $row_nr<$max_score; $row_nr++){
            $this->cesuur_card[$row_nr]= array( 1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'');
        }
        
        // $idx_row = punten column
        foreach ( $new_array as $idx_row => $row_array ){
            $row_nr = 0;

            // $idx_col = score
            foreach ( $row_array as $idx_col => $getal_obj ){
                $row_nr++;
                $this->cesuur_card[$row_nr][(int)($getal_obj->getPunt())][$idx_col] = $getal_obj;
            }
        }
    }

    
    private function resultsPerScore($max, $rows){

        $this->cesuur_card = array();

        if (empty($this->cesuur_list)){
            throw new Exception(__FILE__.__LINE__.'Internal error: No score list.');
        }

        /**
         *  change array as :
         *      minimal 10 rows per column
         *      maximal 10 columns
         *
         *     10 => 1, 11 => 1.1 , 12 =>1.2 ...
         *
         *      10 => 1.0   20 => 2.0   30 => 3.0  ...
         *      11 => 1.1   21 => 2.1   31 => 3.1  ...
         *      12 => 1.2   22 => 2.2   32 => 3.2  ...
         *      ...
         */
         $items_per_row = $max/$rows;
         // Check whether there is a rest ( not all rows have equal length )
         $items_per_row += (($max%$rows > 0) ? 1 : 0);
         
/** @todo define 10 / 10 **/         
         if ( ($max/ 10) < 10 ){
             // 10 rows per column
             $items_per_col = 10;
         } else {

            $items_per_col = $max/10;
            // Check whether there is a rest ( not all rows have equal length )
            $items_per_col += (($max%10 > 0) ? 1 : 0);


         }
         /* Create empty table header (row 1) */

         $this->cesuur_card[0]=array();

         $item_nr=1;
         foreach( $this->cesuur_list as $idx => $score_obj ){

             //echo "$cesuur_card[$item_nr][".count($cesuur_card[$item_nr])."][$score] = $numeric_score;<br />";
             @$this->cesuur_card[$item_nr][count($cesuur_card[$item_nr])][$idx] = $score_obj;

             $item_nr++;
             if ( $item_nr % 11 == 0){
                 $item_nr = 1;
             }
         }
    }


}

class TestCesuurTabel {

    public function TestCesuurTabel(){

        $cesuur_tbl = new CesuurTabel();
        
        $cesuur_tbl->addResultaat(1, 1);
        $cesuur_tbl->addResultaat(2, 1);
        $cesuur_tbl->addResultaat(3, 1);

        /*
        echo "<pre>";
        var_dump($cesuur_tbl);
        echo "</pre>";
        //*/
    }
}
?>
