<?php 
session_start();
require_once 'functions.php';
requireHTTPS();

            if(isset($_SESSION['s232521_user'])){//in questo caso non effettuo la validazione dell'utente perchè è una pagina che viene richiamata asincronamente in una pagina al quale non è possible accedere senza essere correttamente loggati
                echo " <ul class='style1'>";
                $member=$_SESSION['s232521_user'];//utile per la successiva query
                $dearmember=$_SESSION['s232521_fname']." ".$_SESSION['s232521_lname'];
             
                                        $day=explode(':',date("M:d"));//calcolo il giorno attuale,puramente estetico
                                        $now = date("H:i:s");//calcolo il tempo attuale,che userò per verificare se il record è cancellabile o meno
                                        $tempNow=explode(':',$now);//per calcolare successivamente la differenza intera tra l'ora attuale e l ora di inserimento della prenotazione,in modo da verificare se siano passati i 60 secondi
                                        echo "Here it is the current time: $now <br>";
                                        
                                       
                                        
                                        $res = $connection->query( "SELECT  bcode,machine,insertTime, startingtime, duration FROM books WHERE client='$member'  AND startingtime>'$now' ORDER BY startingtime ");//con queste condizioni WHERE ottengo solo le prenotazioni cancellabili o per le quali devo attendere i 60 secondi
                                        //in questo caso non necessito dei meccanismi di gestione della concorrenza poichè solo l'utente puo' cancellare la sua prenotazione
                                        if($res==FALSE)
                                            echo "Query error <br>";
                                        if($res->num_rows){
                                        
                                          $rows=$res->num_rows;
                                          for($j=0;$j<$rows;++$j){
                                          $res->data_seek($j);
                                          $row=$res->fetch_array(MYSQLI_NUM);
                                         
                                          $tempInsert=explode(':',$row[2]);
                                          $t1=$tempNow[0].$tempNow[1].$tempNow[2];
                                          $t2=$tempInsert[0].$tempInsert[1].$tempInsert[2];
                                          
                                  
                                          $dinamicDiff=$t1-$t2;
                                          
                                          
                                      
                                           if($dinamicDiff>=100){//caso in cui le ho appena inserite e devo attendere 60 secondi per cancellare,ma copre anche lo stato del db al momento della consegna,essendo sicuramente
                                           //il tempo attuale maggiore di un minuto del tempo in cui ho inserito la prenotazione(00:01:00)
                                         
                                          $bcode[$j]=$row[0];
                                         
                                           echo "<li>".
                                                "<p class='date'><a>$day[0]<b>$day[1]</b><a>$row[3]</a></a></p>".
                                                "<h3>Dear $dearmember, your reservation:</h3>".
                                                "<p><a>machine:'$row[1]',duration:'$row[4]' minutes </a></p>".
                                              "</li>".
                                              
                                                "<form action='manage.php' method='post'>".
                                                "<input type='hidden' name='delete' value='yes'>".
                                                "<input type='hidden' name='bcode' value='$bcode[$j]'>".
                                                "<input id='del' type='submit'  value='DELETE RECORD'> </form>";
                                                


                                         }else{//caso in cui la differenza e >=0 e <100.In questo modo visualizzo comunque tutte le prenotazioni dell'utente che,unite al caricamento delle altre prenotazioni nella pagina richiamante,mi permette di visualizzare l'intero stato del db
                                             
                                             echo "<li>".
                                                "<p class='date'><a>$day[0]<b>$day[1]</b><a>$row[3]</a></a></p>".
                                                "<h3>Dear $dearmember,your reservation,but not eraseable yet:</h3>".
                                                "<p><a>machine:'$row[1]',duration:'$row[4]' minutes </a></p>".
                                              "</li>";
                                              
                                                
                                             
                                             }
                                         
                                         
                                         }
                                         echo "</ul>";
                                         $res->close();
                                         }else{//copre il caso in cui non ci siano prenotazioni cancellabili,o se sono in atto e quindi non piu' cancellabili 
                                            
                                            echo "<li>".
                                                "<p class='date'><a>$day[0]<b>$day[1]</b><a>$now</a></a></p>".
                                                "<h3>Dear $dearmember, your reservations:</h3>".
                                                "<p><a>No eraseable machine </a></p>".
                                              "</li>";
                                              echo" </ul>";
                                            
                                            
                                            
                                            }
                                         
                                
                }
?>