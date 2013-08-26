<?

class Logger {

  private $usuario;
  private $ip;

  public function __construct($user, $ip_remote)
  {
    $this->usuario=$user;  				//El usuario que accedio
    $this->ip=$ip_remote; 				//La ip de la PC desde donde se accede
  }

  public function imprimir()
  {
        $hoy = date("m-d-Y");                         // 03-10-2001
        $LogFile=$hoy.".log";
        $LogFile = file_get_contents($LogFile);
        $ExplodedLogFile = explode("~", $LogFile);
        foreach ($ExplodedLogFile as $linea_log) {
                echo "************** VOLCADO del REGISTRO ".$hoy." *********************<br/>";
                echo $linea_log."<br/>";
                echo "************** FIN DEL VOLCADO del REGISTRO *********************<br/>";
        }

  }

  public function capturar($actividad)
  {
    $hoy = date("Y-m-d");                         // 03-10-2001
    $ahora = date("H:i:s");                         // 17:16:18
    $LogFile= "./log/".$hoy.".log";
    //$TimeRef = date('d-m-Y H:i T');

    $Handle = fopen($LogFile, 'a'); 
    $Data = $ahora.'|'.$this->usuario.'|'.$this->ip.'|'.$actividad.'~';
    fwrite($Handle, $Data); 
    fclose($Handle); 
    //return true;

  }

}

?>