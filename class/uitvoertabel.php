<?php
/**
 * Description of uitvoertabel
 *
 * @author G. Hoogendoorn
 * @version 0.1
 */
class UitvoerTabel {
    
    private $data_array = array();
    
    public function __construct( $array='' ) {
        //echo __FILE__.' '.__LINE__.' '.__FUNCTION__;
        /*echo '<pre>';
        var_dump($array);
        echo '</pre>';*/
        if (!empty($array)){
            $this->setDataArray($array);
        }
    }
    
    public function setDataArray($array){
        if( !is_array($array)){
            throw new Exception( __FILE__.' '.__LINE__.' dataArray moet Array zijn');
        }
        $this->data_array = $array;
    }
    
    function getTable( $array = '' ){
        if ( empty($this->data_array) && empty($array) ){
            throw new Exception( __FILE__.' '.__LINE__.' Geen data array gezet!');
        
        } else if (!empty($array)){
            $this->setDataArray($array);
        }

        $output = '';

        $firsth_row = TRUE;
        $output .= "<table>";
        foreach( $this->data_array as $idx => $values ){

            if( $firsth_row == TRUE ){
                // Table head
                $output .= "\n<thead>";
            }
            $output .= '<tr>';

            foreach ( $values as $idx => $score_array ){

                if ( is_array($score_array) ){
                    foreach( $score_array as $score => $numeric_score){
                        if( $firsth_row == TRUE ){
                            // Table head
                            $output .= '<th class="table_head" width="100">'. $numeric_score . "</th>";

                        } else {
                            $output .= '<td align="right">'. '<span class="score">'. $score . ' - <span class="cijfer">'. $numeric_score->getPunt(). '</span></td>';
                        }
                    }
                } else {
                    $output .= '<td>&nbsp;</td>';

                }
            }
            $output .= "</tr>";
            if( $firsth_row == TRUE ){
                // Table head
                $output .= "</thead>";
                $firsth_row = FALSE;
            }
            $output .= "\n";
        }
        $output .= '</table';

        return $output;
    }
}
?>
