<?php

# -----------------------x-------------------------
#   Abstracción para el manejo de Base de Datos
# -----------------------x-------------------------

// Mensajes de error.
$msg_error[0]="No se pudo conectar con Base de datos ";
$msg_error[1]="No se pudo realizar consulta a la Base de datos ";
$msg_error[2]="Password o Usuario no existe";
$msg_error[3]="Password no valida";
$msg_error[4]="Usuario no existe";
$msg_error[5]="No está autorizado para realizar esta acción o entrar en esta página";
$msg_error[6]="Acceso no autorizado! Registrese";
$msg_error[7]="Acceso incorrecto!";


class DB {
//Usuarios en esta base de datos infodat3_id3 	
//Para conectarse desde PHP, debe utilizar la siguiente forma:
//$link=mysql_connect("localhost","infodat3_id3","password");
//mysql_select_db("infodat3_info3data");
        var $Host 		    = "localhost";  		// Hostname of our MySQL server
        var $Database 		= "temporal";		// Logical database name on that server
        var $User     		= "root";			// Database user
        var $Password 		= "";

//        var $Host 		= "localhost";  		// Hostname of our MySQL server
//        var $Database 		= "infodata3";		// Logical database name on that server
//        var $User 		= "root";			// Database user
//        var $Password 		= "";				// Database user's password

        var $Link_ID    	= 0;           			// Result of mysql_connect()
        var $Query_ID		= 0;           			// Result of most recent mysql_query()
        var $Record		= array();     			// Current mysql_fetch_array()-result
        var $Row;                      				// Current row number
        var $Cols;                      			// Current cols number
        var $Claves;                      			// Current cols keys
        var $Errno 		= 0;           			// Error state of query
        var $Error 		= "";

	# Crea un ID de enlace hacia la base de datos MySQL 
	# Allow to call $var = new DB($otherhost,$otherDB,$otheruser,$otherpass);
	# where $other* are connections vars different from 
	# $this->Host, etc

# -----------------------x-------------------------
# Constructor del Objeto
        
	function DB($altHost = "",$altDB = "",$altUser = "",$altPassword = "") {
		if ($altHost == "")
			$altHost = $this->Host;
		if ($altDB == "")
			$altDB = $this->Database;
		if ($altUser == "")
			$altUser = $this->User;
		if ($altPassword == "")
			$altPassword = $this->Password;

		$this->Host = $altHost;
		$this->Database = $altDB;
		$this->User = $altUser;
		$this->Password = $altPassword;
	}

# -----------------------x-------------------------
# Detener la ejecucion del Script
# en caso de error
# $msg : mensaje que se imprimira.
    
    function halt($msg) {
		?>
	  <FONT FACE="Arial, Helvetica, san-serif" SIZE="1">
	  <TABLE cellPadding=2 cellSpacing=1 bordercolor="#C0C0C0" align='center' border=1 style="width: 300px;border: 1px solid;border-collapse: collapse;">
	  <CAPTION style="background: #FF9900;padding: 4px;"><B>.: VNCSFE - Error :.</B></CAPTION>
		  <tr>
			<td>
			<LI><B>Database error: </B><DD><font color="#003399"><?php echo($msg); ?></font>.<br />
			<LI><B>MySQL error: </B><DD><font color="#003399"><?php echo($this->Errno." (".$this->Error.")"); ?></font><br /><br />
			<center><font color="#003399"><b><?php die("Consulta interrumpida."); ?></b></font></center>
			</td>
		  </tr>
	  </TABLE>
	  </FONT>
		<?php
    }

	
# -----------------------x-------------------------
# Conectarse al servidor de MySQL
	
	function connect() {
		global $DBType;
		
		if($this->Link_ID == 0) {
			$this->Link_ID = mysql_connect($this->Host, 
								$this->User, 
								$this->Password);
			if (!$this->Link_ID) {
				$this->halt("Link_ID == false, conexion fallada");
            }
            $SelectResult = mysql_select_db($this->Database, $this->Link_ID);
			if(!$SelectResult) {
				$this->Errno = mysql_errno($this->Link_ID);
				$this->Error = mysql_error($this->Link_ID);
				$this->halt("No se puede seleccionar la base de datos <I>".$this->Database."</I>");
			}
		}
	}
	

# -----------------------x-------------------------
# Enviar una consulta al servidor de MySQL
# $Query_String = the query

    
    function query($Query_String) {
		
		$this->connect();
		$this->Query_ID = mysql_query($Query_String,$this->Link_ID);
        $this->Row = 0;
        $this->Errno = mysql_errno();
        $this->Error = mysql_error();
        if (!$this->Query_ID) {
			$this->halt("Invalid SQL: ".$Query_String);
		}
		return $this->Query_ID;
	}


# -----------------------x-------------------------
# Muestra información de consulta
#

	function view_query() {
		$filas = $this->num_rows();
		if($filas > 0) {
			$this->next_record();
			//Nombre de los campos
			$clave = array_keys($this->Record); 
			echo("<TABLE border=0 bgcolor=#000000 cellpadding=2 cellspacing=1>");
			echo("<TR bgcolor=#D8D8C4>");
			for($i=0; $i<count($clave); $i++) {
				echo("<TD class=nombrecampo>".$clave[$i]."</TD>");
			}
			echo("</TR>");
			//Valores de los campos
			for($i=0; $i<$filas; $i++) {
				$valor = array_values($this->Record);
				echo("<TR bgcolor=#F6F6EB>");
				for($j=0; $j<count($this->Record); $j++) {
					echo("<TD class=texto>".$valor[$j]."</TD>");
				}		
				echo("</TR>");		
				$this->next_record();
			}	
			echo("</TABLE>");
		}
		else {
			echo("<b>No hay elementos</b>");
		}
	}


# -----------------------x-------------------------
# Retorna el proximo registro de una consulta MySQL query
# en un  array
	
    function next_record() {
		// asigno a la propiedad Record un regristo de la consulta
		$this->Record = mysql_fetch_array($this->Query_ID,MYSQL_ASSOC);
		$this->Row += 1;
		$this->Errno = mysql_errno();
		$this->Error = mysql_error();
		$stat = is_array($this->Record);
		if (!$stat) {
			//libera la memoria de la ultima consulta
			mysql_free_result($this->Query_ID);
			$this->Query_ID = 0;
		}
		return $this->Record;
    }


# -----------------------x-------------------------
# Retorna el nro de filas afectadas por una consulta
# exepto insert y borrar consulta
	
	function num_rows() {
		return mysql_num_rows($this->Query_ID);
	}

# -----------------------x-------------------------
# Retorna el nro de columnas afectadas por una consulta
# exepto insert y borrar consulta
	
	function num_cols() {
	  $this->Claves = array_keys ($this->Record);
	  $this->Cols =count($this->Claves);
	  $this->Cols = $this->Cols / 2;
	  return $this->Cols;
	}

# -----------------------x-------------------------
# Retorna el nro de filas afectadas
# by a UPDATE, INSERT or DELETE query
	
    function affected_rows() {
		return mysql_affected_rows($this->Link_ID);
	}
    
# -----------------------x-------------------------
# devuelve el id del ultimo elem insertado
    
	function insert_id() {
		return mysql_insert_id($this->Link_ID);
	}

# -----------------------x-------------------------
# Optimiza la tabla
# $tbl_name : the name of the table
	
	function optimize($tbl_name) {
		$this->connect();
		$this->Query_ID = @mysql_query("OPTIMIZE TABLE $tbl_name",$this->Link_ID);
	}

# -----------------------x-------------------------
# Libera la memoria luego de usar result
	
	function clean_results() {
		if($this->Query_ID != 0) mysql_free_result($this->Query_ID);
	}

# -----------------------x-------------------------
# Cierra el enlace a la base de datos

	
	function close() {
		if($this->Link_ID != 0) mysql_close($this->Link_ID);
	}


# -----------------------x-------------------------
#                 Fin de la Clase
# -----------------------x-------------------------
}

?>
